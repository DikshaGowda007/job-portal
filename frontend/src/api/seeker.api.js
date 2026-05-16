import axiosClient from "./axios";
import { API } from "@/constants/api";

export const seekerApi = {
  applyJob: (payload) => axiosClient.post(API.APPLICATION.APPLY, payload),
};
