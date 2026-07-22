import { MapPin, Briefcase, Clock, Zap } from "lucide-react";

export default function CandidateCard({ candidate, onViewProfile }) {
  const experience = candidate.total_experience;
  const experienceLabel =
    experience && (experience.years || experience.months)
      ? `${experience.years ?? 0}y ${experience.months ?? 0}m exp`
      : null;

  const visibleSkills = (candidate.skills ?? []).slice(0, 5);
  const extraSkillsCount = (candidate.skills?.length ?? 0) - visibleSkills.length;

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-start gap-3">
        <div className="flex size-11 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">
          {(candidate.current_job_title ?? candidate.headline ?? "?")
            .charAt(0)
            .toUpperCase()}
        </div>
        <div className="min-w-0 flex-1">
          <h3 className="truncate font-semibold text-gray-900 dark:text-white">
            {candidate.headline ?? candidate.current_job_title ?? "Job Seeker"}
          </h3>
          {candidate.current_job_title && (
            <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">
              {candidate.current_job_title}
              {candidate.current_company ? ` @ ${candidate.current_company}` : ""}
            </p>
          )}
        </div>
      </div>

      <div className="mt-3 flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
        {candidate.location && (
          <span className="flex items-center gap-1">
            <MapPin size={14} />
            {candidate.location}
          </span>
        )}
        {experienceLabel && (
          <span className="flex items-center gap-1">
            <Briefcase size={14} />
            {experienceLabel}
          </span>
        )}
        {candidate.notice_period && (
          <span className="flex items-center gap-1">
            <Clock size={14} />
            {candidate.immediate_joiner
              ? "Immediate joiner"
              : `${candidate.notice_period} days notice`}
          </span>
        )}
      </div>

      {visibleSkills.length > 0 && (
        <div className="mt-3 flex flex-wrap gap-2">
          {visibleSkills.map((skill) => (
            <span
              key={skill}
              className="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
            >
              {skill}
            </span>
          ))}
          {extraSkillsCount > 0 && (
            <span className="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
              +{extraSkillsCount} more
            </span>
          )}
        </div>
      )}

      <div className="mt-4 flex items-center justify-between">
        {typeof candidate.profile_completeness === "number" && (
          <span className="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
            <Zap size={12} />
            {candidate.profile_completeness}% complete
          </span>
        )}
        <button
          onClick={() => onViewProfile(candidate.user_id)}
          className="ml-auto flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
        >
          View Full Profile
        </button>
      </div>
    </div>
  );
}
