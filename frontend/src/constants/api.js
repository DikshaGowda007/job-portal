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
  },

  JOB: {
    LIST: `${BASE_URL}/api/v1/job/list`,
    GET: `${BASE_URL}/api/v1/job/get`,
  },

  APPLICATION: {
    LIST: `${BASE_URL}/api/v1/application/list`,
    VIEW: `${BASE_URL}/api/v1/application/view`,
  },
};