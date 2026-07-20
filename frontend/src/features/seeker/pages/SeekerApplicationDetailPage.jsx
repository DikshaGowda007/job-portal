import { useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import { APPLICATION_STATUS } from "@/utils/constants";
import { ROUTES } from "@/utils/routePaths";
import { formatDate, timeAgo } from "@/utils/formatters";
import Loader from "@/components/common/Loader";
import CompanyLogo from "@/components/common/CompanyLogo";
import ConfirmModal from "@/components/modals/ConfirmModal";
import ApplicationTimeline from "@/components/common/ApplicationTimeline";
import {
  ArrowLeft,
  Clock,
  MapPin,
  Briefcase,
  CheckCircle2,
  Circle,
  XCircle,
  MessageSquare,
  History,
} from "lucide-react";

const PIPELINE_STEPS = ["pending", "reviewed", "shortlisted", "interview", "offered", "hired"];

const STATUS_BADGE = {
  pending:     "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400",
  reviewed:    "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  shortlisted: "bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400",
  interview:   "bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400",
  offered:     "bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400",
  hired:       "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  rejected:    "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
  withdrawn:   "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400",
};

function StatusPipeline({ status }) {
  if (
    status === APPLICATION_STATUS.WITHDRAWN.toLowerCase() ||
    status === APPLICATION_STATUS.REJECTED.toLowerCase()
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
                className={`text-[10px] capitalize ${
                  active
                    ? "font-semibold text-indigo-600 dark:text-indigo-400"
                    : done
                      ? "text-gray-500"
                      : "text-gray-300 dark:text-gray-600"
                }`}
              >
                {step}
              </span>
            </div>
            {i < PIPELINE_STEPS.length - 1 && (
              <div
                className={`mb-4 h-0.5 w-8 ${
                  i < currentIdx
                    ? "bg-indigo-600 dark:bg-indigo-500"
                    : "bg-gray-200 dark:bg-gray-700"
                }`}
              />
            )}
          </div>
        );
      })}
    </div>
  );
}

function DetailRow({ label, value }) {
  return (
    <div>
      <p className="text-xs text-gray-500 dark:text-gray-400">{label}</p>
      <p className="mt-0.5 text-sm font-medium text-gray-900 dark:text-white">
        {value ?? "—"}
      </p>
    </div>
  );
}

export default function SeekerApplicationDetailPage() {
  const { id } = useParams();
  const navigate = useNavigate();
  const queryClient = useQueryClient();

  const { data, isLoading, isError } = useQuery({
    queryKey: ["seeker-application", id],
    queryFn: () =>
      seekerApi
        .getApplication({ application_id: id })
        .then((r) => r.data?.data),
  });

  const { data: historyData } = useQuery({
    queryKey: ["application-history", id],
    queryFn: () =>
      seekerApi
        .getApplicationHistory({ application_id: id })
        .then((r) => r.data?.data),
  });

  const [showConfirm, setShowConfirm] = useState(false);

  const withdrawMutation = useMutation({
    mutationFn: () => seekerApi.withdraw(Number(id)),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-applications"] });
      queryClient.invalidateQueries({ queryKey: ["seeker-application", id] });
      toast.success("Application withdrawn successfully");
      setShowConfirm(false);
    },
    onError: (err) => {
      toast.error(err?.data?.message ?? "Failed to withdraw application");
      setShowConfirm(false);
    },
  });

  if (isLoading) return <Loader />;

  if (isError || !data)
    return (
      <div className="py-16 text-center text-gray-500 dark:text-gray-400">
        Application not found.
      </div>
    );

  const status = data.status?.toLowerCase() ?? "";
  const isTerminal =
    status === APPLICATION_STATUS.WITHDRAWN.toLowerCase() ||
    status === APPLICATION_STATUS.REJECTED.toLowerCase();
  const canWithdraw = !isTerminal && status !== APPLICATION_STATUS.HIRED.toLowerCase();
  const companyName = data.company_name ?? "";

  return (
    <div>
      {/* Back */}
      <button
        onClick={() => navigate(ROUTES.SEEKER_APPLICATIONS)}
        className="mb-5 flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
      >
        <ArrowLeft size={15} />
        Back to applications
      </button>

      {/* Header card */}
      <div className="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div className="flex items-start gap-4">
          <CompanyLogo name={companyName} size="lg" />

          <div className="min-w-0 flex-1">
            <h1 className="text-xl font-bold text-gray-900 dark:text-white">
              {data.job_title ?? "Job"}
            </h1>
            <p className="mt-0.5 text-sm text-gray-500 dark:text-gray-400">
              {companyName || "—"}
            </p>

            <div className="mt-1.5 flex flex-wrap items-center gap-3 text-xs text-gray-400 dark:text-gray-500">
              {data.location && (
                <span className="flex items-center gap-1">
                  <MapPin size={11} /> {data.location}
                </span>
              )}
              {data.work_mode && (
                <span className="flex items-center gap-1 capitalize">
                  <Briefcase size={11} /> {data.work_mode}
                </span>
              )}
              <span className="flex items-center gap-1">
                <Clock size={11} /> Applied {formatDate(data.created_at)}{" "}
                &middot; {timeAgo(data.created_at)}
              </span>
            </div>
          </div>

          <div className="flex shrink-0 flex-col items-end gap-3">
            <span
              className={`rounded-full px-3 py-1 text-xs font-medium capitalize ${
                STATUS_BADGE[status] ?? "bg-gray-100 text-gray-600"
              }`}
            >
              {status === APPLICATION_STATUS.REJECTED.toLowerCase() ? (
                <span className="flex items-center gap-1">
                  <XCircle size={11} /> Rejected
                </span>
              ) : (
                status.charAt(0).toUpperCase() + status.slice(1)
              )}
            </span>
            {canWithdraw && (
              <button
                onClick={() => setShowConfirm(true)}
                disabled={withdrawMutation.isPending}
                className="text-xs text-red-500 hover:text-red-700 disabled:opacity-60 dark:text-red-400"
              >
                Withdraw
              </button>
            )}
          </div>
        </div>

        {/* Pipeline */}
        {!isTerminal && (
          <div className="mt-6 border-t border-gray-100 pt-5 dark:border-gray-800">
            <StatusPipeline status={status} />
          </div>
        )}
      </div>

      {/* Application details */}
      {(data.expected_salary || data.notice_period || data.experience_years != null || data.cover_letter || data.resume_path) && (
      <div className="mt-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h2 className="mb-4 text-sm font-semibold text-gray-900 dark:text-white">
          Application Details
        </h2>

        {(data.expected_salary || data.notice_period || data.experience_years != null) && (
        <div className="grid grid-cols-2 gap-x-8 gap-y-4 sm:grid-cols-3">
          {data.expected_salary && (
          <DetailRow
            label="Expected Salary"
            value={`${data.expected_salary_currency ?? ""} ${data.expected_salary}`.trim()}
          />
          )}
          {data.notice_period && (
          <DetailRow
            label="Notice Period"
            value={`${data.notice_period} days`}
          />
          )}
          {data.experience_years != null && (
          <DetailRow
            label="Experience"
            value={`${data.experience_years} years`}
          />
          )}
        </div>
        )}

        {data.cover_letter && (
        <div className="mt-5">
          <p className="text-xs text-gray-500 dark:text-gray-400">Cover Letter</p>
          <p className="mt-1 whitespace-pre-wrap text-sm text-gray-700 dark:text-gray-300">
            {data.cover_letter}
          </p>
        </div>
        )}

        {data.resume_path && (
        <div className="mt-4">
          <p className="text-xs text-gray-500 dark:text-gray-400">Resume</p>
          <a
              href={`${import.meta.env.VITE_API_BASE_URL}/storage/${data.resume_path}`}
              target="_blank"
              rel="noopener noreferrer"
              className="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:underline dark:text-indigo-400"
            >
              View Resume
            </a>
        </div>
        )}
      </div>
      )}

      {/* Status timeline */}
      {historyData?.timeline?.length > 0 && (
        <div className="mt-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <h2 className="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
            <History size={15} className="text-indigo-500" />
            Status Timeline
          </h2>
          <ApplicationTimeline timeline={historyData.timeline} />
        </div>
      )}

      {/* Messages from recruiter */}
      {data.messages?.length > 0 && (
        <div className="mt-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <h2 className="mb-4 flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
            <MessageSquare size={15} className="text-indigo-500" />
            Messages from Recruiter
          </h2>
          <div className="space-y-3">
            {data.messages.map((msg) => (
              <div
                key={msg.id}
                className="rounded-xl border border-indigo-100 bg-indigo-50 p-4 dark:border-indigo-900/40 dark:bg-indigo-900/20"
              >
                <p className="text-sm text-gray-800 dark:text-gray-200">
                  {msg.message}
                </p>
                <p className="mt-2 text-xs text-gray-400 dark:text-gray-500">
                  {msg.sender && <span className="font-medium">{msg.sender} · </span>}
                  {formatDate(msg.created_at)}
                </p>
              </div>
            ))}
          </div>
        </div>
      )}

      <ConfirmModal
        open={showConfirm}
        onClose={() => setShowConfirm(false)}
        onConfirm={() => withdrawMutation.mutate()}
        title="Withdraw Application"
        description="Are you sure you want to withdraw this application? This action cannot be undone."
        confirmText="Withdraw"
        loading={withdrawMutation.isPending}
        danger
      />
    </div>
  );
}
