import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { jobsApi } from "@/api/jobs.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import { JOB_STATUS_STYLE, WORK_MODE_STYLE, entityColor } from "@/utils/styles";
import { formatDate, formatSalary, timeAgo } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import { Search, MapPin, Clock, Briefcase, Building2 } from "lucide-react";

const STATUS_FILTERS = ["All", "PUBLISHED", "OPEN", "DRAFT", "CLOSED", "EXPIRED"];

export default function AdminJobsPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [search, setSearch] = useState("");
  const [statusFilter, setStatusFilter] = useState("");

  const { data, isLoading } = useQuery({
    queryKey: ["admin-jobs", page, search, statusFilter],
    queryFn: () =>
      jobsApi.list({
        page,
        per_page: PAGINATION_DEFAULT.PER_PAGE,
        text: search || undefined,
        status: statusFilter || undefined,
      }).then((r) => r.data?.data),
  });

  const jobs = data?.jobs ?? [];
  const totalPages = data?.total_pages ?? 1;
  const total = data?.total ?? null;

  return (
    <div className="space-y-5">
      {/* Header */}
      <div>
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">All Jobs</h1>
        <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
          {total !== null ? `${total} job postings on the platform` : "Overview of all job postings"}
        </p>
      </div>

      {/* Search + filter */}
      <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
        <div className="relative max-w-sm flex-1">
          <Search size={15} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            value={search}
            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
            placeholder="Search by title or company…"
            className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500"
          />
        </div>

        <div className="flex flex-wrap gap-1.5">
          {STATUS_FILTERS.map((s) => {
            const val = s === "All" ? "" : s;
            return (
              <button
                key={s}
                onClick={() => { setStatusFilter(val); setPage(1); }}
                className={`rounded-full px-3.5 py-1.5 text-xs font-medium transition ${
                  statusFilter === val
                    ? "bg-indigo-600 text-white"
                    : "border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800"
                }`}
              >
                {s}
              </button>
            );
          })}
        </div>
      </div>

      {/* Grid */}
      {isLoading ? (
        <Loader />
      ) : jobs.length === 0 ? (
        <EmptyState title="No jobs found" description="Try a different search or filter." />
      ) : (
        <>
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            {jobs.map((job) => {
              const initial = job.company_name?.charAt(0)?.toUpperCase() ?? "?";
              return (
                <div
                  key={job.job_id}
                  className="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-200 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-800"
                >
                  {/* Top row */}
                  <div className="flex items-start gap-3">
                    <div className={`flex size-11 shrink-0 items-center justify-center rounded-xl text-base font-bold ${entityColor(job.company_name)}`}>
                      {initial}
                    </div>
                    <div className="min-w-0 flex-1">
                      <h3 className="truncate font-semibold text-gray-900 dark:text-white">{job.title}</h3>
                      <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">{job.company_name}</p>
                    </div>
                    <span className={`shrink-0 rounded-full px-2.5 py-1 text-xs font-medium ${JOB_STATUS_STYLE[job.status] ?? "bg-gray-100 text-gray-600"}`}>
                      {job.status}
                    </span>
                  </div>

                  {/* Meta */}
                  <div className="flex flex-wrap gap-3 text-xs text-gray-400 dark:text-gray-500">
                    {job.location && (
                      <span className="flex items-center gap-1"><MapPin size={11} />{job.location}</span>
                    )}
                    {job.work_mode && (
                      <span className={`rounded-full px-2 py-0.5 capitalize ${WORK_MODE_STYLE[job.work_mode] ?? "bg-gray-100 text-gray-600"}`}>
                        {job.work_mode}
                      </span>
                    )}
                    {job.job_type && (
                      <span className="flex items-center gap-1">
                        <Briefcase size={11} />{job.job_type.replace(/_/g, " ")}
                      </span>
                    )}
                  </div>

                  {/* Salary */}
                  {(job.salary_details?.min || job.salary_details?.max) && (
                    <p className="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                      {formatSalary(job.salary_details.min, job.salary_details.max, job.salary_details.currency, job.salary_details.salary_type)}
                    </p>
                  )}

                  {/* Footer */}
                  <div className="flex items-center justify-between border-t border-gray-100 pt-3 text-xs text-gray-400 dark:border-gray-800 dark:text-gray-500">
                    <span className="flex items-center gap-1"><Clock size={11} />{timeAgo(job.created_at)}</span>
                    <span>Posted {formatDate(job.created_at)}</span>
                  </div>
                </div>
              );
            })}
          </div>

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      )}
    </div>
  );
}
