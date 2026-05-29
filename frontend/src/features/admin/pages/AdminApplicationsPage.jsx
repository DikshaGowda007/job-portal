import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { adminApi } from "@/api/admin.api";
import { APPLICATION_STATUS, PAGINATION_DEFAULT } from "@/utils/constants";
import { APPLICATION_STATUS_BADGE, APPLICATION_STATUS_DOT, entityColor } from "@/utils/styles";
import { formatDate, timeAgo } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import ApplicationDetailDrawer from "@/features/recruiter/components/ApplicationDetailDrawer";
import { Search, Clock, DollarSign, Timer, FileText, UserCheck } from "lucide-react";

const ALL_STATUSES = Object.values(APPLICATION_STATUS);

export default function AdminApplicationsPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [statusFilter, setStatusFilter] = useState("");
  const [search, setSearch] = useState("");
  const [selectedApp, setSelectedApp] = useState(null);

  const { data, isLoading } = useQuery({
    queryKey: ["admin-applications", page, statusFilter, search],
    queryFn: () =>
      adminApi
        .listApplications({
          page,
          per_page: PAGINATION_DEFAULT.PER_PAGE,
          status: statusFilter || undefined,
          text: search || undefined,
        })
        .then((r) => r.data?.data),
  });

  const applications = data?.applications ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;
  const total = data?.pagination?.total ?? null;

  return (
    <div className="space-y-5">
      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Applications</h1>
        <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
          {total !== null ? `${total} total applications` : "All applications across the platform"}
        </p>
      </div>

      {/* Filters */}
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div className="relative max-w-sm flex-1">
          <Search size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            value={search}
            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
            placeholder="Search by applicant or job…"
            className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500"
          />
        </div>

        <div className="flex flex-wrap gap-1.5">
          <button
            onClick={() => { setStatusFilter(""); setPage(1); }}
            className={`rounded-full px-3.5 py-1.5 text-xs font-medium transition ${statusFilter === "" ? "bg-indigo-600 text-white" : "border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800"}`}
          >
            All
          </button>
          {ALL_STATUSES.map((s) => (
            <button
              key={s}
              onClick={() => { setStatusFilter(s); setPage(1); }}
              className={`rounded-full px-3.5 py-1.5 text-xs font-medium transition ${statusFilter === s ? "bg-indigo-600 text-white" : "border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800"}`}
            >
              {s.charAt(0) + s.slice(1).toLowerCase()}
            </button>
          ))}
        </div>
      </div>

      {/* List */}
      {isLoading ? (
        <Loader />
      ) : applications.length === 0 ? (
        <EmptyState title="No applications" description="No applications match the selected filters." />
      ) : (
        <>
          <div className="space-y-3">
            {applications.map((app) => {
              const applicantName = app.applicant_name ?? app.user?.name ?? "Applicant";
              const jobTitle = app.job_title ?? app.job?.title ?? "—";
              return (
                <div
                  key={app.id}
                  className="flex items-center gap-4 rounded-2xl border border-gray-200 bg-white px-5 py-4 shadow-sm transition hover:border-indigo-200 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-800"
                >
                  {/* Avatar */}
                  <div className={`flex size-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold ${entityColor(applicantName)}`}>
                    {applicantName.charAt(0).toUpperCase()}
                  </div>

                  {/* Main info */}
                  <div className="min-w-0 flex-1">
                    <div className="flex flex-wrap items-center gap-2">
                      <span className="font-semibold text-gray-900 dark:text-white">{applicantName}</span>
                      <span className={`inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-medium ${APPLICATION_STATUS_BADGE[app.status] ?? "bg-gray-100 text-gray-600"}`}>
                        <span className={`size-1.5 rounded-full ${APPLICATION_STATUS_DOT[app.status] ?? "bg-gray-400"}`} />
                        {app.status ? app.status.charAt(0) + app.status.slice(1).toLowerCase() : "—"}
                      </span>
                    </div>
                    <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">
                      {jobTitle}
                      {app.company_name && (
                        <span className="text-gray-400 dark:text-gray-500"> · {app.company_name}</span>
                      )}
                    </p>
                    {app.recruiter_name && (
                      <p className="mt-0.5 flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400">
                        <UserCheck size={11} className="shrink-0" />
                        {app.recruiter_name}
                        {app.recruiter_email && (
                          <span className="text-gray-400 dark:text-gray-500">· {app.recruiter_email}</span>
                        )}
                      </p>
                    )}
                    <div className="mt-1.5 flex flex-wrap gap-3 text-xs text-gray-400 dark:text-gray-500">
                      <span className="flex items-center gap-1">
                        <Clock size={11} /> {formatDate(app.created_at)} · {timeAgo(app.created_at)}
                      </span>
                      {app.experience_years != null && (
                        <span className="flex items-center gap-1"><Timer size={11} /> {app.experience_years} yrs exp</span>
                      )}
                      {app.expected_salary && (
                        <span className="flex items-center gap-1">
                          <DollarSign size={11} />
                          {app.expected_salary_currency ?? ""} {Number(app.expected_salary).toLocaleString()}
                        </span>
                      )}
                      {app.resume_path && (
                        <a
                          href={`${import.meta.env.VITE_API_BASE_URL}/storage/${app.resume_path}`}
                          target="_blank"
                          rel="noreferrer"
                          onClick={(e) => e.stopPropagation()}
                          className="flex items-center gap-1 text-indigo-600 hover:underline dark:text-indigo-400"
                        >
                          <FileText size={11} /> Resume
                        </a>
                      )}
                    </div>
                  </div>

                  <button
                    onClick={() => setSelectedApp(app)}
                    className="shrink-0 rounded-xl border border-gray-200 px-4 py-2 text-xs font-medium text-gray-600 transition hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 dark:border-gray-700 dark:text-gray-400 dark:hover:border-indigo-700 dark:hover:bg-indigo-950/30 dark:hover:text-indigo-400"
                  >
                    View Details
                  </button>
                </div>
              );
            })}
          </div>

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      )}

      <ApplicationDetailDrawer
        app={selectedApp}
        onClose={() => setSelectedApp(null)}
        viewFn={(payload) => adminApi.viewApplication(payload)}
        updateStatusFn={(payload) => adminApi.updateApplicationStatus(payload)}
        historyFn={(payload) => adminApi.getApplicationHistory(payload)}
        invalidateKey="admin-applications"
      />
    </div>
  );
}
