import axiosClient from "./axios";
import { API } from "@/constants/api";

export const jobsApi = {
  list: (params) => axiosClient.post(API.JOB.LIST, params),
};
