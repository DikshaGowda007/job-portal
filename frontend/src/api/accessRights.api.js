import axiosClient from "./axios";
import { API } from "@/constants/api";

export const accessRightsApi = {
  get: (data) => axiosClient.post(API.ACCESS_RIGHTS.GET, data),
  edit: (data) => axiosClient.post(API.ACCESS_RIGHTS.EDIT, data),
};
