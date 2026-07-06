import { useCallback, useEffect, useRef, useState } from "react";
import { useAuth } from "@/context/AuthContext";
import { useEcho } from "@/context/EchoContext";

/**
 * useChat manages real-time state for a single conversation thread.
 *
 * @param {object} params
 * @param {number|null} params.applicationId   - The active conversation's application ID
 * @param {array}  params.initialMessages      - Messages preloaded from the REST API
 * @param {function} params.markReadFn         - API call to mark messages as read
 * @param {function} params.typingFn           - API call to broadcast typing status
 *
 * @returns {{ messages, typingUser, onTyping, addMessage }}
 */
export function useChat({ applicationId, initialMessages = [], markReadFn, typingFn }) {
  const echo = useEcho();
  const { user } = useAuth();

  const [messages, setMessages] = useState(initialMessages);
  const [typingUser, setTypingUser] = useState(null);
  const [isOtherOnline, setIsOtherOnline] = useState(false);

  // Refs for typing debounce — refs don't cause re-renders on change
  const isTypingRef = useRef(false);
  const typingDebounceTimer = useRef(null);
  const typingClearTimer = useRef(null);

  // Reset state when the user switches to a different conversation
  useEffect(() => {
    setMessages(initialMessages);
    setTypingUser(null);
    setIsOtherOnline(false);
  // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [applicationId]);

  // Subscribe to the WebSocket channel for the active conversation
  useEffect(() => {
    if (!echo || !applicationId) return;

    const channel = echo.private(`conversation.${applicationId}`);

    // Note: the '.' prefix is required when the event uses broadcastAs().
    // Without it, Echo looks for the full PHP class name as the event name.
    channel.listen(".message.sent", (event) => {
      setMessages((prev) => {
        // Deduplicate: the sender receives the WS event too, but they already
        // added the message via addMessage() in onSuccess — skip if ID exists.
        if (prev.some((m) => m.id === event.id)) return prev;
        return [...prev, { ...event, sender: event.sender_name }];
      });
    });

    channel.listen(".message.read", (event) => {
      // Update read_at on messages that the other person just read
      setMessages((prev) =>
        prev.map((m) =>
          event.message_ids.includes(m.id) ? { ...m, read_at: event.read_at } : m
        )
      );
    });

    channel.listen(".user.typing", (event) => {
      // Ignore our own typing events (we sent them, no need to show)
      if (event.user_id === user?.id) return;

      clearTimeout(typingClearTimer.current);

      if (event.is_typing) {
        setTypingUser(event.user_name);
        // Safety fallback: clear after 3s in case the 'stop typing' event is lost
        typingClearTimer.current = setTimeout(() => setTypingUser(null), 3000);
      } else {
        setTypingUser(null);
      }
    });

    // Mark messages as read the moment this conversation is opened
    if (markReadFn) {
      markReadFn({ application_id: applicationId }).catch(() => {});
    }

    // Presence channel: tracks who is currently online in this conversation.
    // echo.join() subscribes to a presence channel (prefix 'presence-' is automatic).
    const presence = echo.join(`conversation.${applicationId}`)
      .here((members) => {
        // 'here' fires once on subscribe with all currently online members
        setIsOtherOnline(members.some((m) => m.id !== user?.id));
      })
      .joining((member) => {
        // 'joining' fires when someone else connects
        if (member.id !== user?.id) setIsOtherOnline(true);
      })
      .leaving((member) => {
        // 'leaving' fires when someone disconnects
        if (member.id !== user?.id) setIsOtherOnline(false);
      });

    return () => {
      clearTimeout(typingClearTimer.current);
      clearTimeout(typingDebounceTimer.current);
      isTypingRef.current = false;
      echo.leave(`conversation.${applicationId}`);       // leaves private channel
      echo.leave(`presence-conversation.${applicationId}`); // leaves presence channel
    };
  }, [echo, applicationId]);

  /**
   * Call this from the textarea onChange to broadcast typing indicators.
   * Sends 'is_typing: true' on first keypress, then 'is_typing: false'
   * after 2 seconds of inactivity — debounced to avoid API spam.
   */
  const onTyping = useCallback(() => {
    if (!applicationId || !typingFn) return;

    if (!isTypingRef.current) {
      isTypingRef.current = true;
      typingFn({ application_id: applicationId, is_typing: true }).catch(() => {});
    }

    clearTimeout(typingDebounceTimer.current);
    typingDebounceTimer.current = setTimeout(() => {
      isTypingRef.current = false;
      typingFn({ application_id: applicationId, is_typing: false }).catch(() => {});
    }, 2000);
  }, [applicationId, typingFn]);

  /**
   * Add a message to local state directly.
   * Used by the send mutation's onSuccess to show the message immediately
   * without waiting for the WebSocket event.
   */
  const addMessage = useCallback((msg) => {
    setMessages((prev) => {
      if (prev.some((m) => m.id === msg.id)) return prev;
      return [...prev, msg];
    });
  }, []);

  return { messages, typingUser, isOtherOnline, onTyping, addMessage };
}
