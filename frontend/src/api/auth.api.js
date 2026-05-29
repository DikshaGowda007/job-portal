import axiosClient from "./axios";
import { API } from "@/constants/api";

export const authApi = {
  login: (payload) => axiosClient.post(API.AUTH.LOGIN, payload),
  signup: (payload) => axiosClient.post(API.AUTH.SIGNUP, payload),
  verifyOtp: (payload) => axiosClient.post(API.AUTH.VERIFY_OTP, payload),
  resendOtp: (payload) => axiosClient.post(API.AUTH.RESEND_OTP, payload),
  refresh: () => axiosClient.post(API.AUTH.REFRESH),
  logout: () => axiosClient.post(API.USER.LOGOUT),
  me: () => axiosClient.post(API.USER.ME),
  updateProfile: (payload) => axiosClient.post(API.USER.UPDATE_PROFILE, payload),
  forgotPassword: (payload) => axiosClient.post(API.USER.FORGOT_PASSWORD, payload),
  resetPassword: (payload) => axiosClient.post(API.USER.RESET_PASSWORD, payload),
  changePassword: (payload) => axiosClient.post(API.USER.CHANGE_PASSWORD, payload),
};
