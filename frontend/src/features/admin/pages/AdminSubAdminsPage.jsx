import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { adminApi } from "@/api/admin.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import { entityColor } from "@/utils/styles";
import { formatDate } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import { UserPlus, X, Mail, Calendar, ShieldCheck } from "lucide-react";

export default function AdminSubAdminsPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [showForm, setShowForm] = useState(false);
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ["admin-sub-admins", page],
    queryFn: () =>
      adminApi.listSubAdmins({ page, per_page: PAGINATION_DEFAULT.PER_PAGE }).then((r) => r.data?.data),
  });

  const createMutation = useMutation({
    mutationFn: (payload) => adminApi.createSubAdmin(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-sub-admins"] });
      setShowForm(false);
    },
  });

  const toggleMutation = useMutation({
    mutationFn: ({ user_id, status }) => adminApi.toggleUserStatus({ user_id, status }),
    onSuccess: () => queryClient.invalidateQueries({ queryKey: ["admin-sub-admins"] }),
  });

  const subAdmins = data?.data ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;
  const total = data?.pagination?.total ?? null;

  return (
    <div className="space-y-5">

      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Sub-Admins</h1>
          <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
            {total !== null ? `${total} sub-administrator${total !== 1 ? "s" : ""}` : "Manage sub-administrator accounts"}
          </p>
        </div>
        <button
          onClick={() => setShowForm(true)}
          className="flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
        >
          <UserPlus size={15} /> Add Sub-Admin
        </button>
      </div>

      {/* Create form — inline card */}
      {showForm && (
        <CreateSubAdminForm
          onSubmit={(p) => createMutation.mutate(p)}
          onCancel={() => setShowForm(false)}
          loading={createMutation.isPending}
          error={createMutation.error?.response?.data?.message ?? ""}
        />
      )}

      {/* List */}
      {isLoading ? (
        <Loader />
      ) : subAdmins.length === 0 && !showForm ? (
        <EmptyState title="No sub-admins yet" description="Add a sub-admin using the button above." />
      ) : subAdmins.length > 0 ? (
        <>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            {subAdmins.map((user) => {
              const name = `${user.first_name ?? ""} ${user.last_name ?? ""}`.trim();
              const initial = name.charAt(0).toUpperCase() || "S";
              const isActive = user.status === "active";
              return (
                <div
                  key={user.id}
                  className="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                  <div className="flex items-center gap-3">
                    <div className={`flex size-11 shrink-0 items-center justify-center rounded-xl text-base font-bold ${entityColor(name)}`}>
                      {initial}
                    </div>
                    <div className="min-w-0 flex-1">
                      <p className="truncate font-semibold text-gray-900 dark:text-white">{name || "—"}</p>
                      <div className="mt-0.5 flex items-center gap-1">
                        <ShieldCheck size={11} className="text-indigo-500" />
                        <span className="text-xs text-indigo-600 dark:text-indigo-400">Sub-Admin</span>
                      </div>
                    </div>
                    <span className={`shrink-0 inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ${isActive ? "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400" : "bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400"}`}>
                      <span className={`size-1.5 rounded-full ${isActive ? "bg-emerald-500" : "bg-red-500"}`} />
                      {isActive ? "Active" : "Inactive"}
                    </span>
                  </div>

                  <div className="space-y-1.5 border-t border-gray-100 pt-3 dark:border-gray-800">
                    <div className="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                      <Mail size={12} className="shrink-0" />
                      <span className="truncate">{user.email}</span>
                    </div>
                    <div className="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                      <Calendar size={12} className="shrink-0" />
                      <span>Joined {formatDate(user.created_at)}</span>
                    </div>
                  </div>

                  <button
                    onClick={() => toggleMutation.mutate({ user_id: user.id, status: isActive ? "inactive" : "active" })}
                    disabled={toggleMutation.isPending}
                    className={`w-full rounded-xl px-3 py-2 text-xs font-semibold transition disabled:opacity-50 ${isActive ? "bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-900/20 dark:text-red-400 dark:hover:bg-red-900/30" : "bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/30"}`}
                  >
                    {isActive ? "Deactivate" : "Activate"}
                  </button>
                </div>
              );
            })}
          </div>

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      ) : null}
    </div>
  );
}

function CreateSubAdminForm({ onSubmit, onCancel, loading, error }) {
  const [form, setForm] = useState({ first_name: "", last_name: "", email: "", password: "" });
  const set = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }));

  return (
    <div className="rounded-2xl border border-indigo-200 bg-indigo-50/40 p-6 shadow-sm dark:border-indigo-900/40 dark:bg-indigo-950/20">
      <div className="mb-5 flex items-center justify-between">
        <div>
          <h2 className="font-semibold text-gray-900 dark:text-white">New Sub-Admin</h2>
          <p className="mt-0.5 text-xs text-gray-500 dark:text-gray-400">They'll have access to manage users and jobs</p>
        </div>
        <button onClick={onCancel} className="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800">
          <X size={16} />
        </button>
      </div>

      <form onSubmit={(e) => { e.preventDefault(); onSubmit(form); }} className="space-y-4">
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <Field label="First Name">
            <input required value={form.first_name} onChange={set("first_name")} placeholder="Jane" className={inputCls} />
          </Field>
          <Field label="Last Name">
            <input required value={form.last_name} onChange={set("last_name")} placeholder="Doe" className={inputCls} />
          </Field>
          <Field label="Email">
            <input required type="email" value={form.email} onChange={set("email")} placeholder="jane@company.com" className={inputCls} />
          </Field>
          <Field label="Password">
            <input required type="password" value={form.password} onChange={set("password")} placeholder="Min 8 characters" className={inputCls} />
          </Field>
        </div>

        {error && (
          <p className="rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400">
            {error}
          </p>
        )}

        <div className="flex gap-3">
          <button
            type="submit"
            disabled={loading}
            className="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500"
          >
            {loading ? "Creating…" : "Create Sub-Admin"}
          </button>
          <button
            type="button"
            onClick={onCancel}
            className="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-transparent dark:text-gray-300 dark:hover:bg-gray-800"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
}

const inputCls =
  "w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500";

function Field({ label, children }) {
  return (
    <div className="flex flex-col gap-1.5">
      <label className="text-sm font-medium text-gray-700 dark:text-gray-300">{label}</label>
      {children}
    </div>
  );
}
