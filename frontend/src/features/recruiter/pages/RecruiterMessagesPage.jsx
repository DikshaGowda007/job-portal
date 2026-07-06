import { useState, useRef, useEffect } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { useAuth } from "@/context/AuthContext";
import { useChat } from "@/hooks/useChat";
import { ROUTES } from "@/utils/routePaths";
import { timeAgo, chatTime } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import { MessageSquare, Send, ArrowLeft, Check, CheckCheck } from "lucide-react";

export default function RecruiterMessagesPage() {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const { user } = useAuth();
  const [selected, setSelected] = useState(null);
  const [text, setText] = useState("");
  const messagesEndRef = useRef(null);

  const { data: conversations = [], isLoading } = useQuery({
    queryKey: ["recruiter-conversations"],
    queryFn: () => recruiterApi.getConversations().then((r) => r.data?.data ?? []),
  });

  const { messages: chatMessages, typingUser, isOtherOnline, onTyping, addMessage } = useChat({
    applicationId: selected?.application_id ?? null,
    initialMessages: selected?.messages ?? [],
    markReadFn: recruiterApi.markRead,
    typingFn: recruiterApi.sendTyping,
  });

  // Auto-scroll to latest message whenever the thread or typing indicator updates
  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [chatMessages, typingUser]);

  const sendMutation = useMutation({
    mutationFn: ({ application_id, message }) =>
      recruiterApi.sendMessage({ application_id, message }),
    onSuccess: (res, { message }) => {
      const newMsg = res.data?.data;
      if (!newMsg) return;

      addMessage({
        id: newMsg.id,
        application_id: selected.application_id,
        sender_id: newMsg.sender_id,
        sender: user ? `${user.first_name ?? ""} ${user.last_name ?? ""}`.trim() : "You",
        message: newMsg.message,
        read_at: newMsg.read_at ?? null,
        created_at: newMsg.created_at,
      });

      queryClient.setQueryData(["recruiter-conversations"], (prev = []) =>
        prev.map((conv) =>
          conv.application_id === selected.application_id
            ? { ...conv, last_message: message, last_message_at: newMsg.created_at }
            : conv
        )
      );

      setText("");
    },
    onError: () => toast.error("Failed to send message"),
  });

  const handleSend = () => {
    const trimmed = text.trim();
    if (!trimmed || !selected) return;
    sendMutation.mutate({ application_id: selected.application_id, message: trimmed });
  };

  const handleKeyDown = (e) => {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      handleSend();
    }
  };

  if (isLoading) return <Loader />;

  if (conversations.length === 0) {
    return (
      <div className="flex flex-col items-center justify-center py-24 text-center">
        <MessageSquare size={40} className="mb-3 text-gray-300 dark:text-gray-700" />
        <p className="font-medium text-gray-500 dark:text-gray-400">No messages yet</p>
        <p className="mt-1 text-sm text-gray-400 dark:text-gray-500">
          Messages from applicants will appear here.
        </p>
      </div>
    );
  }

  return (
    <div className="flex h-[calc(100vh-140px)] gap-4 overflow-hidden">
      {/* Conversation list */}
      <aside
        className={`flex w-full shrink-0 flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 sm:w-72 ${
          selected ? "hidden sm:flex" : "flex"
        }`}
      >
        <div className="border-b border-gray-200 px-5 py-4 dark:border-gray-800">
          <h2 className="font-semibold text-gray-900 dark:text-white">Messages</h2>
          <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
            {conversations.length} conversation{conversations.length !== 1 ? "s" : ""}
          </p>
        </div>

        <div className="scrollbar-thin flex-1 divide-y divide-gray-100 overflow-y-auto dark:divide-gray-800">
          {conversations.map((conv) => {
            const isActive = selected?.application_id === conv.application_id;
            return (
              <button
                key={conv.application_id}
                onClick={() => {
                  setSelected(conv);
                  queryClient.setQueryData(["recruiter-conversations"], (prev = []) =>
                    prev.map((c) =>
                      c.application_id === conv.application_id ? { ...c, unread_count: 0 } : c
                    )
                  );
                }}
                className={`w-full px-4 py-3.5 text-left transition ${
                  isActive
                    ? "bg-indigo-50 dark:bg-indigo-900/20"
                    : "hover:bg-gray-50 dark:hover:bg-gray-800/40"
                }`}
              >
                <div className="flex items-start gap-3">
                  <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                    {conv.seeker_name?.charAt(0)?.toUpperCase() ?? "?"}
                  </div>
                  <div className="min-w-0 flex-1">
                    <div className="flex items-center justify-between gap-1">
                      <p className="truncate text-sm font-semibold text-gray-900 dark:text-white">
                        {conv.seeker_name ?? "Applicant"}
                      </p>
                      <span className="shrink-0 text-[10px] text-gray-400 dark:text-gray-500">
                        {timeAgo(conv.last_message_at)}
                      </span>
                    </div>
                    <p className="text-xs text-gray-500 dark:text-gray-400">{conv.job_title}</p>
                    <div className="mt-1 flex items-center justify-between gap-1">
                      <p className="truncate text-xs text-gray-400 dark:text-gray-500">
                        {conv.last_message}
                      </p>
                      {conv.unread_count > 0 && (
                        <span className="ml-1 flex size-5 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white">
                          {conv.unread_count > 9 ? "9+" : conv.unread_count}
                        </span>
                      )}
                    </div>
                  </div>
                </div>
              </button>
            );
          })}
        </div>
      </aside>

      {/* Message panel */}
      <div
        className={`flex flex-1 flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 ${
          selected ? "flex" : "hidden sm:flex"
        }`}
      >
        {!selected ? (
          <div className="flex flex-1 flex-col items-center justify-center text-center">
            <MessageSquare size={32} className="mb-2 text-gray-300 dark:text-gray-700" />
            <p className="text-sm text-gray-400 dark:text-gray-500">
              Select a conversation to read messages
            </p>
          </div>
        ) : (
          <>
            {/* Header */}
            <div className="flex items-center justify-between border-b border-gray-200 px-4 py-3.5 dark:border-gray-800 sm:px-6 sm:py-4">
              <div className="flex items-center gap-3">
                <button
                  onClick={() => setSelected(null)}
                  className="rounded-lg p-1 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 sm:hidden"
                >
                  <ArrowLeft size={18} />
                </button>
                <div className="flex size-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400">
                  {selected.seeker_name?.charAt(0)?.toUpperCase() ?? "?"}
                </div>
                <div>
                  <p className="font-semibold text-gray-900 dark:text-white">
                    {selected.seeker_name ?? "Applicant"}
                  </p>
                  <div className="flex items-center gap-1.5">
                    <span
                      className={`size-2 rounded-full ${
                        isOtherOnline ? "bg-green-500" : "bg-gray-300 dark:bg-gray-600"
                      }`}
                    />
                    <p className="text-xs text-gray-500 dark:text-gray-400">
                      {isOtherOnline ? "Online" : selected.job_title}
                    </p>
                  </div>
                </div>
              </div>
              <button
                onClick={() =>
                  navigate(ROUTES.RECRUITER_APPLICATION_DETAIL?.replace(":id", selected.application_id) ?? "#")
                }
                className="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
              >
                View Application →
              </button>
            </div>

            {/* Messages */}
            <div className="scrollbar-thin flex-1 space-y-4 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">
              {chatMessages.map((msg) => {
                const isMine = msg.sender_id === user?.id;
                const senderLabel = typeof msg.sender === "string"
                  ? msg.sender
                  : msg.sender
                    ? `${msg.sender.first_name} ${msg.sender.last_name}`.trim()
                    : selected.seeker_name ?? "Applicant";

                return (
                  <div
                    key={msg.id}
                    className={`flex items-end gap-2.5 ${isMine ? "flex-row-reverse" : ""}`}
                  >
                    {!isMine && (
                      <div className="flex size-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        {senderLabel.charAt(0).toUpperCase()}
                      </div>
                    )}
                    <div className={`max-w-[70%] ${isMine ? "items-end" : "items-start"} flex flex-col`}>
                      <div
                        className={`rounded-2xl px-4 py-2.5 ${
                          isMine
                            ? "rounded-br-none bg-indigo-600 text-white"
                            : "rounded-bl-none bg-gray-100 dark:bg-gray-800"
                        }`}
                      >
                        <p
                          className={`text-sm leading-relaxed ${
                            isMine ? "text-white" : "text-gray-800 dark:text-gray-200"
                          }`}
                        >
                          {msg.message}
                        </p>
                      </div>
                      <div className="mt-1 flex items-center gap-1">
                        <p className="text-[11px] text-gray-400 dark:text-gray-500">
                          {isMine ? "You" : senderLabel} · {chatTime(msg.created_at)}
                        </p>
                        {isMine && (
                          msg.read_at
                            ? <CheckCheck size={12} className="text-indigo-400" />
                            : <Check size={12} className="text-gray-400" />
                        )}
                      </div>
                    </div>
                  </div>
                );
              })}

              {/* Typing indicator */}
              {typingUser && (
                <div className="flex items-end gap-2.5">
                  <div className="flex size-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    {typingUser.charAt(0).toUpperCase()}
                  </div>
                  <div className="rounded-2xl rounded-bl-none bg-gray-100 px-4 py-2.5 dark:bg-gray-800">
                    <p className="text-xs italic text-gray-400 dark:text-gray-500">
                      {typingUser} is typing…
                    </p>
                  </div>
                </div>
              )}

              <div ref={messagesEndRef} />
            </div>

            {/* Compose */}
            <div className="border-t border-gray-200 px-4 py-3 dark:border-gray-800 sm:px-6">
              <div className="flex items-end gap-2">
                <textarea
                  rows={1}
                  value={text}
                  onChange={(e) => {
                    setText(e.target.value);
                    onTyping();
                  }}
                  onKeyDown={handleKeyDown}
                  placeholder="Type a message… (Enter to send)"
                  className="flex-1 resize-none rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500"
                  style={{ maxHeight: "120px", overflowY: "auto" }}
                />
                <button
                  onClick={handleSend}
                  disabled={!text.trim() || sendMutation.isPending}
                  className="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-600 text-white transition hover:bg-indigo-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                  <Send size={16} />
                </button>
              </div>
            </div>
          </>
        )}
      </div>
    </div>
  );
}
