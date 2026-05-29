import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { recruiterApi } from "@/api/recruiter.api";
import { jobsApi } from "@/api/jobs.api";
import { ROUTES } from "@/utils/routePaths";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import JobCard from "@/components/cards/JobCard";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import ConfirmModal from "@/components/modals/ConfirmModal";
import { Plus, Edit2, Trash2, Send } from "lucide-react";

export default function RecruiterJobsPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [deleteTarget, setDeleteTarget] = useState(null);
  const queryClient = useQueryClient();
  const navigate = useNavigate();

  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-jobs", page],
    queryFn: () =>
      recruiterApi
        .myJobs({ page, per_page: PAGINATION_DEFAULT.PER_PAGE })
        .then((r) => r.data?.data),
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => jobsApi.delete({ id }),
    onSuccess: () => {
      setDeleteTarget(null);
      queryClient.invalidateQueries({ queryKey: ["recruiter-jobs"] });
    },
  });

  const publishMutation = useMutation({
    mutationFn: (id) => jobsApi.publish({ id }),
    onSuccess: () =>
      queryClient.invalidateQueries({ queryKey: ["recruiter-jobs"] }),
  });

  const jobs = data?.jobs ?? [];
  const totalPages = data?.total_pages ?? 1;

  return (
    <div>
      <div className="mb-6 flex items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
            My Jobs
          </h1>
          <p className="mt-1 text-gray-500 dark:text-gray-400">
            Manage your job postings
          </p>
        </div>
        <Link
          to={ROUTES.RECRUITER_JOB_PUBLISH}
          className="flex shrink-0 items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
        >
          <Plus size={16} />
          Post a Job
        </Link>
      </div>

      {isLoading ? (
        <Loader />
      ) : jobs.length === 0 ? (
        <EmptyState
          title="No jobs yet"
          description="Post your first job to start receiving applications."
        />
      ) : (
        <>
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            {jobs.map((job) => (
              <JobCard
                key={job.job_id}
                job={job}
                actions={[
                  <button
                    key="edit"
                    onClick={() =>
                      navigate(
                        ROUTES.RECRUITER_JOB_EDIT.replace(":id", job.job_id),
                      )
                    }
                    className="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                  >
                    <Edit2 size={13} /> Edit
                  </button>,
                  <button
                    key="publish"
                    onClick={() => publishMutation.mutate(job.job_id)}
                    disabled={publishMutation.isPending}
                    className="flex items-center gap-1.5 rounded-lg border border-indigo-300 bg-indigo-50 px-3 py-1.5 text-sm text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-950 dark:text-indigo-400 dark:hover:bg-indigo-900"
                  >
                    <Send size={13} /> Publish
                  </button>,
                  <Link
                    key="apps"
                    to={`${ROUTES.RECRUITER_APPLICATIONS}?job_id=${job.job_id}`}
                    className="flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
                  >
                    Applications
                  </Link>,
                  <button
                    key="delete"
                    onClick={() => setDeleteTarget(job)}
                    className="flex items-center gap-1.5 rounded-lg border border-red-300 bg-red-50 px-3 py-1.5 text-sm text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950 dark:text-red-400"
                  >
                    <Trash2 size={13} /> Delete
                  </button>,
                ]}
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

      <ConfirmModal
        open={!!deleteTarget}
        onClose={() => setDeleteTarget(null)}
        onConfirm={() => deleteMutation.mutate(deleteTarget?.job_id)}
        title="Delete Job"
        description={`Are you sure you want to delete "${deleteTarget?.title}"? This action cannot be undone.`}
        confirmText="Delete"
        loading={deleteMutation.isPending}
        danger
      />
    </div>
  );
}
