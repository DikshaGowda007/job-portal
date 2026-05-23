import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { APPLICATION_STATUS } from "@/utils/constants";
import { formatDate } from "@/utils/formatters";
import {
  X,
  FileText,
  DollarSign,
  Timer,
  Clock,
  MapPin,
  Briefcase,
  GraduationCap,
  Loader2,
} from "lucide-react";

const STATUS_BADGE = {
  pending:
    "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400",
  reviewed: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  shortlisted:
    "bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400",
  hired: "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  rejected: "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
  withdrawn: "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400",
};

const LOGO_COLORS = [
  "bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400",
  "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400",
  "bg-violet-100 text-violet-600 dark:bg-violet-900/50 dark:text-violet-400",
  "bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400",
  "bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400",
  "bg-sky-100 text-sky-600 dark:bg-sky-900/50 dark:text-sky-400",
];

function logoColor(name = "") {
  const code = name.charCodeAt(0);
  return LOGO_COLORS[(isNaN(code) ? 0 : code) % LOGO_COLORS.length];
}

const NEXT_ACTIONS = {
  [APPLICATION_STATUS.PENDING]: [
    {
      label: "Mark Reviewed",
      status: APPLICATION_STATUS.REVIEWED,
      cls: "border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-950/50 dark:text-blue-400",
    },
    {
      label: "Reject",
      status: APPLICATION_STATUS.REJECTED,
      cls: "border-red-300 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400",
    },
  ],
  [APPLICATION_STATUS.REVIEWED]: [
    {
      label: "Shortlist",
      status: APPLICATION_STATUS.SHORTLISTED,
      cls: "border-indigo-300 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:border-indigo-800 dark:bg-indigo-950/50 dark:text-indigo-400",
    },
    {
      label: "Reject",
      status: APPLICATION_STATUS.REJECTED,
      cls: "border-red-300 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400",
    },
  ],
  [APPLICATION_STATUS.SHORTLISTED]: [
    {
      label: "Hire",
      status: APPLICATION_STATUS.HIRED,
      cls: "border-green-300 bg-green-50 text-green-700 hover:bg-green-100 dark:border-green-900 dark:bg-green-950/50 dark:text-green-400",
    },
    {
      label: "Reject",
      status: APPLICATION_STATUS.REJECTED,
      cls: "border-red-300 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400",
    },
  ],
};

export default function ApplicationDetailDrawer({
  app,
  onClose,
  viewFn = (payload) => recruiterApi.viewApplication(payload),
  updateStatusFn = (payload) => recruiterApi.updateApplicationStatus(payload),
  invalidateKey = "recruiter-applications",
}) {
  const queryClient = useQueryClient();

  const detailQueryKey = ["application-detail", invalidateKey, app?.id];

  const { data: detail, isLoading } = useQuery({
    queryKey: detailQueryKey,
    queryFn: () => viewFn({ application_id: app.id }).then((r) => r.data?.data),
    enabled: !!app?.id,
  });

  const updateStatus = useMutation({
    mutationFn: (payload) => updateStatusFn(payload),
    onSuccess: (_, { status }) => {
      queryClient.invalidateQueries({ queryKey: [invalidateKey] });
      queryClient.invalidateQueries({ queryKey: detailQueryKey });
      toast.success(`Application marked as ${status}`);
    },
    onError: () => toast.error("Failed to update status"),
  });

  if (!app) return null;

  const merged = { ...app, ...detail };
  const applicantName =
    merged.applicant_name ?? merged.user?.name ?? "Applicant";
  const profile = merged.profile ?? detail?.profile ?? null;
  const actions = NEXT_ACTIONS[merged.status] ?? [];

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      {/* Backdrop */}
      <div
        className="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onClick={onClose}
      />

      {/* Panel */}
      <div className="relative flex h-full w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl dark:bg-gray-900">
        {/* Header */}
        <div className="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
          <div className="flex items-center gap-3">
            <div
              className={`flex size-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold ${logoColor(applicantName)}`}
            >
              {applicantName.charAt(0).toUpperCase()}
            </div>
            <div>
              <h2 className="font-semibold text-gray-900 dark:text-white">
                {applicantName}
              </h2>
              <p className="text-xs text-gray-500 dark:text-gray-400">
                {merged.job_title ?? "—"}
              </p>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <span
              className={`rounded-full px-3 py-1 text-xs font-medium capitalize ${STATUS_BADGE[merged.status] ?? "bg-gray-100 text-gray-600"}`}
            >
              {merged.status}
            </span>
            <button
              onClick={onClose}
              className="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
            >
              <X size={18} />
            </button>
          </div>
        </div>

        {isLoading ? (
          <div className="flex flex-1 items-center justify-center">
            <Loader2 size={24} className="animate-spin text-indigo-500" />
          </div>
        ) : (
          <div className="flex-1 space-y-6 p-6">
            {/* Meta row */}
            <div className="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
              <span className="flex items-center gap-1">
                <Clock size={12} /> Applied {formatDate(merged.created_at)}
              </span>
              {profile?.city && (
                <span className="flex items-center gap-1">
                  <MapPin size={12} /> {profile.city}
                </span>
              )}
              {profile?.headline && (
                <span className="flex items-center gap-1">
                  <Briefcase size={12} /> {profile.headline}
                </span>
              )}
            </div>

            {/* Application details */}
            <Section title="Application Details">
              <div className="grid grid-cols-2 gap-4">
                {merged.experience_years != null && (
                  <Detail icon={<Timer size={13} />} label="Experience">
                    {merged.experience_years} yrs
                  </Detail>
                )}
                {merged.expected_salary && (
                  <Detail
                    icon={<DollarSign size={13} />}
                    label="Expected Salary"
                  >
                    {merged.expected_salary_currency ?? ""}{" "}
                    {Number(merged.expected_salary).toLocaleString()}
                  </Detail>
                )}
                {merged.notice_period && (
                  <Detail icon={<Clock size={13} />} label="Notice Period">
                    {merged.notice_period}
                  </Detail>
                )}
                {merged.resume_path && (
                  <Detail icon={<FileText size={13} />} label="Resume">
                    <a
                      href={`${import.meta.env.VITE_API_BASE_URL}/storage/${merged.resume_path}`}
                      target="_blank"
                      rel="noreferrer"
                      className="text-indigo-600 hover:underline dark:text-indigo-400"
                    >
                      View / Download
                    </a>
                  </Detail>
                )}
              </div>

              {merged.cover_letter && (
                <div className="mt-4">
                  <p className="mb-1.5 text-xs font-medium text-gray-500 dark:text-gray-400">
                    Cover Letter
                  </p>
                  <p className="whitespace-pre-wrap text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                    {merged.cover_letter}
                  </p>
                </div>
              )}
            </Section>

            {/* Skills */}
            {profile?.skills?.length > 0 && (
              <Section title="Skills">
                <div className="flex flex-wrap gap-2">
                  {profile.skills.map((skill) => (
                    <span
                      key={skill}
                      className="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
                    >
                      {skill}
                    </span>
                  ))}
                </div>
              </Section>
            )}

            {/* Summary */}
            {profile?.summary && (
              <Section title="Professional Summary">
                <p className="text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                  {profile.summary}
                </p>
              </Section>
            )}

            {/* Experience */}
            {profile?.experiences?.length > 0 && (
              <Section title="Work Experience">
                <div className="space-y-4">
                  {profile.experiences.map((exp, i) => (
                    <div key={exp.id ?? i} className="flex gap-3">
                      <div className="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <Briefcase
                          size={14}
                          className="text-gray-500 dark:text-gray-400"
                        />
                      </div>
                      <div>
                        <p className="text-sm font-medium text-gray-900 dark:text-white">
                          {exp.job_title}
                        </p>
                        <p className="text-xs text-gray-500 dark:text-gray-400">
                          {exp.company_name} &middot; {exp.start_date} –{" "}
                          {exp.is_current ? "Present" : exp.end_date}
                        </p>
                        {exp.description && (
                          <p className="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {exp.description}
                          </p>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </Section>
            )}

            {/* Education */}
            {profile?.education?.length > 0 && (
              <Section title="Education">
                <div className="space-y-4">
                  {profile.education.map((edu, i) => (
                    <div key={edu.id ?? i} className="flex gap-3">
                      <div className="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <GraduationCap
                          size={14}
                          className="text-gray-500 dark:text-gray-400"
                        />
                      </div>
                      <div>
                        <p className="text-sm font-medium text-gray-900 dark:text-white">
                          {edu.degree}{" "}
                          {edu.field_of_study ? `– ${edu.field_of_study}` : ""}
                        </p>
                        <p className="text-xs text-gray-500 dark:text-gray-400">
                          {edu.institution} &middot; {edu.start_year} –{" "}
                          {edu.end_year}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </Section>
            )}
          </div>
        )}

        {/* Sticky footer with action buttons */}
        {actions.length > 0 && (
          <div className="sticky bottom-0 flex gap-2 border-t border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
            {actions.map(({ label, status, cls }) => (
              <button
                key={status}
                onClick={() => updateStatus.mutate({ id: app.id, status })}
                disabled={updateStatus.isPending}
                className={`rounded-xl border px-5 py-2 text-sm font-medium transition disabled:opacity-60 ${cls}`}
              >
                {label}
              </button>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

function Section({ title, children }) {
  return (
    <div>
      <h3 className="mb-3 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-gray-500">
        {title}
      </h3>
      {children}
    </div>
  );
}

function Detail({ icon, label, children }) {
  return (
    <div>
      <p className="mb-0.5 flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
        {icon} {label}
      </p>
      <p className="text-sm font-medium text-gray-900 dark:text-white">
        {children}
      </p>
    </div>
  );
}
