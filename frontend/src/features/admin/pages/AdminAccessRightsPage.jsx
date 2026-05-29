import { useState, useEffect } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useQuery, useMutation } from "@tanstack/react-query";
import { toast } from "sonner";
import { accessRightsApi } from "@/api/accessRights.api";
import { ROUTES } from "@/utils/routePaths";
import { ROLE_LABELS } from "@/utils/constants";
import { ROLE_STYLE } from "@/utils/styles";
import Loader from "@/components/common/Loader";
import { ArrowLeft, Save, ShieldCheck } from "lucide-react";

function Toggle({ checked, onChange }) {
  return (
    <button
      type="button"
      role="switch"
      aria-checked={checked}
      onClick={onChange}
      className={`relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none ${
        checked ? "bg-indigo-600" : "bg-gray-200 dark:bg-gray-700"
      }`}
    >
      <span
        className={`pointer-events-none inline-block h-4 w-4 rounded-full bg-white shadow-sm transition-transform duration-200 ${
          checked ? "translate-x-4" : "translate-x-0"
        }`}
      />
    </button>
  );
}

export default function AdminAccessRightsPage() {
  const { userId } = useParams();
  const navigate = useNavigate();
  const [accessMap, setAccessMap] = useState({});

  const { data, isLoading, isError } = useQuery({
    queryKey: ["access-rights", userId],
    queryFn: () => accessRightsApi.get({ user_id: Number(userId) }).then((r) => r.data?.data),
    enabled: !!userId,
  });

  useEffect(() => {
    if (!data) return;
    const map = {};
    data.groups.forEach((g) => g.permissions.forEach((p) => { map[p.key] = p.is_enabled; }));
    setAccessMap(map);
  }, [data]);

  const mutation = useMutation({
    mutationFn: (access_details) => accessRightsApi.edit({ user_id: Number(userId), access_details }),
    onSuccess: () => toast.success("Access rights updated"),
    onError: (err) => toast.error(err.response?.data?.message ?? "Failed to update"),
  });

  const toggle = (key) => setAccessMap((prev) => ({ ...prev, [key]: !prev[key] }));

  const handleSave = () => {
    const payload = {};
    Object.entries(accessMap).forEach(([k, v]) => { payload[k] = v ? 1 : 0; });
    mutation.mutate(payload);
  };

  if (isLoading) return <Loader />;

  if (isError || !data) {
    return (
      <div className="flex flex-col items-center gap-4 py-20 text-center">
        <p className="text-sm text-gray-500">Could not load access rights for this user.</p>
        <button onClick={() => navigate(-1)} className="text-sm text-indigo-600 hover:underline">Go back</button>
      </div>
    );
  }

  const enabledCount = Object.values(accessMap).filter(Boolean).length;
  const totalCount = Object.keys(accessMap).length;

  return (
    <div className="mx-auto max-w-3xl space-y-6">

      {/* Top bar */}
      <div className="flex items-center justify-between">
        <button
          onClick={() => navigate(ROUTES.ADMIN_USERS)}
          className="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200"
        >
          <ArrowLeft size={16} /> Back to Users
        </button>
        <button
          onClick={handleSave}
          disabled={mutation.isPending}
          className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60"
        >
          <Save size={14} />
          {mutation.isPending ? "Saving…" : "Save Changes"}
        </button>
      </div>

      {/* User + summary card */}
      <div className="flex items-center justify-between rounded-2xl border border-gray-200 bg-white px-6 py-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div className="flex items-center gap-4">
          <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-lg font-bold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400">
            {data.name?.charAt(0)?.toUpperCase() ?? "?"}
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="font-semibold text-gray-900 dark:text-white">{data.name || "—"}</p>
              <span className={`rounded-full px-2.5 py-0.5 text-xs font-medium ${ROLE_STYLE[data.role] ?? "bg-gray-100 text-gray-600"}`}>
                {ROLE_LABELS[data.role] ?? "Unknown"}
              </span>
            </div>
            <p className="mt-0.5 text-sm text-gray-400">{data.email || "—"}</p>
          </div>
        </div>
        <div className="flex items-center gap-2 rounded-xl bg-indigo-50 px-4 py-2 dark:bg-indigo-900/20">
          <ShieldCheck size={16} className="text-indigo-500" />
          <span className="text-sm font-semibold text-indigo-700 dark:text-indigo-400">
            {enabledCount} <span className="font-normal text-indigo-400">/ {totalCount}</span>
          </span>
        </div>
      </div>

      {/* Permission groups */}
      <div className="space-y-4">
        {data.groups.map((group) => {
          const groupEnabled = group.permissions.filter((p) => accessMap[p.key]).length;
          return (
            <div
              key={group.group}
              className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
            >
              {/* Group header */}
              <div className="flex items-center justify-between border-b border-gray-100 px-5 py-3 dark:border-gray-800">
                <p className="text-sm font-semibold text-gray-700 dark:text-gray-300">{group.group}</p>
                <span className="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                  {groupEnabled} / {group.permissions.length} enabled
                </span>
              </div>

              {/* Permissions grid */}
              <div className="grid grid-cols-1 divide-y divide-gray-50 dark:divide-gray-800/60 sm:grid-cols-2 sm:divide-y-0">
                {group.permissions.map((perm, idx) => (
                  <label
                    key={perm.key}
                    className={`flex cursor-pointer items-center justify-between px-5 py-3.5 hover:bg-gray-50 dark:hover:bg-gray-800/40 ${
                      idx % 2 === 0 && idx === group.permissions.length - 1 && group.permissions.length % 2 !== 0
                        ? "sm:col-span-2"
                        : ""
                    } ${
                      idx > 1 ? "sm:border-t sm:border-gray-50 sm:dark:border-gray-800/60" : ""
                    }`}
                  >
                    <span className={`text-sm ${accessMap[perm.key] ? "font-medium text-gray-900 dark:text-white" : "text-gray-500 dark:text-gray-400"}`}>
                      {perm.title}
                    </span>
                    <Toggle checked={!!accessMap[perm.key]} onChange={() => toggle(perm.key)} />
                  </label>
                ))}
              </div>
            </div>
          );
        })}
      </div>

    </div>
  );
}
