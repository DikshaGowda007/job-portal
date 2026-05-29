const BASE_URL = import.meta.env.VITE_API_BASE_URL;

export const API = {

  AUTH: {
    LOGIN: `${BASE_URL}/api/v1/auth/login`,
    SIGNUP: `${BASE_URL}/api/v1/auth/signup`,
    VERIFY_OTP: `${BASE_URL}/api/v1/auth/verifyOtp`,
    RESEND_OTP: `${BASE_URL}/api/v1/auth/resend-otp`,
  },

  USER: {
    LOGOUT: `${BASE_URL}/api/v1/auth/logout`,
    CHANGE_PASSWORD: `${BASE_URL}/api/v1/user/change-password`,
  },

  JOB: {
    LIST: `${BASE_URL}/api/v1/job/list`,
    GET: `${BASE_URL}/api/v1/job/get`,
  },

  APPLICATION: {
    LIST: `${BASE_URL}/api/v1/application/list`,
    VIEW: `${BASE_URL}/api/v1/application/view`,
    WITHDRAW: `${BASE_URL}/api/v1/application/withdraw`,
    CONVERSATIONS: `${BASE_URL}/api/v1/application/conversations`,
    SEND_MESSAGE: `${BASE_URL}/api/v1/application/send-message`,
  },


  RECRUITER: {
    DASHBOARD: `${BASE_URL}/api/v1/recruiter/dashboard`,
    MY_JOBS: `${BASE_URL}/api/v1/recruiter/my-jobs`,
    MY_APPLICATIONS: `${BASE_URL}/api/v1/recruiter/my-applications`,
    CONVERSATIONS: `${BASE_URL}/api/v1/recruiter/conversations`,
    SEND_MESSAGE: `${BASE_URL}/api/v1/recruiter/send-message`,
  },

  SAVED_JOB: {
    ADD: `${BASE_URL}/api/v1/saved-job/add`,
    LIST: `${BASE_URL}/api/v1/saved-job/list`,
    DELETE: `${BASE_URL}/api/v1/saved-job/delete`,
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
};