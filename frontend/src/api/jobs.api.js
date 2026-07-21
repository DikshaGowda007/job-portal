import axiosClient from "./axios";
import { API } from "@/constants/api";

export const jobsApi = {
  list: (params) => axiosClient.post(API.JOB.LIST, params),
  get: (payload) => axiosClient.post(API.JOB.GET, payload),
  add: (payload) => axiosClient.post(API.JOB.ADD, payload),
  edit: (payload) => axiosClient.post(API.JOB.EDIT, payload),
  delete: (payload) => axiosClient.post(API.JOB.DELETE, payload),
  publish: (payload) => axiosClient.post(API.JOB.PUBLISH, payload),
};
