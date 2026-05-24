import axiosClient from "./axios";
import { API } from "@/constants/api";

export const categoryApi = {
  list: (params) => axiosClient.post(API.CATEGORY.LIST, params),
  get: (payload) => axiosClient.post(API.CATEGORY.GET, payload),
  add: (payload) => axiosClient.post(API.CATEGORY.ADD, payload),
  edit: (payload) => axiosClient.post(API.CATEGORY.EDIT, payload),
  delete: (payload) => axiosClient.post(API.CATEGORY.DELETE, payload),
};
