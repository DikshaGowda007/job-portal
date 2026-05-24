import { APPLICATION_STATUS } from "@/utils/constants";

export const STATUS_BADGE = {
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

export function logoColor(name = "") {
  const code = name.charCodeAt(0);
  return LOGO_COLORS[(isNaN(code) ? 0 : code) % LOGO_COLORS.length];
}

export const NEXT_ACTIONS = {
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
