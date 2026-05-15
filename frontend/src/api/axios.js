import axios from "axios";
import { storage } from "@/services/storage.service";
import { ROUTES } from "@/utils/routePaths";

const axiosClient = axios.create({
  headers: { "Content-Type": "application/json" },
  withCredentials: true,
});

axiosClient.interceptors.request.use((config) => {
  const token = storage.getToken();
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

axiosClient.interceptors.response.use(
  (response) => {
    if (response.data?.status === "error") {
      return Promise.reject(response);
    }
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      storage.clear();
      window.location.href = ROUTES.LOGIN;
    }
    return Promise.reject(error);
  }
);

export default axiosClient;
