import axiosClient from "./axios";
import { API } from "@/constants/api";

export const authApi = {
  login: (payload) => axiosClient.post(API.AUTH.LOGIN, payload),
  signup: (payload) => axiosClient.post(API.AUTH.SIGNUP, payload),
  verifyOtp: (payload) => axiosClient.post(API.AUTH.VERIFY_OTP, payload),
  resendOtp: (payload) => axiosClient.post(API.AUTH.RESEND_OTP, payload),
  changePassword: (payload) => axiosClient.post(API.USER.CHANGE_PASSWORD, payload),
};
