import { MapPin, Briefcase, Building2, Clock, Timer } from "lucide-react";
import { formatSalary, formatDate } from "@/utils/formatters";

export default function PublicJobCard({ job, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="group w-full rounded-2xl border border-gray-200 bg-white p-5 text-left shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
    >
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0 flex-1">
          <h3 className="truncate font-semibold text-gray-900 dark:text-white">
            {job.title}
          </h3>
          <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">
            {job.company_name}
          </p>
        </div>
        {job.experience?.experience_level && (
          <span className="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
            {job.experience.experience_level}
          </span>
        )}
      </div>

      <div className="mt-3 flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
        {job.location && (
          <span className="flex items-center gap-1">
            <MapPin size={13} />
            {job.location}
          </span>
        )}
        {job.job_type && (
          <span className="flex items-center gap-1">
            <Briefcase size={13} />
            {job.job_type.replace(/_/g, " ")}
          </span>
        )}
        {job.work_mode && (
          <span className="flex items-center gap-1">
            <Building2 size={13} />
            {job.work_mode.charAt(0).toUpperCase() + job.work_mode.slice(1)}
          </span>
        )}
        {job.experience?.min != null && job.experience?.max != null && (
          <span className="flex items-center gap-1">
            <Timer size={13} />
            {job.experience.min}–{job.experience.max} yrs
          </span>
        )}
        {job.created_at && (
          <span className="flex items-center gap-1 ml-auto">
            <Clock size={13} />
            {formatDate(job.created_at)}
          </span>
        )}
      </div>

      {(job.salary_details?.min || job.salary_details?.max) && (
        <p className="mt-2 text-sm font-medium text-indigo-600 dark:text-indigo-400">
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
