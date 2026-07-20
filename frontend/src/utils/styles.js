// Shared Tailwind style maps used across multiple components

export const ENTITY_COLORS = [
  "bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400",
  "bg-violet-100 text-violet-600 dark:bg-violet-900/50 dark:text-violet-400",
  "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400",
  "bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400",
  "bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400",
  "bg-sky-100 text-sky-600 dark:bg-sky-900/50 dark:text-sky-400",
];

export function entityColor(name = "") {
  const code = name.charCodeAt(0);
  return ENTITY_COLORS[(isNaN(code) ? 0 : code) % ENTITY_COLORS.length];
}

export const ROLE_STYLE = {
  1: "bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400",
  2: "bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400",
  3: "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  4: "bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400",
};

export const WORK_MODE_STYLE = {
  remote: "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400",
  onsite: "bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  hybrid: "bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400",
};

export const JOB_TYPE_STYLE = {
  FULL_TIME:  "bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400",
  PART_TIME:  "bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400",
  INTERNSHIP: "bg-orange-50 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400",
  REMOTE:     "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400",
};

export const JOB_STATUS_STYLE = {
  PUBLISHED: "bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400",
  OPEN:      "bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400",
  CLOSED:    "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400",
  EXPIRED:   "bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400",
  DRAFT:     "bg-yellow-50 text-yellow-700 dark:bg-yellow-900/20 dark:text-yellow-400",
};

export const APPLICATION_STATUS_BADGE = {
  PENDING:     "bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400",
  REVIEWED:    "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400",
  SHORTLISTED: "bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400",
  INTERVIEW:   "bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400",
  OFFERED:     "bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400",
  HIRED:       "bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400",
  REJECTED:    "bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400",
  WITHDRAWN:   "bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400",
};

export const APPLICATION_STATUS_DOT = {
  PENDING:     "bg-yellow-400",
  REVIEWED:    "bg-blue-400",
  SHORTLISTED: "bg-indigo-500",
  INTERVIEW:   "bg-purple-500",
  OFFERED:     "bg-teal-500",
  HIRED:       "bg-emerald-500",
  REJECTED:    "bg-red-400",
  WITHDRAWN:   "bg-gray-400",
};
