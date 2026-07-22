import { useQuery } from "@tanstack/react-query";
import { recruiterApi } from "@/api/recruiter.api";
import {
  X,
  MapPin,
  Briefcase,
  GraduationCap,
  Loader2,
  Link2,
  DollarSign,
  Clock,
} from "lucide-react";

export default function CandidateProfileDrawer({ userId, onClose }) {
  const { data: profile, isLoading, isError } = useQuery({
    queryKey: ["candidate-profile", userId],
    queryFn: () =>
      recruiterApi.viewSeekerProfile(userId).then((r) => r.data?.data),
    enabled: !!userId,
  });

  if (!userId) return null;

  return (
    <div className="fixed inset-0 z-50 flex justify-end">
      <div
        className="absolute inset-0 bg-black/40 backdrop-blur-sm"
        onClick={onClose}
      />

      <div className="relative flex h-full w-full max-w-xl flex-col overflow-y-auto bg-white shadow-2xl dark:bg-gray-900">
        <div className="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-800 dark:bg-gray-900">
          <div>
            <h2 className="font-semibold text-gray-900 dark:text-white">
              {profile?.headline ?? profile?.current_job_title ?? "Candidate Profile"}
            </h2>
            {profile?.current_job_title && (
              <p className="text-xs text-gray-500 dark:text-gray-400">
                {profile.current_job_title}
                {profile.current_company ? ` @ ${profile.current_company}` : ""}
              </p>
            )}
          </div>
          <button
            onClick={onClose}
            className="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
          >
            <X size={18} />
          </button>
        </div>

        {isLoading ? (
          <div className="flex flex-1 items-center justify-center">
            <Loader2 size={24} className="animate-spin text-indigo-500" />
          </div>
        ) : isError || !profile ? (
          <div className="flex flex-1 items-center justify-center px-6 text-center text-sm text-gray-500 dark:text-gray-400">
            This profile isn't available for viewing.
          </div>
        ) : (
          <div className="flex-1 space-y-6 p-6">
            <div className="flex flex-wrap gap-4 text-xs text-gray-500 dark:text-gray-400">
              {profile.city && (
                <span className="flex items-center gap-1">
                  <MapPin size={12} /> {profile.city}
                </span>
              )}
              {(profile.total_experience_years || profile.total_experience_months) && (
                <span className="flex items-center gap-1">
                  <Briefcase size={12} />
                  {profile.total_experience_years ?? 0}y {profile.total_experience_months ?? 0}m
                </span>
              )}
              {profile.notice_period && (
                <span className="flex items-center gap-1">
                  <Clock size={12} />
                  {profile.immediate_joiner
                    ? "Immediate joiner"
                    : `${profile.notice_period} days notice`}
                </span>
              )}
              {profile.expected_salary && (
                <span className="flex items-center gap-1">
                  <DollarSign size={12} />
                  {profile.expected_salary_currency ?? ""}{" "}
                  {Number(profile.expected_salary).toLocaleString()}
                </span>
              )}
            </div>

            {(profile.linkedin_url || profile.github_url || profile.portfolio_url) && (
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

            {profile.skills?.length > 0 && (
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

            {profile.summary && (
              <Section title="Professional Summary">
                <p className="text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                  {profile.summary}
                </p>
              </Section>
            )}

            {profile.experiences?.length > 0 && (
              <Section title="Work Experience">
                <div className="space-y-4">
                  {profile.experiences.map((exp, i) => (
                    <div key={exp.id ?? i} className="flex gap-3">
                      <div className="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <Briefcase size={14} className="text-gray-500 dark:text-gray-400" />
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

            {profile.education?.length > 0 && (
              <Section title="Education">
                <div className="space-y-4">
                  {profile.education.map((edu, i) => (
                    <div key={edu.id ?? i} className="flex gap-3">
                      <div className="mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                        <GraduationCap size={14} className="text-gray-500 dark:text-gray-400" />
                      </div>
                      <div>
                        <p className="text-sm font-medium text-gray-900 dark:text-white">
                          {edu.degree} {edu.field_of_study ? `– ${edu.field_of_study}` : ""}
                        </p>
                        <p className="text-xs text-gray-500 dark:text-gray-400">
                          {edu.institution} &middot; {edu.start_year} – {edu.end_year}
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              </Section>
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
