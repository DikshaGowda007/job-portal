const BASE_URL = import.meta.env.VITE_API_BASE_URL;

export const API = {

  AUTH: {
    SIGNUP: `${BASE_URL}/api/v1/auth/signup`,
    VERIFY_OTP: `${BASE_URL}/api/v1/auth/verifyOtp`,
    RESEND_OTP: `${BASE_URL}/api/v1/auth/resend-otp`,
  },

  JOB: {
    LIST: `${BASE_URL}/api/v1/job/list`,
  },
};
