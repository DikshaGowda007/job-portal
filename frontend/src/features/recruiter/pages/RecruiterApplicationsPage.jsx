import { useState } from "react";
import { useSearchParams } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { recruiterApi } from "@/api/recruiter.api";
import { APPLICATION_STATUS, PAGINATION_DEFAULT } from "@/utils/constants";
import { formatDate, timeAgo } from "@/utils/formatters";
import {
  STATUS_BADGE,
  logoColor,
  NEXT_ACTIONS,
} from "@/features/recruiter/utils/applicationHelpers";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import ApplicationDetailDrawer from "@/features/recruiter/components/ApplicationDetailDrawer";
import { Clock, FileText, Briefcase, DollarSign, Timer } from "lucide-react";

const ALL_STATUSES = Object.values(APPLICATION_STATUS);

export default function RecruiterApplicationsPage() {
  const [searchParams] = useSearchParams();
  const jobIdFromUrl = searchParams.get("job_id");

  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [statusFilter, setStatusFilter] = useState("");
  const [selectedApp, setSelectedApp] = useState(null);
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-applications", page, statusFilter, jobIdFromUrl],
    queryFn: () =>
      recruiterApi
        .listApplications({
          page,
          per_page: PAGINATION_DEFAULT.PER_PAGE,
          status: statusFilter || undefined,
          job_post_id: jobIdFromUrl ? Number(jobIdFromUrl) : undefined,
        })
        .then((r) => r.data?.data),
  });

  const updateStatus = useMutation({
    mutationFn: (payload) => recruiterApi.updateApplicationStatus(payload),
    onSuccess: () =>
      queryClient.invalidateQueries({ queryKey: ["recruiter-applications"] }),
  });

  const applications = data?.applications ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
          Applications
        </h1>
        <p className="mt-1 text-gray-500 dark:text-gray-400">
          {jobIdFromUrl
            ? `Showing applications for job #${jobIdFromUrl}`
            : "All applications for your jobs"}
        </p>
      </div>

      {/* Status filter */}
      <div className="mb-6 flex flex-wrap gap-2">
        <button
          onClick={() => {
            setStatusFilter("");
            setPage(1);
          }}
          className={`rounded-full px-4 py-1.5 text-sm font-medium transition ${statusFilter === "" ? "bg-indigo-600 text-white" : "border border-gray-300 bg-white hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"}`}
        >
          All
        </button>
        {ALL_STATUSES.map((s) => (
          <button
            key={s}
            onClick={() => {
              setStatusFilter(s);
              setPage(1);
            }}
            className={`rounded-full px-4 py-1.5 text-sm font-medium capitalize transition ${statusFilter === s ? "bg-indigo-600 text-white" : "border border-gray-300 bg-white hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"}`}
          >
            {s}
          </button>
        ))}
      </div>

      {isLoading ? (
        <Loader />
      ) : applications.length === 0 ? (
        <EmptyState
          title="No applications"
          description="No applications match the selected filter."
        />
      ) : (
        <>
          <div className="space-y-4">
            {applications.map((app) => (
              <ApplicationCard
                key={app.id}
                app={app}
                onStatusChange={(status) =>
                  updateStatus.mutate({ id: app.id, status })
                }
                loading={updateStatus.isPending}
                onViewDetails={() => setSelectedApp(app)}
              />
            ))}
          </div>
          <Pagination
            page={page}
            totalPages={totalPages}
            onPageChange={setPage}
          />
        </>
      )}

      <ApplicationDetailDrawer
        app={selectedApp}
        onClose={() => setSelectedApp(null)}
      />
    </div>
  );
}

function ApplicationCard({ app, onStatusChange, loading, onViewDetails }) {
  const applicantName = app.applicant_name ?? app.user?.name ?? "Applicant";
  const actions = NEXT_ACTIONS[app.status] ?? [];

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-start gap-4">
        {/* Avatar */}
        <div
          className={`flex size-11 shrink-0 items-center justify-center rounded-xl text-base font-bold ${logoColor(applicantName)}`}
        >
          {applicantName.charAt(0).toUpperCase()}
        </div>

        <div className="min-w-0 flex-1">
          {/* Name + status */}
          <div className="flex flex-wrap items-center justify-between gap-2">
            <h3 className="font-semibold text-gray-900 dark:text-white">
              {applicantName}
            </h3>
            <span
              className={`rounded-full px-3 py-1 text-xs font-medium capitalize ${STATUS_BADGE[app.status] ?? "bg-gray-100 text-gray-600"}`}
            >
              {app.status}
            </span>
          </div>

          {/* Job + date */}
          <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
            {app.job_title ?? app.job?.title ?? "—"}
          </p>
          <p className="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
            <Clock size={11} />
            Applied {formatDate(app.created_at)} · {timeAgo(app.created_at)}
          </p>

          {/* Details row */}
          <div className="mt-3 flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
            {app.experience_years != null && (
              <span className="flex items-center gap-1">
                <Timer size={12} /> {app.experience_years} yrs exp
              </span>
            )}
            {app.expected_salary && (
              <span className="flex items-center gap-1">
                <DollarSign size={12} />
                {app.expected_salary_currency ?? ""}{" "}
                {Number(app.expected_salary).toLocaleString()}
                {app.notice_period ? ` · ${app.notice_period} notice` : ""}
              </span>
            )}
            {app.resume_path && (
              <a
                href={`${import.meta.env.VITE_API_BASE_URL}/storage/${app.resume_path}`}
                target="_blank"
                rel="noreferrer"
                className="flex items-center gap-1 text-indigo-600 hover:underline dark:text-indigo-400"
              >
                <FileText size={12} /> View Resume
              </a>
            )}
            {app.job_posting && (
              <span className="flex items-center gap-1">
                <Briefcase size={12} /> {app.job_posting}
              </span>
            )}
          </div>

          {/* Cover letter */}
          {app.cover_letter && (
            <p className="mt-2 line-clamp-2 text-xs text-gray-400 dark:text-gray-500">
              &ldquo;{app.cover_letter}&rdquo;
            </p>
          )}
        </div>
      </div>

      {/* Action buttons */}
      <div className="mt-4 flex flex-wrap items-center gap-2 border-t border-gray-100 pt-4 dark:border-gray-800">
        <button
          onClick={onViewDetails}
          className="rounded-lg border border-gray-300 px-4 py-1.5 text-xs font-medium text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
        >
          View Details
        </button>
        {actions.map(({ label, status, cls }) => (
          <button
            key={status}
            onClick={() => onStatusChange(status)}
            disabled={loading}
            className={`rounded-lg border px-4 py-1.5 text-xs font-medium transition disabled:opacity-60 ${cls}`}
          >
            {label}
          </button>
        ))}
      </div>
    </div>
  );
}
