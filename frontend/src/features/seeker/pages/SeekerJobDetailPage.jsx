import { useParams, useNavigate } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import { jobsApi } from "@/api/jobs.api";
import { ROUTES } from "@/utils/routePaths";
import { formatSalary, formatDate } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import JobSection from "@/features/home/components/JobSection";
import JobDetailRow from "@/features/home/components/JobDetailRow";
import ApplyCard from "@/features/home/components/ApplyCard";
import {
  ArrowLeft,
  MapPin,
  Briefcase,
  Building2,
  Clock,
  Users,
  GraduationCap,
  CheckCircle2,
} from "lucide-react";

export default function SeekerJobDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();

  const { data: job, isLoading } = useQuery({
    queryKey: ["seeker-job", id],
    queryFn: () => jobsApi.get({ id: Number(id) }).then((r) => r.data?.data),
  });

  if (isLoading) return <Loader />;

  if (!job) {
    return (
      <div className="py-20 text-center">
        <p className="text-gray-500 dark:text-gray-400">Job not found.</p>
        <button
          onClick={() => navigate(ROUTES.SEEKER_JOBS)}
          className="mt-4 inline-block text-sm text-indigo-600 hover:underline dark:text-indigo-400"
        >
          Back to jobs
        </button>
      </div>
    );
  }

  return (
    <div>
      <button
        onClick={() => navigate(ROUTES.SEEKER_JOBS)}
        className="mb-6 flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
      >
        <ArrowLeft size={15} /> Back to jobs
      </button>

      <div className="grid gap-6 lg:grid-cols-3">
        {/* Main content */}
        <div className="space-y-6 lg:col-span-2">
          <div className="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">{job.title}</h1>
            <p className="mt-1 text-gray-600 dark:text-gray-400">{job.company_name}</p>

            <div className="mt-4 flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
              {job.location && (
                <span className="flex items-center gap-1.5"><MapPin size={14} />{job.location}</span>
              )}
              {job.job_type && (
                <span className="flex items-center gap-1.5"><Briefcase size={14} />{job.job_type.replace(/_/g, " ")}</span>
              )}
              {job.work_mode && (
                <span className="flex items-center gap-1.5">
                  <Building2 size={14} />{job.work_mode.charAt(0).toUpperCase() + job.work_mode.slice(1)}
                </span>
              )}
              {job.openings_count && (
                <span className="flex items-center gap-1.5">
                  <Users size={14} />{job.openings_count} opening{job.openings_count > 1 ? "s" : ""}
                </span>
              )}
              {job.expires_at && (
                <span className="flex items-center gap-1.5"><Clock size={14} />Apply by {formatDate(job.expires_at)}</span>
              )}
            </div>

            {(job.salary_details?.min || job.salary_details?.max) && (
              <p className="mt-3 text-base font-semibold text-indigo-600 dark:text-indigo-400">
                {formatSalary(
                  job.salary_details.min,
                  job.salary_details.max,
                  job.salary_details.currency,
                  job.salary_details.salary_type,
                )}
              </p>
            )}
          </div>

          {job.job_description && (
            <JobSection title="Job Description">
              <p className="whitespace-pre-line text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                {job.job_description}
              </p>
            </JobSection>
          )}

          {job.roles_responsibility?.length > 0 && (
            <JobSection title="Roles & Responsibilities">
              <ul className="space-y-1.5">
                {job.roles_responsibility.map((r, i) => (
                  <li key={i} className="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <CheckCircle2 size={14} className="mt-0.5 shrink-0 text-indigo-500" />
                    {r}
                  </li>
                ))}
              </ul>
            </JobSection>
          )}

          {job.skills?.length > 0 && (
            <JobSection title="Required Skills">
              <div className="flex flex-wrap gap-2">
                {job.skills.map((skill) => (
                  <span
                    key={skill}
                    className="rounded-full border border-gray-200 bg-gray-50 px-3 py-1 text-sm text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300"
                  >
                    {skill}
                  </span>
                ))}
              </div>
            </JobSection>
          )}
        </div>

        {/* Sidebar */}
        <div className="space-y-4">
          <ApplyCard jobId={id} />

          <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h3 className="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Job Details</h3>
            <dl className="space-y-2 text-sm">
              {job.experience?.experience_level && (
                <JobDetailRow label="Experience">{job.experience.experience_level}</JobDetailRow>
              )}
              {(job.experience?.min != null || job.experience?.max != null) && (
                <JobDetailRow label="Years">
                  {job.experience.min ?? 0}–{job.experience.max ?? "∞"} yrs
                </JobDetailRow>
              )}
              {job.education && (
                <JobDetailRow label="Education">
                  <span className="flex items-center gap-1"><GraduationCap size={13} />{job.education}</span>
                </JobDetailRow>
              )}
              {job.created_at && (
                <JobDetailRow label="Posted">{formatDate(job.created_at)}</JobDetailRow>
              )}
            </dl>
          </div>
        </div>
      </div>
    </div>
  );
}
