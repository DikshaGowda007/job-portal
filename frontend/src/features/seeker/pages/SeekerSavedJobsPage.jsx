import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import { jobsApi } from "@/api/jobs.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import { WORK_MODE_STYLE, JOB_TYPE_STYLE } from "@/utils/styles";
import { formatSalary, timeAgo } from "@/utils/formatters";
import { ROUTES } from "@/utils/routePaths";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import CompanyLogo from "@/components/common/CompanyLogo";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  MapPin,
  Clock,
  BookmarkX,
  Briefcase,
  Building2,
  ExternalLink,
} from "lucide-react";

export default function SeekerSavedJobsPage() {
  const navigate = useNavigate();
  const [page] = useState(PAGINATION_DEFAULT.PAGE);
  const [selectedJob, setSelectedJob] = useState(null);
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ["seeker-saved-jobs", page],
    queryFn: () =>
      seekerApi
        .savedJobs({ page, per_page: PAGINATION_DEFAULT.PER_PAGE })
        .then((r) => r.data?.data),
  });

  const { data: jobDetail, isLoading: jobDetailLoading } = useQuery({
    queryKey: ["job-detail-modal", selectedJob?.job_id],
    queryFn: () =>
      jobsApi.get({ id: selectedJob.job_id }).then((r) => r.data?.data),
    enabled: !!selectedJob,
  });

  const unsaveMutation = useMutation({
    mutationFn: (job_post_id) => seekerApi.unsaveJob({ job_post_id }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs"] });
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs-check"] });
      setSelectedJob(null);
      toast.success("Job removed from saved");
    },
    onError: () => toast.error("Failed to remove saved job"),
  });

  const jobs = Array.isArray(data) ? data : [];

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
          Saved Jobs
        </h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Jobs you've bookmarked for later
        </p>
      </div>

      {isLoading ? (
        <Loader />
      ) : jobs.length === 0 ? (
        <EmptyState
          title="No saved jobs"
          description="Bookmark jobs from Browse Jobs to see them here."
        />
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
          {jobs.map((job) => (
            <div
              key={job.job_id}
              onClick={() => setSelectedJob(job)}
              className="flex cursor-pointer flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
            >
              <div className="flex items-start gap-3">
                <CompanyLogo name={job.company_name} />
                <div className="min-w-0 flex-1">
                  <h3 className="truncate font-semibold text-gray-900 dark:text-white">
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
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    unsaveMutation.mutate(job.job_id);
                  }}
                  disabled={unsaveMutation.isPending}
                  title="Remove from saved"
                  className="shrink-0 rounded-lg p-1.5 text-indigo-500 transition hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-950/30"
                >
                  <BookmarkX size={18} />
                </button>
              </div>

              <div className="flex flex-wrap gap-2">
                {job.work_mode && (
                  <span
                    className={`rounded-full px-2.5 py-1 text-xs font-medium capitalize ${WORK_MODE_STYLE[job.work_mode] ?? "bg-gray-100 text-gray-600"}`}
                  >
                    {job.work_mode}
                  </span>
                )}
                {job.job_type && (
                  <span
                    className={`rounded-full px-2.5 py-1 text-xs font-medium ${JOB_TYPE_STYLE[job.job_type] ?? "bg-gray-100 text-gray-600"}`}
                  >
                    {job.job_type.replace(/_/g, " ")}
                  </span>
                )}
                {job.experience?.experience_level && (
                  <span className="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {job.experience.experience_level}
                  </span>
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

              <div className="mt-auto flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                <span className="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                  <Clock size={12} />
                  {timeAgo(job.created_at)}
                </span>
                <button
                  onClick={(e) => {
                    e.stopPropagation();
                    navigate(
                      ROUTES.PUBLIC_JOB_DETAIL.replace(":id", job.job_id),
                    );
                  }}
                  className="rounded-lg bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                  Apply Now
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      <Dialog
        open={!!selectedJob}
        onOpenChange={(open) => !open && setSelectedJob(null)}
      >
        <DialogContent className="max-h-[85vh] max-w-2xl overflow-y-auto">
          {jobDetailLoading ? (
            <div className="py-12">
              <Loader />
            </div>
          ) : jobDetail ? (
            <>
              <DialogHeader>
                <div className="flex items-start gap-4">
                  <CompanyLogo name={jobDetail.company_name} />
                  <div className="min-w-0 flex-1">
                    <DialogTitle className="text-xl">
                      {jobDetail.title}
                    </DialogTitle>
                    <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
                      {jobDetail.company_name}
                    </p>
                    {jobDetail.location && (
                      <p className="mt-1 flex items-center gap-1 text-sm text-gray-400 dark:text-gray-500">
                        <MapPin size={13} />
                        {jobDetail.location}
                      </p>
                    )}
                  </div>
                </div>
              </DialogHeader>

              <div className="flex flex-wrap gap-2">
                {jobDetail.work_mode && (
                  <span
                    className={`rounded-full px-2.5 py-1 text-xs font-medium capitalize ${WORK_MODE_STYLE[jobDetail.work_mode] ?? "bg-gray-100 text-gray-600"}`}
                  >
                    <Building2 size={11} className="mr-1 inline" />
                    {jobDetail.work_mode}
                  </span>
                )}
                {jobDetail.job_type && (
                  <span
                    className={`rounded-full px-2.5 py-1 text-xs font-medium ${JOB_TYPE_STYLE[jobDetail.job_type] ?? "bg-gray-100 text-gray-600"}`}
                  >
                    <Briefcase size={11} className="mr-1 inline" />
                    {jobDetail.job_type.replace(/_/g, " ")}
                  </span>
                )}
                {jobDetail.experience_level && (
                  <span className="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                    {jobDetail.experience_level}
                  </span>
                )}
              </div>

              {(jobDetail.salary_min || jobDetail.salary_max) && (
                <p className="font-semibold text-indigo-600 dark:text-indigo-400">
                  {formatSalary(
                    jobDetail.salary_min,
                    jobDetail.salary_max,
                    jobDetail.salary_currency,
                    jobDetail.salary_type,
                  )}
                </p>
              )}

              {jobDetail.description && (
                <div>
                  <h4 className="mb-1.5 font-semibold text-gray-900 dark:text-white">
                    About the Role
                  </h4>
                  <p className="whitespace-pre-line text-sm text-gray-600 dark:text-gray-400">
                    {jobDetail.description}
                  </p>
                </div>
              )}

              {jobDetail.requirements && (
                <div>
                  <h4 className="mb-1.5 font-semibold text-gray-900 dark:text-white">
                    Requirements
                  </h4>
                  <p className="whitespace-pre-line text-sm text-gray-600 dark:text-gray-400">
                    {jobDetail.requirements}
                  </p>
                </div>
              )}

              <div className="flex gap-3 border-t border-gray-100 pt-4 dark:border-gray-800">
                <button
                  onClick={() => {
                    setSelectedJob(null);
                    navigate(
                      ROUTES.PUBLIC_JOB_DETAIL.replace(
                        ":id",
                        selectedJob.job_id,
                      ),
                    );
                  }}
                  className="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                  <ExternalLink size={14} />
                  Apply Now
                </button>
                <button
                  onClick={() => unsaveMutation.mutate(selectedJob.job_id)}
                  disabled={unsaveMutation.isPending}
                  className="flex items-center gap-1.5 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-red-200 hover:text-red-500 disabled:opacity-60 dark:border-gray-700 dark:text-gray-400"
                >
                  <BookmarkX size={14} />
                  Remove
                </button>
              </div>
            </>
          ) : null}
        </DialogContent>
      </Dialog>
    </div>
  );
}
