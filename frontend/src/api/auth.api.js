import axiosClient from "./axios";
import { API } from "@/constants/api";

export const authApi = {
  signup: (payload) => axiosClient.post(API.AUTH.SIGNUP, payload),
  verifyOtp: (payload) => axiosClient.post(API.AUTH.VERIFY_OTP, payload),
  resendOtp: (payload) => axiosClient.post(API.AUTH.RESEND_OTP, payload),
};
