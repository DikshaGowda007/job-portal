import { useState, useEffect } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { formatDate } from "@/utils/formatters";
import { STATUS_BADGE, logoColor, NEXT_ACTIONS } from "@/features/recruiter/utils/applicationHelpers";
import { X, FileText, DollarSign, Timer, Clock, MapPin, Briefcase, GraduationCap, Loader2, Send, Link2 } from "lucide-react";

export default function ApplicationDetailDrawer({
  app,
  onClose,
  initialAction = null,
  viewFn = (payload) => recruiterApi.viewApplication(payload),
  updateStatusFn = (payload) => recruiterApi.updateApplicationStatus(payload),
  historyFn = (payload) => recruiterApi.getApplicationHistory(payload),
  invalidateKey = "recruiter-applications",
}) {
  const queryClient = useQueryClient();
  const [pendingAction, setPendingAction] = useState(null);
  const [message, setMessage] = useState("");

  useEffect(() => {
    if (app) {
      setPendingAction(initialAction);
      setMessage("");
    }
  }, [app, initialAction]);

  const detailQueryKey = ["application-detail", invalidateKey, app?.id];

  const { data: detail, isLoading } = useQuery({
    queryKey: detailQueryKey,
    queryFn: () => viewFn({ application_id: app.id }).then((r) => r.data?.data),
    enabled: !!app?.id,
  });

  const { data: historyData } = useQuery({
    queryKey: ["application-history", app?.id],
    queryFn: () =>
      historyFn({ application_id: app.id }).then((r) => r.data?.data),
    enabled: !!app?.id,
  });

  const { data: seekerProfile } = useQuery({
    queryKey: ["seeker-profile", app?.user_id],
    queryFn: () =>
      recruiterApi.viewSeekerProfile(app.user_id).then((r) => r.data?.data),
    enabled: !!app?.user_id,
  });

  const timeline = historyData?.timeline ?? [];

  const updateStatus = useMutation({
    mutationFn: (payload) => updateStatusFn(payload),
    onSuccess: (_, { status }) => {
      queryClient.invalidateQueries({ queryKey: [invalidateKey] });
      queryClient.invalidateQueries({ queryKey: detailQueryKey });
      toast.success(`Application marked as ${status}`);
      setPendingAction(null);
      setMessage("");
    },
    onError: () => toast.error("Failed to update status"),
  });

  const handleActionClick = (action) => {
    setPendingAction(action);
    setMessage("");
  };

  const handleConfirm = () => {
    updateStatus.mutate({
      application_id: app.id,
      status: pendingAction.status,
      ...(message.trim() && { recruiter_notes: message.trim() }),
    });
  };

  if (!app) return null;

  const merged = { ...app, ...detail };
  const applicantName =
    merged.applicant_name ?? merged.user?.name ?? "Applicant";
  const profile = seekerProfile ?? merged.profile ?? detail?.profile ?? null;
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
              className={`rounded-full px-3 py-1 text-xs font-medium ${STATUS_BADGE[merged.status] ?? "bg-gray-100 text-gray-600"}`}
            >
              {merged.status ? merged.status.charAt(0) + merged.status.slice(1).toLowerCase() : "—"}
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
              <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
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

            {/* Links */}
            {(profile?.linkedin_url || profile?.github_url || profile?.portfolio_url) && (
              <Section title="Links">
                <div className="flex flex-wrap gap-3">
                  {profile.linkedin_url && (
                    <a href={profile.linkedin_url} target="_blank" rel="noreferrer"
                      className="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                      <Link2 size={12} /> LinkedIn
                    </a>
                  )}
                  {profile.github_url && (
                    <a href={profile.github_url} target="_blank" rel="noreferrer"
                      className="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                      <Link2 size={12} /> GitHub
                    </a>
                  )}
                  {profile.portfolio_url && (
                    <a href={profile.portfolio_url} target="_blank" rel="noreferrer"
                      className="flex items-center gap-1.5 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                      <Link2 size={12} /> Portfolio
                    </a>
                  )}
                </div>
              </Section>
            )}

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

            {/* Status history */}
            {timeline.length > 0 && (
              <Section title="Status History">
                <div className="space-y-3">
                  {timeline.map((entry) => {
                    const isSubmission = !entry.previous_status;
                    return (
                    <div key={entry.id} className="flex gap-3">
                      <div className={`mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg ${isSubmission ? "bg-indigo-50 dark:bg-indigo-900/30" : "bg-gray-100 dark:bg-gray-800"}`}>
                        {isSubmission ? (
                          <FileText size={13} className="text-indigo-500 dark:text-indigo-400" />
                        ) : (
                          <Clock size={13} className="text-gray-500 dark:text-gray-400" />
                        )}
                      </div>
                      <div className="flex-1">
                        <p className="text-sm text-gray-900 dark:text-white">
                          {isSubmission ? (
                            <span className="font-medium">Application submitted</span>
                          ) : (
                            <>
                              <span>{entry.previous_status.charAt(0) + entry.previous_status.slice(1).toLowerCase()}</span>
                              <span className="mx-1.5 text-gray-400">→</span>
                              <span className="font-medium">{entry.new_status.charAt(0) + entry.new_status.slice(1).toLowerCase()}</span>
                            </>
                          )}
                        </p>
                        <div className="mt-0.5 flex flex-wrap gap-2 text-xs text-gray-400 dark:text-gray-500">
                          <span className="flex items-center gap-1">
                            <Clock size={10} /> {formatDate(entry.created_at)}
                          </span>
                          {entry.changed_by?.name && (
                            <span>by {entry.changed_by.name}</span>
                          )}
                          {entry.notes && (
                            <span className="italic">{entry.notes}</span>
                          )}
                        </div>
                      </div>
                    </div>
                  );
                  })}
                </div>
              </Section>
            )}
          </div>
        )}

        {/* Sticky footer */}
        {actions.length > 0 && (
          <div className="sticky bottom-0 border-t border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            {pendingAction ? (
              <div className="space-y-3 px-6 py-4">
                <p className="text-xs font-medium text-gray-500 dark:text-gray-400">
                  Message to candidate{" "}
                  <span className="font-normal">(optional)</span>
                </p>
                <textarea
                  value={message}
                  onChange={(e) => setMessage(e.target.value)}
                  placeholder={`e.g. Please come to 3rd Floor, Tower A on Monday at 10am for the ${pendingAction.label.toLowerCase()} round.`}
                  rows={3}
                  className="w-full resize-none rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-800 outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
                />
                <div className="flex gap-2">
                  <button
                    onClick={handleConfirm}
                    disabled={updateStatus.isPending}
                    className="flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                  >
                    <Send size={13} />
                    Confirm &amp; {pendingAction.label}
                  </button>
                  <button
                    onClick={() => setPendingAction(null)}
                    disabled={updateStatus.isPending}
                    className="rounded-xl border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
                  >
                    Cancel
                  </button>
                </div>
              </div>
            ) : (
              <div className="flex gap-2 px-6 py-4">
                {actions.map(({ label, status, cls }) => (
                  <button
                    key={status}
                    onClick={() => handleActionClick({ label, status })}
                    className={`rounded-xl border px-5 py-2 text-sm font-medium transition ${cls}`}
                  >
                    {label}
                  </button>
                ))}
              </div>
            )}
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
