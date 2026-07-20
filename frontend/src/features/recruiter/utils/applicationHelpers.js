import { APPLICATION_STATUS } from "@/utils/constants";
import { APPLICATION_STATUS_BADGE, entityColor } from "@/utils/styles";

export const STATUS_BADGE = APPLICATION_STATUS_BADGE;

export function logoColor(name = "") {
  return entityColor(name);
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
      label: "Schedule Interview",
      status: APPLICATION_STATUS.INTERVIEW,
      cls: "border-purple-300 bg-purple-50 text-purple-700 hover:bg-purple-100 dark:border-purple-900 dark:bg-purple-950/50 dark:text-purple-400",
    },
    {
      label: "Reject",
      status: APPLICATION_STATUS.REJECTED,
      cls: "border-red-300 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400",
    },
  ],
  [APPLICATION_STATUS.INTERVIEW]: [
    {
      label: "Send Offer",
      status: APPLICATION_STATUS.OFFERED,
      cls: "border-teal-300 bg-teal-50 text-teal-700 hover:bg-teal-100 dark:border-teal-900 dark:bg-teal-950/50 dark:text-teal-400",
    },
    {
      label: "Reject",
      status: APPLICATION_STATUS.REJECTED,
      cls: "border-red-300 bg-red-50 text-red-600 hover:bg-red-100 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400",
    },
  ],
  [APPLICATION_STATUS.OFFERED]: [
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
