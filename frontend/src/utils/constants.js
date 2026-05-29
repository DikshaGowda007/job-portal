export const TOKEN_KEY = "jwt_token";
export const USER_KEY = "auth_user";

export const JOB_STATUS = {
  DRAFT: "draft",
  PUBLISHED: "published",
  EXPIRED: "expired",
  CLOSED: "closed",
};

export const APPLICATION_STATUS = {
  PENDING: "PENDING",
  REVIEWED: "REVIEWED",
  SHORTLISTED: "SHORTLISTED",
  REJECTED: "REJECTED",
  HIRED: "HIRED",
  WITHDRAWN: "WITHDRAWN",
};

export const PAGINATION_DEFAULT = {
  PAGE: 1,
  PER_PAGE: 10,
};

export const CURRENCIES = ["INR", "USD", "EUR", "GBP"];

export const ROLE_LABELS = {
  1: "Admin",
  2: "Sub-Admin",
  3: "Recruiter",
  4: "Job Seeker",
};

export const PIPELINE_STEPS = ["PENDING", "REVIEWED", "SHORTLISTED", "HIRED"];

// Filter value arrays
export const JOB_TYPES  = ["FULL_TIME", "PART_TIME", "REMOTE", "INTERNSHIP"];
export const WORK_MODES = ["onsite", "remote", "hybrid"];
export const EXP_LEVELS = ["FRESHER", "JUNIOR", "MID", "SENIOR", "TEAM_LEAD"];

// Select option arrays { value, label }
export const WORK_MODE_OPTIONS = [
  { value: "onsite",  label: "Onsite" },
  { value: "remote",  label: "Remote" },
  { value: "hybrid",  label: "Hybrid" },
];

export const JOB_TYPE_OPTIONS = [
  { value: "FULL_TIME",  label: "Full Time" },
  { value: "PART_TIME",  label: "Part Time" },
  { value: "REMOTE",     label: "Remote" },
  { value: "INTERNSHIP", label: "Internship" },
];

export const EXP_LEVEL_OPTIONS = [
  { value: "FRESHER",   label: "Fresher" },
  { value: "JUNIOR",    label: "Junior" },
  { value: "MID",       label: "Mid" },
  { value: "SENIOR",    label: "Senior" },
  { value: "TEAM_LEAD", label: "Team Lead" },
];

export const SALARY_TYPE_OPTIONS = [
  { value: "monthly", label: "Monthly" },
  { value: "yearly",  label: "Yearly" },
];

export const JOB_STATUS_OPTIONS = [
  { value: "OPEN",    label: "Open" },
  { value: "CLOSED",  label: "Closed" },
  { value: "EXPIRED", label: "Expired" },
];

export const CURRENCY_OPTIONS = [
  { value: "INR", label: "INR" },
  { value: "USD", label: "USD" },
];
