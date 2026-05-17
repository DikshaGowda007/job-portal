import axiosClient from "./axios";
import { API } from "@/constants/api";

export const seekerApi = {
  applyJob: (payload) => axiosClient.post(API.APPLICATION.APPLY, payload),
  myApplications: (params) => axiosClient.post(API.APPLICATION.MY_APPLICATIONS, params),
  getApplication: (payload) => axiosClient.post(API.APPLICATION.GET, payload),
};
