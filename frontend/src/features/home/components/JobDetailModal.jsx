import { useQuery } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import {
  X,
  MapPin,
  Briefcase,
  Building2,
  Clock,
  Users,
  GraduationCap,
  CheckCircle2,
} from "lucide-react";
import { jobsApi } from "@/api/jobs.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { formatSalary, formatDate } from "@/utils/formatters";

export default function JobDetailModal({ jobId, onClose }) {
  const navigate = useNavigate();
  const { isAuthenticated } = useAuth();

  const { data: job, isLoading } = useQuery({
    queryKey: ["public-job", jobId],
    queryFn: () => jobsApi.get({ id: Number(jobId) }).then((r) => r.data?.data),
    enabled: !!jobId,
  });

  const handleApply = () => {
    onClose();
    if (!isAuthenticated) {
      navigate(ROUTES.LOGIN, { state: { from: `/jobs/${jobId}` } });
    } else {
      navigate(ROUTES.PUBLIC_JOB_DETAIL.replace(":id", jobId));
    }
  };

  return (
    <div
      className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
      onClick={(e) => e.target === e.currentTarget && onClose()}
    >
      <div className="flex max-h-[90vh] w-full max-w-2xl flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-xl dark:border-gray-800 dark:bg-gray-900">
        {/* Header */}
        <div className="flex items-start justify-between gap-4 border-b border-gray-200 px-6 py-5 dark:border-gray-800">
          {isLoading || !job ? (
            <div className="space-y-2">
              <div className="h-5 w-56 animate-pulse rounded bg-gray-200 dark:bg-gray-700" />
              <div className="h-3.5 w-36 animate-pulse rounded bg-gray-200 dark:bg-gray-700" />
            </div>
          ) : (
            <div className="min-w-0 flex-1">
              <h2 className="text-lg font-bold text-gray-900 dark:text-white">
                {job.title}
              </h2>
              <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                {job.company_name}
              </p>
            </div>
          )}
          <button
            onClick={onClose}
            className="shrink-0 rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 dark:hover:text-gray-300"
          >
            <X size={18} />
          </button>
        </div>

        {/* Body */}
        <div className="flex-1 overflow-y-auto">
          {isLoading ? (
            <div className="space-y-3 px-6 py-5">
              {[100, 80, 60, 90, 70].map((w, i) => (
                <div
                  key={i}
                  className="h-3.5 animate-pulse rounded bg-gray-200 dark:bg-gray-700"
                  style={{ width: `${w}%` }}
                />
              ))}
            </div>
          ) : !job ? (
            <p className="px-6 py-10 text-center text-sm text-gray-500">
              Job not found.
            </p>
          ) : (
            <div>
              {/* Key info */}
              <div className="space-y-3 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <div className="flex flex-wrap gap-2">
                  {job.location && (
                    <MetaBadge icon={<MapPin size={12} />}>
                      {job.location}
                    </MetaBadge>
                  )}
                  {job.job_type && (
                    <MetaBadge icon={<Briefcase size={12} />}>
                      {job.job_type.replace(/_/g, " ")}
                    </MetaBadge>
                  )}
                  {job.work_mode && (
                    <MetaBadge icon={<Building2 size={12} />}>
                      {job.work_mode.charAt(0).toUpperCase() +
                        job.work_mode.slice(1)}
                    </MetaBadge>
                  )}
                  {job.openings_count && (
                    <MetaBadge icon={<Users size={12} />}>
                      {job.openings_count} opening
                      {job.openings_count > 1 ? "s" : ""}
                    </MetaBadge>
                  )}
                  {job.expires_at && (
                    <MetaBadge icon={<Clock size={12} />}>
                      Apply by {formatDate(job.expires_at)}
                    </MetaBadge>
                  )}
                </div>

                {(job.salary_details?.min || job.salary_details?.max) && (
                  <p className="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                    {formatSalary(
                      job.salary_details.min,
                      job.salary_details.max,
                      job.salary_details.currency,
                      job.salary_details.salary_type,
                    )}
                  </p>
                )}

                <div className="flex flex-wrap gap-2">
                  {job.experience?.experience_level && (
                    <span className="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                      {job.experience.experience_level}
                    </span>
                  )}
                  {job.experience?.min != null &&
                    job.experience?.max != null && (
                      <span className="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                        {job.experience.min}–{job.experience.max} yrs
                      </span>
                    )}
                  {job.education && (
                    <span className="flex items-center gap-1 rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-950 dark:text-indigo-400">
                      <GraduationCap size={11} />
                      {job.education}
                    </span>
                  )}
                </div>
              </div>

              {/* Description */}
              {job.job_description && (
                <Section title="About the role">
                  <p className="whitespace-pre-line text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                    {job.job_description}
                  </p>
                </Section>
              )}

              {/* Responsibilities */}
              {job.roles_responsibility?.length > 0 && (
                <Section title="Responsibilities">
                  <ul className="space-y-2">
                    {job.roles_responsibility.map((r, i) => (
                      <li
                        key={i}
                        className="flex items-start gap-2.5 text-sm text-gray-600 dark:text-gray-300"
                      >
                        <CheckCircle2
                          size={14}
                          className="mt-0.5 shrink-0 text-indigo-500"
                        />
                        {r}
                      </li>
                    ))}
                  </ul>
                </Section>
              )}

              {/* Skills */}
              {job.skills?.length > 0 && (
                <Section title="Required skills">
                  <div className="flex flex-wrap gap-2">
                    {job.skills.map((skill) => (
                      <span
                        key={skill}
                        className="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                      >
                        {skill}
                      </span>
                    ))}
                  </div>
                </Section>
              )}
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="border-t border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
          <button
            onClick={handleApply}
            disabled={isLoading || !job}
            className="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-50 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            Apply Now
          </button>
          {!isAuthenticated && (
            <p className="mt-2 text-center text-xs text-gray-400 dark:text-gray-500">
              You&apos;ll be asked to log in before applying.
            </p>
          )}
        </div>
      </div>
    </div>
  );
}

function Section({ title, children }) {
  return (
    <div className="border-b border-gray-100 px-6 py-5 last:border-0 dark:border-gray-800">
      <h3 className="mb-3 text-sm font-semibold text-gray-900 dark:text-white">
        {title}
      </h3>
      {children}
    </div>
  );
}

function MetaBadge({ icon, children }) {
  return (
    <span className="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-xs text-gray-600 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
      {icon}
      {children}
    </span>
  );
}
