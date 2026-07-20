import axiosClient from "./axios";
import { API } from "@/constants/api";

export const seekerApi = {
  applyJob: (payload) => axiosClient.post(API.APPLICATION.APPLY, payload),
  myApplications: (params) => axiosClient.post(API.APPLICATION.MY_APPLICATIONS, params),
  getApplication: (payload) => axiosClient.post(API.APPLICATION.GET, payload),
  withdraw: (applicationId) => axiosClient.post(API.APPLICATION.WITHDRAW, { application_id: applicationId }),

  getProfile: () => axiosClient.post(API.PROFILE.GET),
  updateProfile: (payload) => axiosClient.post(API.PROFILE.UPDATE, payload),
  uploadResume: (formData) =>
    axiosClient.post(API.PROFILE.UPLOAD_RESUME, formData, {
      headers: { "Content-Type": "multipart/form-data" },
    }),

  addExperience: (payload) => axiosClient.post(API.PROFILE.ADD_EXPERIENCE, payload),
  updateExperience: (payload) => axiosClient.post(API.PROFILE.UPDATE_EXPERIENCE, payload),
  deleteExperience: (payload) => axiosClient.post(API.PROFILE.DELETE_EXPERIENCE, payload),

  addEducation: (payload) => axiosClient.post(API.PROFILE.ADD_EDUCATION, payload),
  updateEducation: (payload) => axiosClient.post(API.PROFILE.UPDATE_EDUCATION, payload),
  deleteEducation: (payload) => axiosClient.post(API.PROFILE.DELETE_EDUCATION, payload),

  getApplicationHistory: (payload) => axiosClient.post(API.APPLICATION.HISTORY, payload),
  getConversations: () => axiosClient.post(API.APPLICATION.CONVERSATIONS),
  sendMessage: (payload) => axiosClient.post(API.APPLICATION.SEND_MESSAGE, payload),
  markRead: (payload) => axiosClient.post(API.APPLICATION.MARK_READ, payload),
  sendTyping: (payload) => axiosClient.post(API.APPLICATION.TYPING, payload),
  savedJobs: (params) => axiosClient.post(API.SAVED_JOB.LIST, params),
  saveJob: (payload) => axiosClient.post(API.SAVED_JOB.ADD, payload),
  unsaveJob: (payload) => axiosClient.post(API.SAVED_JOB.DELETE, payload),

  jobAlerts: () => axiosClient.post(API.JOB_ALERT.LIST),
  createJobAlert: (payload) => axiosClient.post(API.JOB_ALERT.ADD, payload),
  updateJobAlert: (payload) => axiosClient.post(API.JOB_ALERT.EDIT, payload),
  deleteJobAlert: (payload) => axiosClient.post(API.JOB_ALERT.DELETE, payload),
};
