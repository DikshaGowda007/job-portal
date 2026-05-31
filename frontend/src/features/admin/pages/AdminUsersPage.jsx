import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { adminApi } from "@/api/admin.api";
import { PAGINATION_DEFAULT, ROLE_LABELS } from "@/utils/constants";
import { ROLE_STYLE, entityColor } from "@/utils/styles";
import { formatDate, formatDateTime } from "@/utils/formatters";
import { ROUTES } from "@/utils/routePaths";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import { Search, ShieldCheck, UserCheck, Users, Crown, X, Mail, Phone, Calendar, Clock, BadgeCheck } from "lucide-react";

const ROLE_FILTERS = [
  { label: "All",        value: "",  icon: Users },
  { label: "Recruiters", value: "3", icon: UserCheck },
  { label: "Seekers",    value: "4", icon: Users },
  { label: "Sub-Admins", value: "2", icon: ShieldCheck },
  { label: "Admins",     value: "1", icon: Crown },
];

export default function AdminUsersPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [search, setSearch] = useState("");
  const [roleFilter, setRoleFilter] = useState("");
  const [selectedUserId, setSelectedUserId] = useState(null);
  const queryClient = useQueryClient();
  const navigate = useNavigate();

  const { data, isLoading } = useQuery({
    queryKey: ["admin-users", page, search, roleFilter],
    queryFn: () =>
      adminApi.listUsers({
        page,
        per_page: PAGINATION_DEFAULT.PER_PAGE,
        search: search || undefined,
        role: roleFilter || undefined,
      }).then((r) => r.data?.data),
  });

  const toggleMutation = useMutation({
    mutationFn: ({ user_id, status }) => adminApi.toggleUserStatus({ user_id, status }),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["admin-users"] }),
  });

  const users = data?.data ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;
  const total = data?.pagination?.total ?? null;

  return (
    <div className="space-y-5">
      {/* Header */}
      <div className="flex flex-wrap items-center justify-between gap-3">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
          <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
            {total !== null ? `${total} registered users` : "Manage all registered users"}
          </p>
        </div>
      </div>

      {/* Search + role filter */}
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div className="relative max-w-sm flex-1">
          <Search size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            value={search}
            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
            placeholder="Search by name or email…"
            className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500"
          />
        </div>

        <div className="flex flex-wrap gap-1.5">
          {ROLE_FILTERS.map(({ label, value }) => (
            <button
              key={value}
              onClick={() => { setRoleFilter(value); setPage(1); }}
              className={`rounded-full px-3.5 py-1.5 text-xs font-medium transition ${
                roleFilter === value
                  ? "bg-indigo-600 text-white"
                  : "border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800"
              }`}
            >
              {label}
            </button>
          ))}
        </div>
      </div>

      {/* Table */}
      {isLoading ? (
        <Loader />
      ) : users.length === 0 ? (
        <EmptyState title="No users found" description="Try a different search or filter." />
      ) : (
        <>
          <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-900/60">
                  <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">User</th>
                  <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Role</th>
                  <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Joined</th>
                  <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                  <th className="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Action</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-gray-100 dark:divide-gray-800">
                {users.map((user) => {
                  const name = `${user.first_name ?? ""} ${user.last_name ?? ""}`.trim();
                  const initial = name.charAt(0).toUpperCase() || "?";
                  const isActive = user.status === "active";
                  return (
                    <tr key={user.id} className="transition hover:bg-gray-50 dark:hover:bg-gray-800/40">
                      <td className="px-5 py-4">
                        <div className="flex items-center gap-3">
                          <div className={`flex size-9 shrink-0 items-center justify-center rounded-xl text-sm font-bold ${entityColor(name)}`}>
                            {initial}
                          </div>
                          <div>
                            <p className="font-medium text-gray-900 dark:text-white">{name || "—"}</p>
                            <p className="text-xs text-gray-400 dark:text-gray-500">{user.email}</p>
                          </div>
                        </div>
                      </td>
                      <td className="px-5 py-4">
                        <span className={`rounded-full px-2.5 py-1 text-xs font-medium ${ROLE_STYLE[user.user_type] ?? "bg-gray-100 text-gray-600"}`}>
                          {ROLE_LABELS[user.user_type] ?? "Unknown"}
                        </span>
                      </td>
                      <td className="px-5 py-4 text-sm text-gray-500 dark:text-gray-400">{formatDateTime(user.created_at)}</td>
                      <td className="px-5 py-4">
                        <span className={`inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ${isActive ? "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400" : "bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400"}`}>
                          <span className={`size-1.5 rounded-full ${isActive ? "bg-emerald-500" : "bg-red-500"}`} />
                          {isActive ? "Active" : "Inactive"}
                        </span>
                      </td>
                      <td className="px-5 py-4">
                        <div className="flex items-center gap-2">
                          <button
                            onClick={() => setSelectedUserId(user.id)}
                            className="rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 dark:border-gray-700 dark:text-gray-400 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/30 dark:hover:text-indigo-400"
                          >
                            View
                          </button>
                          {[2, 3].includes(Number(user.user_type)) && (
                            <button
                              onClick={() => navigate(ROUTES.ADMIN_ACCESS_RIGHTS.replace(":userId", user.id))}
                              className="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-medium text-indigo-700 transition hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-400 dark:hover:bg-indigo-900/40"
                            >
                              Edit
                            </button>
                          )}
                          <button
                            onClick={() => toggleMutation.mutate({ user_id: user.id, status: isActive ? "inactive" : "active" })}
                            disabled={toggleMutation.isPending}
                            className={`rounded-lg px-3 py-1.5 text-xs font-medium transition disabled:opacity-50 ${
                              isActive
                                ? "border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400"
                                : "border border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400"
                            }`}
                          >
                            {isActive ? "Deactivate" : "Activate"}
                          </button>
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      )}

      <UserDetailDrawer userId={selectedUserId} onClose={() => setSelectedUserId(null)} />
    </div>
  );
}

function UserDetailDrawer({ userId, onClose }) {
  const { data, isLoading } = useQuery({
    queryKey: ["admin-user-view", userId],
    queryFn: () => adminApi.viewUser({ user_id: userId }).then((r) => r.data?.data?.data),
    enabled: !!userId,
  });

  if (!userId) return null;

  const name = data ? `${data.first_name ?? ""} ${data.last_name ?? ""}`.trim() : "";
  const isActive = data?.status === "active";

  return (
    <>
      <div className="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm" onClick={onClose} />
      <div className="fixed inset-y-0 right-0 z-50 flex w-full max-w-md flex-col bg-white shadow-2xl dark:bg-gray-900">
        {/* Header */}
        <div className="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-gray-800">
          <h2 className="text-base font-semibold text-gray-900 dark:text-white">User Details</h2>
          <button onClick={onClose} className="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
            <X size={16} />
          </button>
        </div>

        <div className="flex-1 overflow-y-auto px-6 py-5">
          {isLoading ? (
            <div className="flex h-40 items-center justify-center text-sm text-gray-400">Loading…</div>
          ) : data ? (
            <div className="space-y-6">
              {/* Avatar + name */}
              <div className="flex items-center gap-4">
                <div className="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-xl font-bold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
                  {name.charAt(0).toUpperCase() || "?"}
                </div>
                <div>
                  <p className="text-lg font-semibold text-gray-900 dark:text-white">{name || "—"}</p>
                  <div className="mt-0.5 flex items-center gap-2">
                    <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${ROLE_STYLE[data.user_type] ?? "bg-gray-100 text-gray-600"}`}>
                      {ROLE_LABELS[data.user_type] ?? "Unknown"}
                    </span>
                    <span className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ${isActive ? "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400" : "bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400"}`}>
                      <span className={`size-1.5 rounded-full ${isActive ? "bg-emerald-500" : "bg-red-500"}`} />
                      {isActive ? "Active" : "Inactive"}
                    </span>
                  </div>
                </div>
              </div>

              {/* Details */}
              <div className="space-y-3 rounded-2xl border border-gray-100 p-4 dark:border-gray-800">
                <Row icon={<Mail size={14} />} label="Email" value={data.email || "—"} />
                {data.phone && <Row icon={<Phone size={14} />} label="Phone" value={data.phone} />}
                <Row icon={<BadgeCheck size={14} />} label="Verified" value={data.verified ? "Yes" : "No"} />
                <Row icon={<Calendar size={14} />} label="Joined" value={formatDateTime(data.created_at)} />
                <Row icon={<Clock size={14} />} label="Last Login" value={data.last_login ? formatDateTime(data.last_login) : "Never"} />
              </div>
            </div>
          ) : (
            <p className="text-sm text-gray-400">User not found.</p>
          )}
        </div>
      </div>
    </>
  );
}

function Row({ icon, label, value }) {
  return (
    <div className="flex items-center gap-3">
      <span className="text-gray-400">{icon}</span>
      <span className="w-24 shrink-0 text-xs text-gray-500 dark:text-gray-400">{label}</span>
      <span className="text-sm font-medium text-gray-900 dark:text-white">{value}</span>
    </div>
  );
}
