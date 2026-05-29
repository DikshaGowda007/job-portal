import { useState, useRef, useEffect } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { timeAgo } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import { MessageSquare, Send, ArrowLeft } from "lucide-react";

function chatTime(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  const time = d.toLocaleTimeString("en-IN", { hour: "2-digit", minute: "2-digit", hour12: true });
  const days = Math.floor((Date.now() - d.getTime()) / 86400000);
  if (days === 0) return `Today, ${time}`;
  if (days === 1) return `Yesterday, ${time}`;
  const date = d.toLocaleDateString("en-IN", { day: "2-digit", month: "short" });
  return `${date}, ${time}`;
}

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

  useEffect(() => {
    if (selected) {
      const updated = conversations.find((c) => c.application_id === selected.application_id);
      if (updated) setSelected(updated);
    }
  }, [conversations]);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [selected?.messages]);

  const sendMutation = useMutation({
    mutationFn: ({ application_id, message }) =>
      recruiterApi.sendMessage({ application_id, message }),
    onSuccess: (res, { message }) => {
      const newMsg = res.data?.data;
      if (!newMsg) return;

      queryClient.setQueryData(["recruiter-conversations"], (prev = []) =>
        prev.map((conv) =>
          conv.application_id === selected.application_id
            ? {
                ...conv,
                last_message: message,
                last_message_at: newMsg.created_at,
                messages: [
                  ...conv.messages,
                  {
                    id: newMsg.id,
                    message: newMsg.message,
                    sender_id: newMsg.sender_id,
                    sender: user ? `${user.first_name ?? ""} ${user.last_name ?? ""}`.trim() : "You",
                    created_at: newMsg.created_at,
                  },
                ],
              }
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

        <div className="flex-1 divide-y divide-gray-100 overflow-y-auto dark:divide-gray-800">
          {conversations.map((conv) => {
            const isActive = selected?.application_id === conv.application_id;
            return (
              <button
                key={conv.application_id}
                onClick={() => setSelected(conv)}
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
                    <p className="mt-1 truncate text-xs text-gray-400 dark:text-gray-500">
                      {conv.last_message}
                    </p>
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
                  <p className="text-xs text-gray-500 dark:text-gray-400">{selected.job_title}</p>
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
            <div className="flex-1 space-y-4 overflow-y-auto px-4 py-4 sm:px-6 sm:py-5">
              {selected.messages.map((msg) => {
                const isMine = msg.sender_id === user?.id;
                return (
                  <div
                    key={msg.id}
                    className={`flex items-end gap-2.5 ${isMine ? "flex-row-reverse" : ""}`}
                  >
                    {!isMine && (
                      <div className="flex size-7 shrink-0 items-center justify-center rounded-full bg-gray-100 text-xs font-bold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                        {selected.seeker_name?.charAt(0)?.toUpperCase() ?? "A"}
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
                      <p className="mt-1 text-[11px] text-gray-400 dark:text-gray-500">
                        {isMine ? "You" : selected.seeker_name ?? "Applicant"} · {chatTime(msg.created_at)}
                      </p>
                    </div>
                  </div>
                );
              })}
              <div ref={messagesEndRef} />
            </div>

            {/* Compose */}
            <div className="border-t border-gray-200 px-4 py-3 dark:border-gray-800 sm:px-6">
              <div className="flex items-end gap-2">
                <textarea
                  rows={1}
                  value={text}
                  onChange={(e) => setText(e.target.value)}
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
