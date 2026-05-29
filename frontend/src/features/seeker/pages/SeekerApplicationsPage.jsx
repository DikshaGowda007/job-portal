import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import { APPLICATION_STATUS, PAGINATION_DEFAULT, PIPELINE_STEPS } from "@/utils/constants";
import { APPLICATION_STATUS_BADGE as STATUS_BADGE } from "@/utils/styles";
import { ROUTES } from "@/utils/routePaths";
import { formatDate, timeAgo } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import CompanyLogo from "@/components/common/CompanyLogo";
import ConfirmModal from "@/components/modals/ConfirmModal";
import { Clock, CheckCircle2, Circle, XCircle } from "lucide-react";

function StatusPipeline({ status }) {
  if (
  status === APPLICATION_STATUS.WITHDRAWN ||
    status === APPLICATION_STATUS.REJECTED
  )
    return null;
  const currentIdx = PIPELINE_STEPS.indexOf(status);

  return (
    <div className="flex items-center gap-0">
      {PIPELINE_STEPS.map((step, i) => {
        const done = i < currentIdx;
        const active = i === currentIdx;
        return (
          <div key={step} className="flex items-center">
            <div className="flex flex-col items-center gap-1">
              {done ? (
                <CheckCircle2
                  size={16}
                  className="text-indigo-600 dark:text-indigo-400"
                />
              ) : active ? (
                <div className="flex size-4 items-center justify-center rounded-full bg-indigo-600 dark:bg-indigo-500">
                  <div className="size-1.5 rounded-full bg-white" />
                </div>
              ) : (
                <Circle
                  size={16}
                  className="text-gray-300 dark:text-gray-600"
                />
              )}
              <span
                className={`text-[10px] ${active ? "font-semibold text-indigo-600 dark:text-indigo-400" : done ? "text-gray-500" : "text-gray-300 dark:text-gray-600"}`}
              >
                {step.charAt(0) + step.slice(1).toLowerCase()}
              </span>
            </div>
            {i < PIPELINE_STEPS.length - 1 && (
              <div
                className={`mb-4 h-0.5 w-8 ${i < currentIdx ? "bg-indigo-600 dark:bg-indigo-500" : "bg-gray-200 dark:bg-gray-700"}`}
              />
            )}
          </div>
        );
      })}
    </div>
  );
}

export default function SeekerApplicationsPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [confirmAppId, setConfirmAppId] = useState(null);
  const navigate = useNavigate();
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ["seeker-applications", page],
    queryFn: () =>
      seekerApi
        .myApplications({ page, per_page: PAGINATION_DEFAULT.PER_PAGE })
        .then((r) => r.data),
  });

  const withdrawMutation = useMutation({
    mutationFn: (id) => seekerApi.withdraw(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-applications"] });
      toast.success("Application withdrawn successfully");
      setConfirmAppId(null);
    },
    onError: (err) => {
      toast.error(err?.data?.message ?? "Failed to withdraw application");
      setConfirmAppId(null);
    },
  });

  const applications = data?.data?.applications ?? [];
  const totalPages = data?.data?.pagination?.last_page ?? 1;

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
          My Applications
        </h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Track the status of your job applications
        </p>
      </div>

      {isLoading ? (
        <Loader />
      ) : applications.length === 0 ? (
        <EmptyState
          title="No applications yet"
          description="Apply to jobs to see them here."
        />
      ) : (
        <>
          <div className="space-y-4">
            {applications.map((app) => {
              const companyName =
                app.company_name ?? app.job?.company_name ?? "";
              const jobTitle = app.job_title ?? app.job?.title ?? "Job";
              const status = app.status ?? "";
              const isTerminal =
                status === APPLICATION_STATUS.WITHDRAWN ||
                status === APPLICATION_STATUS.REJECTED;
              const canWithdraw =
                !isTerminal && status !== APPLICATION_STATUS.HIRED;

              return (
                <div
                  key={app.id}
                  onClick={() => navigate(ROUTES.SEEKER_APPLICATION_DETAIL.replace(":id", app.id))}
                  className="cursor-pointer rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
                >
                  {/* Top: logo + job info + status badge */}
                  <div className="flex items-start gap-3">
                    <CompanyLogo name={companyName} />

                    <div className="min-w-0 flex-1">
                      <h3 className="truncate font-semibold text-gray-900 dark:text-white">
                        {jobTitle}
                      </h3>
                      <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
                        {companyName || "—"}
                      </p>
                      <p className="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                        <Clock size={11} />
                        Applied {formatDate(app.created_at)} &middot;{" "}
                        {timeAgo(app.created_at)}
                      </p>
                    </div>

                    <div className="flex shrink-0 flex-col items-end gap-2">
                      <span
                        className={`rounded-full px-3 py-1 text-xs font-medium capitalize ${STATUS_BADGE[status] ?? "bg-gray-100 text-gray-600"}`}
                      >
                        {status === APPLICATION_STATUS.REJECTED ? (
                          <span className="flex items-center gap-1">
                            <XCircle size={11} /> Rejected
                          </span>
                        ) : (
                          status.charAt(0) + status.slice(1).toLowerCase()
                        )}
                      </span>
                      {canWithdraw && (
                        <button
                          onClick={(e) => {
                            e.stopPropagation();
                            setConfirmAppId(app.id);
                          }}
                          disabled={withdrawMutation.isPending}
                          className="text-xs text-red-500 hover:text-red-700 disabled:opacity-60 dark:text-red-400"
                        >
                          Withdraw
                        </button>
                      )}
                    </div>
                  </div>

                  {/* Status pipeline */}
                  {!isTerminal && (
                    <div className="mt-5 border-t border-gray-100 pt-4 dark:border-gray-800">
                      <StatusPipeline status={status} />
                    </div>
                  )}
                </div>
              );
            })}
          </div>

          <Pagination
            page={page}
            totalPages={totalPages}
            onPageChange={setPage}
          />
        </>
      )}

      <ConfirmModal
        open={!!confirmAppId}
        onClose={() => setConfirmAppId(null)}
        onConfirm={() => withdrawMutation.mutate(confirmAppId)}
        title="Withdraw Application"
        description="Are you sure you want to withdraw this application? This action cannot be undone."
        confirmText="Withdraw"
        loading={withdrawMutation.isPending}
        danger
      />
    </div>
  );
}
