import axiosClient from "./axios";
import { API } from "@/constants/api";

export const adminApi = {
  dashboard: () => axiosClient.post(API.ADMIN.DASHBOARD),
  listUsers: (params) => axiosClient.post(API.ADMIN.USERS_LIST, params),
  viewUser: (payload) => axiosClient.post(API.ADMIN.USERS_VIEW, payload),
  toggleUserStatus: (payload) =>
    axiosClient.post(API.ADMIN.TOGGLE_USER_STATUS, payload),
  createSubAdmin: (payload) =>
    axiosClient.post(API.ADMIN.CREATE_SUB_ADMIN, payload),
  listSubAdmins: (params) =>
    axiosClient.post(API.ADMIN.LIST_SUB_ADMINS, params),
  listApplications: (params) => axiosClient.post(API.APPLICATION.LIST, params),
  viewApplication: (payload) => axiosClient.post(API.APPLICATION.VIEW, payload),
  updateApplicationStatus: (payload) =>
    axiosClient.post(API.APPLICATION.UPDATE_STATUS, payload),
};
