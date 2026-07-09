import { useQuery } from "@tanstack/react-query";
import { adminApi } from "@/api/admin.api";
import Loader from "@/components/common/Loader";
import {
  Briefcase,
  Users,
  FileText,
  TrendingUp,
  UserCheck,
  Clock,
  CheckCircle2,
  XCircle,
  Star,
  Award,
  RotateCcw,
  Eye,
  BarChart3,
} from "lucide-react";

const STATUS_CONFIG = [
  { key: "PENDING",     label: "Pending",     color: "bg-yellow-400", text: "text-yellow-700", bg: "bg-yellow-50 dark:bg-yellow-900/20" },
  { key: "REVIEWED",    label: "Reviewed",    color: "bg-blue-400",   text: "text-blue-700",   bg: "bg-blue-50 dark:bg-blue-900/20" },
  { key: "SHORTLISTED", label: "Shortlisted", color: "bg-indigo-500", text: "text-indigo-700", bg: "bg-indigo-50 dark:bg-indigo-900/20" },
  { key: "INTERVIEW",   label: "Interview",   color: "bg-purple-400", text: "text-purple-700", bg: "bg-purple-50 dark:bg-purple-900/20" },
  { key: "OFFERED",     label: "Offered",     color: "bg-cyan-400",   text: "text-cyan-700",   bg: "bg-cyan-50 dark:bg-cyan-900/20" },
  { key: "HIRED",       label: "Hired",       color: "bg-emerald-500",text: "text-emerald-700",bg: "bg-emerald-50 dark:bg-emerald-900/20" },
  { key: "REJECTED",    label: "Rejected",    color: "bg-red-400",    text: "text-red-700",    bg: "bg-red-50 dark:bg-red-900/20" },
  { key: "WITHDRAWN",   label: "Withdrawn",   color: "bg-gray-400",   text: "text-gray-600",   bg: "bg-gray-50 dark:bg-gray-800" },
];

const JOB_STATUS_CONFIG = [
  { key: "PUBLISHED", label: "Published", dot: "bg-emerald-500" },
  { key: "OPEN",      label: "Open",      dot: "bg-blue-500" },
  { key: "CLOSED",    label: "Closed",    dot: "bg-gray-400" },
  { key: "EXPIRED",   label: "Expired",   dot: "bg-red-400" },
  { key: "DRAFT",     label: "Draft",     dot: "bg-yellow-400" },
];

function StatCard({ icon: Icon, label, value, accent, sub }) {
  const accents = {
    indigo: "bg-indigo-50 text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-400",
    violet: "bg-violet-50 text-violet-600 dark:bg-violet-900/40 dark:text-violet-400",
    emerald: "bg-emerald-50 text-emerald-600 dark:bg-emerald-900/40 dark:text-emerald-400",
    blue: "bg-blue-50 text-blue-600 dark:bg-blue-900/40 dark:text-blue-400",
    amber: "bg-amber-50 text-amber-600 dark:bg-amber-900/40 dark:text-amber-400",
  };
  return (
    <div className="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className={`flex size-12 shrink-0 items-center justify-center rounded-xl ${accents[accent]}`}>
        <Icon size={22} />
      </div>
      <div className="min-w-0">
        <p className="text-sm text-gray-500 dark:text-gray-400">{label}</p>
        <p className="mt-0.5 text-2xl font-bold text-gray-900 dark:text-white">{value ?? "—"}</p>
        {sub && <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">{sub}</p>}
      </div>
    </div>
  );
}

export default function AdminDashboard() {
  const { data, isLoading } = useQuery({
    queryKey: ["admin-dashboard"],
    queryFn: () => adminApi.dashboard().then((r) => r.data?.data),
  });

  if (isLoading) return <Loader />;

  const stats = data?.stats ?? {};
  const byRole = stats.users_by_role ?? {};
  const appsByStatus = stats.applications_by_status ?? {};
  const jobsByStatus = stats.jobs_by_status ?? {};

  const totalApps = stats.total_applications ?? 0;
  const maxAppCount = Math.max(...STATUS_CONFIG.map((s) => appsByStatus[s.key] ?? 0), 1);

  return (
    <div className="space-y-6">

      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Admin Dashboard</h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">Platform overview and key metrics</p>
      </div>

      {/* Top stat cards */}
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <StatCard icon={Briefcase}  label="Total Jobs"        value={stats.total_jobs}      accent="indigo"  sub="All job postings" />
        <StatCard icon={Users}      label="Total Users"       value={stats.total_users}     accent="violet"  sub={`${byRole['3'] ?? 0} recruiters · ${byRole['4'] ?? 0} seekers`} />
        <StatCard icon={FileText}   label="Total Applications" value={totalApps}            accent="blue"    sub={`${stats.recent_applications ?? 0} in last 30 days`} />
        <StatCard icon={TrendingUp} label="New Registrations" value={stats.recent_registrations} accent="emerald" sub="Last 30 days" />
      </div>

      {/* Middle row */}
      <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {/* Application pipeline */}
        <div className="lg:col-span-2 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <div className="mb-5 flex items-center gap-2">
            <BarChart3 size={17} className="text-indigo-500" />
            <h2 className="font-semibold text-gray-900 dark:text-white">Application Pipeline</h2>
            {totalApps > 0 && (
              <span className="ml-auto text-xs text-gray-400 dark:text-gray-500">{totalApps} total</span>
            )}
          </div>

          <div className="space-y-3">
            {STATUS_CONFIG.map(({ key, label, color, text, bg }) => {
              const count = appsByStatus[key] ?? 0;
              const pct = totalApps > 0 ? Math.round((count / totalApps) * 100) : 0;
              const barPct = maxAppCount > 0 ? (count / maxAppCount) * 100 : 0;
              return (
                <div key={key} className="flex items-center gap-3">
                  <span className="w-24 shrink-0 text-sm text-gray-500 dark:text-gray-400">{label}</span>
                  <div className="flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800" style={{ height: 8 }}>
                    <div
                      className={`h-full rounded-full transition-all duration-500 ${color}`}
                      style={{ width: `${barPct}%` }}
                    />
                  </div>
                  <span className={`w-14 shrink-0 rounded-full px-2.5 py-0.5 text-center text-xs font-medium ${bg} ${text}`}>
                    {count} · {pct}%
                  </span>
                </div>
              );
            })}
          </div>
        </div>

        {/* Right column */}
        <div className="space-y-4">

          {/* User breakdown */}
          <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 className="mb-4 font-semibold text-gray-900 dark:text-white">Users by Role</h2>
            <div className="space-y-3">
              {[
                { label: "Job Seekers",  value: byRole['4'], icon: Users,     cls: "text-blue-500" },
                { label: "Recruiters",   value: byRole['3'], icon: UserCheck, cls: "text-indigo-500" },
                { label: "Sub-Admins",   value: byRole['2'], icon: Star,      cls: "text-violet-500" },
                { label: "Admins",       value: byRole['1'], icon: Award,     cls: "text-amber-500" },
              ].map(({ label, value, icon: Icon, cls }) => (
                <div key={label} className="flex items-center justify-between">
                  <div className="flex items-center gap-2">
                    <Icon size={15} className={cls} />
                    <span className="text-sm text-gray-600 dark:text-gray-400">{label}</span>
                  </div>
                  <span className="text-sm font-semibold text-gray-900 dark:text-white">{value ?? 0}</span>
                </div>
              ))}
            </div>
          </div>

          {/* Jobs by status */}
          <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h2 className="mb-4 font-semibold text-gray-900 dark:text-white">Jobs by Status</h2>
            <div className="space-y-3">
              {JOB_STATUS_CONFIG.map(({ key, label, dot }) => {
                const count = jobsByStatus[key] ?? 0;
                if (count === 0) return null;
                return (
                  <div key={key} className="flex items-center justify-between">
                    <div className="flex items-center gap-2">
                      <span className={`size-2 rounded-full ${dot}`} />
                      <span className="text-sm text-gray-600 dark:text-gray-400">{label}</span>
                    </div>
                    <span className="text-sm font-semibold text-gray-900 dark:text-white">{count}</span>
                  </div>
                );
              })}
              {Object.keys(jobsByStatus).length === 0 && (
                <p className="text-sm text-gray-400 dark:text-gray-500">No jobs yet</p>
              )}
            </div>
          </div>

        </div>
      </div>

      {/* Bottom quick-stats row */}
      <div className="grid grid-cols-2 gap-4 sm:grid-cols-4">
        {[
          { icon: Clock,        label: "Pending Review",  value: appsByStatus["PENDING"]     ?? 0, cls: "text-yellow-500" },
          { icon: Eye,          label: "Shortlisted",     value: appsByStatus["SHORTLISTED"]  ?? 0, cls: "text-indigo-500" },
          { icon: CheckCircle2, label: "Hired",           value: appsByStatus["HIRED"]        ?? 0, cls: "text-emerald-500" },
          { icon: XCircle,      label: "Rejected",        value: appsByStatus["REJECTED"]     ?? 0, cls: "text-red-500" },
        ].map(({ icon: Icon, label, value, cls }) => (
          <div key={label} className="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <Icon size={20} className={cls} />
            <div>
              <p className="text-xs text-gray-500 dark:text-gray-400">{label}</p>
              <p className="text-xl font-bold text-gray-900 dark:text-white">{value}</p>
            </div>
          </div>
        ))}
      </div>

    </div>
  );
}
