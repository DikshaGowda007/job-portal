import axiosClient from "./axios";
import { API } from "@/constants/api";

export const seekerApi = {
  applyJob: (payload) => axiosClient.post(API.APPLICATION.APPLY, payload),
  myApplications: (params) => axiosClient.post(API.APPLICATION.MY_APPLICATIONS, params),
  getApplication: (payload) => axiosClient.post(API.APPLICATION.GET, payload),
  withdraw: (applicationId) => axiosClient.post(API.APPLICATION.WITHDRAW, { application_id: applicationId }),

  savedJobs: (params) => axiosClient.post(API.SAVED_JOB.LIST, params),
  saveJob: (payload) => axiosClient.post(API.SAVED_JOB.ADD, payload),
  unsaveJob: (payload) => axiosClient.post(API.SAVED_JOB.DELETE, payload),
};
