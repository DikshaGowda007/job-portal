import { useRef, useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { notificationsApi } from "@/api/notifications.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { Bell, Briefcase, BellRing, CheckCheck } from "lucide-react";

function formatNotifTime(dateStr) {
  if (!dateStr) return "";
  const d = new Date(dateStr);
  const time = d.toLocaleTimeString("en-IN", { hour: "2-digit", minute: "2-digit", hour12: true });
  const days = Math.floor((Date.now() - d.getTime()) / 86400000);
  if (days === 0) return `Today, ${time}`;
  if (days === 1) return `Yesterday, ${time}`;
  const date = d.toLocaleDateString("en-IN", { day: "2-digit", month: "short" });
  return `${date}, ${time}`;
}

const NOTIFICATION_TYPES = {
  APPLICATION_RECEIVED: "APPLICATION_RECEIVED",
  APPLICATION_STATUS_CHANGED: "APPLICATION_STATUS_CHANGED",
  JOB_ALERT_MATCH: "JOB_ALERT_MATCH",
};

function getNavTarget(notification, role) {
  if (!notification.link_id) return null;
  if (notification.type === NOTIFICATION_TYPES.APPLICATION_RECEIVED) {
    return ROUTES.RECRUITER_APPLICATIONS;
  }
  if (notification.type === NOTIFICATION_TYPES.APPLICATION_STATUS_CHANGED) {
    return ROUTES.SEEKER_APPLICATION_DETAIL.replace(":id", notification.link_id);
  }
  if (notification.type === NOTIFICATION_TYPES.JOB_ALERT_MATCH) {
    return ROUTES.SEEKER_JOB_DETAIL.replace(":id", notification.link_id);
  }
  return null;
}

export default function NotificationBell() {
  const { isAuthenticated, role } = useAuth();
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const [open, setOpen] = useState(false);
  const ref = useRef(null);

  useEffect(() => {
    const handler = (e) => {
      if (ref.current && !ref.current.contains(e.target)) setOpen(false);
    };
    document.addEventListener("mousedown", handler);
    return () => document.removeEventListener("mousedown", handler);
  }, []);

  const { data } = useQuery({
    queryKey: ["notifications"],
    queryFn: () => notificationsApi.list().then((r) => r.data?.data),
    enabled: isAuthenticated,
    refetchInterval: 30_000,
  });

  const notifications = data?.notifications ?? [];
  const unreadCount = data?.unread_count ?? 0;

  const markReadMutation = useMutation({
    mutationFn: (id) => notificationsApi.markRead(id),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["notifications"] }),
  });

  const handleNotificationClick = (n) => {
    if (!n.is_read) markReadMutation.mutate(n.id);
    const target = getNavTarget(n, role);
    if (target) navigate(target);
    setOpen(false);
  };

  const handleMarkAll = () => {
    markReadMutation.mutate(null);
    setOpen(false);
  };

  if (!isAuthenticated) return null;

  return (
    <div className="relative flex shrink-0 items-center" ref={ref}>
      <button
        onClick={() => setOpen((v) => !v)}
        className="relative flex size-9 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
        aria-label="Notifications"
      >
        <Bell size={18} />
        {unreadCount > 0 && (
          <span className="absolute right-0.5 top-0.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
            {unreadCount > 9 ? "9+" : unreadCount}
          </span>
        )}
      </button>

      {open && (
        <div className="absolute right-0 top-12 z-50 w-80 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-gray-700 dark:bg-gray-900">
          {/* Header */}
          <div className="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-gray-800">
            <p className="text-sm font-semibold text-gray-900 dark:text-white">
              Notifications
              {unreadCount > 0 && (
                <span className="ml-2 rounded-full bg-red-100 px-1.5 py-0.5 text-xs font-medium text-red-600 dark:bg-red-900/40 dark:text-red-400">
                  {unreadCount} new
                </span>
              )}
            </p>
            {unreadCount > 0 && (
              <button
                onClick={handleMarkAll}
                className="flex items-center gap-1 text-xs text-indigo-600 hover:underline dark:text-indigo-400"
              >
                <CheckCheck size={13} /> Mark all read
              </button>
            )}
          </div>

          {/* List */}
          <div className="max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-800">
            {notifications.length === 0 ? (
              <div className="flex flex-col items-center justify-center py-10 text-center">
                <Bell size={24} className="mb-2 text-gray-300 dark:text-gray-700" />
                <p className="text-sm text-gray-400 dark:text-gray-500">No notifications yet</p>
              </div>
            ) : (
              notifications.map((n) => (
                <button
                  key={n.id}
                  onClick={() => handleNotificationClick(n)}
                  className={`flex w-full items-start gap-3 px-4 py-3 text-left transition hover:bg-gray-50 dark:hover:bg-gray-800/50 ${
                    !n.is_read ? "bg-indigo-50/60 dark:bg-indigo-900/10" : ""
                  }`}
                >
                  <div
                    className={`mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-full ${
                      n.type === NOTIFICATION_TYPES.APPLICATION_RECEIVED
                        ? "bg-green-100 text-green-600 dark:bg-green-900/40 dark:text-green-400"
                        : n.type === NOTIFICATION_TYPES.JOB_ALERT_MATCH
                          ? "bg-amber-100 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400"
                          : "bg-indigo-100 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400"
                    }`}
                  >
                    {n.type === NOTIFICATION_TYPES.JOB_ALERT_MATCH ? (
                      <BellRing size={14} />
                    ) : (
                      <Briefcase size={14} />
                    )}
                  </div>
                  <div className="min-w-0 flex-1">
                    <p className={`text-sm ${!n.is_read ? "font-semibold text-gray-900 dark:text-white" : "text-gray-700 dark:text-gray-300"}`}>
                      {n.title}
                    </p>
                    <p className="mt-0.5 text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{n.body}</p>
                    <p className="mt-1 text-[11px] text-gray-400 dark:text-gray-500">{formatNotifTime(n.created_at)}</p>
                  </div>
                  {!n.is_read && (
                    <span className="mt-1.5 size-2 shrink-0 rounded-full bg-indigo-600 dark:bg-indigo-400" />
                  )}
                </button>
              ))
            )}
          </div>
        </div>
      )}
    </div>
  );
}
