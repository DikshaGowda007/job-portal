import { MapPin, Briefcase, Building2, Clock } from "lucide-react";
import { formatSalary, formatDate } from "@/utils/formatters";

const statusColors = {
  OPEN: "bg-green-100 text-green-700",
  CLOSED: "bg-red-100 text-red-700",
  EXPIRED: "bg-gray-100 text-gray-600",
  draft: "bg-yellow-100 text-yellow-700",
  published: "bg-green-100 text-green-700",
};

export default function JobCard({ job, actions }) {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-start justify-between gap-3">
        <div className="min-w-0 flex-1">
          <h3 className="truncate font-semibold text-gray-900 dark:text-white">{job.title}</h3>
          <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">{job.company_name}</p>
        </div>
        {job.status && (
          <span className={`shrink-0 rounded-full px-3 py-1 text-xs font-medium capitalize ${statusColors[job.status] ?? "bg-gray-100 text-gray-600"}`}>
            {job.status}
          </span>
        )}
      </div>

      <div className="mt-3 flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
        {job.location && (
          <span className="flex items-center gap-1"><MapPin size={14} />{job.location}</span>
        )}
        {job.job_type && (
          <span className="flex items-center gap-1"><Briefcase size={14} />{job.job_type.replace(/_/g, " ")}</span>
        )}
        {job.work_mode?.charAt(0).toUpperCase() + job.work_mode?.slice(1) && (
          <span className="flex items-center gap-1"><Building2 size={14} />{job.work_mode?.charAt(0).toUpperCase() + job.work_mode?.slice(1)}</span>
        )}
        {job.expires_at && (
          <span className="flex items-center gap-1"><Clock size={14} />Expires {formatDate(job.expires_at)}</span>
        )}
      </div>

      {(job.salary_details?.min || job.salary_details?.max) && (
        <p className="mt-2 text-sm font-medium text-indigo-600 dark:text-indigo-400">
          {formatSalary(job.salary_details.min, job.salary_details.max, job.salary_details.currency, job.salary_details.salary_type)}
        </p>
      )}

      {actions && <div className="mt-4 flex flex-wrap gap-2">{actions}</div>}
    </div>
  );
}
