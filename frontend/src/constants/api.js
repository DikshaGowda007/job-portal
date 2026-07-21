const BASE_URL = import.meta.env.VITE_API_BASE_URL;

export const API = {

  AUTH: {
    LOGIN: `${BASE_URL}/api/v1/auth/login`,
    SIGNUP: `${BASE_URL}/api/v1/auth/signup`,
    VERIFY_OTP: `${BASE_URL}/api/v1/auth/verifyOtp`,
    RESEND_OTP: `${BASE_URL}/api/v1/auth/resend-otp`,
    REFRESH: `${BASE_URL}/api/v1/auth/refresh`,
  },

  USER: {
    LOGOUT: `${BASE_URL}/api/v1/auth/logout`,
    ME: `${BASE_URL}/api/v1/user/me`,
    UPDATE_PROFILE: `${BASE_URL}/api/v1/user/update-profile`,
    CHANGE_PASSWORD: `${BASE_URL}/api/v1/user/change-password`,
    FORGOT_PASSWORD: `${BASE_URL}/api/v1/auth/forgot-password`,
    RESET_PASSWORD: `${BASE_URL}/api/v1/auth/reset-password`,
  },

  JOB: {
    LIST: `${BASE_URL}/api/v1/job/list`,
    GET: `${BASE_URL}/api/v1/job/get`,
    ADD: `${BASE_URL}/api/v1/job/add`,
    EDIT: `${BASE_URL}/api/v1/job/edit`,
    DELETE: `${BASE_URL}/api/v1/job/delete`,
    PUBLISH: `${BASE_URL}/api/v1/job/publish`,
  },

  APPLICATION: {
    LIST: `${BASE_URL}/api/v1/application/list`,
    APPLY: `${BASE_URL}/api/v1/application/apply`,
    VIEW: `${BASE_URL}/api/v1/application/view`,
    HISTORY: `${BASE_URL}/api/v1/application/history`,
    UPDATE_STATUS: `${BASE_URL}/api/v1/application/update-status`,
    MY_APPLICATIONS: `${BASE_URL}/api/v1/application/my-applications`,
    GET: `${BASE_URL}/api/v1/application/get`,
    WITHDRAW: `${BASE_URL}/api/v1/application/withdraw`,
    CONVERSATIONS: `${BASE_URL}/api/v1/application/conversations`,
    SEND_MESSAGE: `${BASE_URL}/api/v1/application/send-message`,
    MARK_READ: `${BASE_URL}/api/v1/application/mark-read`,
    TYPING: `${BASE_URL}/api/v1/application/typing`,
  },

  CATEGORY: {
    LIST: `${BASE_URL}/api/v1/category/list`,
    GET: `${BASE_URL}/api/v1/category/get`,
    ADD: `${BASE_URL}/api/v1/category/add`,
    EDIT: `${BASE_URL}/api/v1/category/edit`,
    DELETE: `${BASE_URL}/api/v1/category/delete`,
  },

  RECRUITER: {
    DASHBOARD: `${BASE_URL}/api/v1/recruiter/dashboard`,
    MY_JOBS: `${BASE_URL}/api/v1/recruiter/my-jobs`,
    MY_APPLICATIONS: `${BASE_URL}/api/v1/recruiter/my-applications`,
    CONVERSATIONS: `${BASE_URL}/api/v1/recruiter/conversations`,
    SEND_MESSAGE: `${BASE_URL}/api/v1/recruiter/send-message`,
    MARK_READ: `${BASE_URL}/api/v1/recruiter/mark-read`,
    TYPING: `${BASE_URL}/api/v1/recruiter/typing`,
    COMPANY_PROFILE_GET: `${BASE_URL}/api/v1/recruiter/company-profile/get`,
    COMPANY_PROFILE_UPDATE: `${BASE_URL}/api/v1/recruiter/company-profile/update`,
  },

  SAVED_JOB: {
    ADD: `${BASE_URL}/api/v1/saved-job/add`,
    LIST: `${BASE_URL}/api/v1/saved-job/list`,
    DELETE: `${BASE_URL}/api/v1/saved-job/delete`,
  },

  JOB_ALERT: {
    ADD: `${BASE_URL}/api/v1/job-alert/add`,
    EDIT: `${BASE_URL}/api/v1/job-alert/edit`,
    LIST: `${BASE_URL}/api/v1/job-alert/list`,
    DELETE: `${BASE_URL}/api/v1/job-alert/delete`,
  },

  PROFILE: {
    GET: `${BASE_URL}/api/v1/profile/get`,
    UPDATE: `${BASE_URL}/api/v1/profile/update`,
    UPLOAD_RESUME: `${BASE_URL}/api/v1/profile/upload-resume`,

    ADD_EXPERIENCE: `${BASE_URL}/api/v1/profile/experience/add`,
    UPDATE_EXPERIENCE: `${BASE_URL}/api/v1/profile/experience/update`,
    DELETE_EXPERIENCE: `${BASE_URL}/api/v1/profile/experience/delete`,

    ADD_EDUCATION: `${BASE_URL}/api/v1/profile/education/add`,
    UPDATE_EDUCATION: `${BASE_URL}/api/v1/profile/education/update`,
    DELETE_EDUCATION: `${BASE_URL}/api/v1/profile/education/delete`,

    VIEW: `${BASE_URL}/api/v1/profile/view`,
  },

  NOTIFICATION: {
    LIST: `${BASE_URL}/api/v1/notification/list`,
    MARK_READ: `${BASE_URL}/api/v1/notification/mark-read`,
  },

  ACCESS_RIGHTS: {
    GET: `${BASE_URL}/api/v1/user/access-rights/get`,
    EDIT: `${BASE_URL}/api/v1/user/access-rights/edit`,
  },

  ADMIN: {
    DASHBOARD: `${BASE_URL}/api/v1/admin/dashboard`,

    USERS_LIST: `${BASE_URL}/api/v1/admin/users/list`,
    USERS_VIEW: `${BASE_URL}/api/v1/admin/users/view`,
    TOGGLE_USER_STATUS: `${BASE_URL}/api/v1/admin/users/toggle-status`,

    CREATE_SUB_ADMIN: `${BASE_URL}/api/v1/admin/sub-admin/create`,
    LIST_SUB_ADMINS: `${BASE_URL}/api/v1/admin/sub-admin/list`,
  },
};