import { MapPin, Clock } from "lucide-react";
import { formatSalary, timeAgo } from "@/utils/formatters";
import { WORK_MODE_STYLE, JOB_TYPE_STYLE } from "@/utils/styles";
import CompanyLogo from "@/components/common/CompanyLogo";

export default function PublicJobCard({ job, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="group w-full rounded-2xl border border-gray-200 bg-white p-5 text-left shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
    >
      {/* Logo + title + location */}
      <div className="flex items-start gap-3">
        <CompanyLogo name={job.company_name} />

        <div className="min-w-0 flex-1">
          <h3 className="truncate font-semibold text-gray-900 group-hover:text-indigo-600 dark:text-white dark:group-hover:text-indigo-400">
            {job.title}
          </h3>
          <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">
            {job.company_name}
          </p>
          {job.location && (
            <p className="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
              <MapPin size={11} className="shrink-0" />
              {job.location}
            </p>
          )}
        </div>

        {job.created_at && (
          <span className="flex shrink-0 items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
            <Clock size={11} />
            {timeAgo(job.created_at)}
          </span>
        )}
      </div>

      {/* Tag chips */}
      <div className="mt-3 flex flex-wrap gap-2">
        {job.work_mode && (
          <span className={`rounded-full px-2.5 py-1 text-xs font-medium capitalize ${WORK_MODE_STYLE[job.work_mode] ?? "bg-gray-100 text-gray-600"}`}>
            {job.work_mode}
          </span>
        )}
        {job.job_type && (
          <span className={`rounded-full px-2.5 py-1 text-xs font-medium ${JOB_TYPE_STYLE[job.job_type] ?? "bg-gray-100 text-gray-600"}`}>
            {job.job_type.replace(/_/g, " ")}
          </span>
        )}
        {job.experience?.experience_level && (
          <span className="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
            {job.experience.experience_level}
          </span>
        )}
        {job.experience?.min != null && job.experience?.max != null && (
          <span className="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
            {job.experience.min}–{job.experience.max} yrs
          </span>
        )}
      </div>

      {/* Salary */}
      {(job.salary_details?.min || job.salary_details?.max) && (
        <p className="mt-3 text-sm font-semibold text-indigo-600 dark:text-indigo-400">
          {formatSalary(
            job.salary_details.min,
            job.salary_details.max,
            job.salary_details.currency,
            job.salary_details.salary_type,
          )}
        </p>
      )}
    </button>
  );
}
