import axiosClient from "./axios";
import { API } from "@/constants/api";

export const recruiterApi = {
  dashboard: () => axiosClient.post(API.RECRUITER.DASHBOARD),
  myJobs: (params) => axiosClient.post(API.RECRUITER.MY_JOBS, params),
  myApplications: (params) => axiosClient.post(API.RECRUITER.MY_APPLICATIONS, params),
  listApplications: (params) => axiosClient.post(API.APPLICATION.LIST, params),
  updateApplicationStatus: (payload) => axiosClient.post(API.APPLICATION.UPDATE_STATUS, payload),
  viewApplication: (payload) => axiosClient.post(API.APPLICATION.VIEW, payload),
  getConversations: () => axiosClient.post(API.RECRUITER.CONVERSATIONS),
  sendMessage: (payload) => axiosClient.post(API.RECRUITER.SEND_MESSAGE, payload),
  viewSeekerProfile: (userId) => axiosClient.post(API.PROFILE.VIEW, { user_id: userId }),
};
