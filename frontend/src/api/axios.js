import axios from "axios";
import { toast } from "sonner";
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

const handleForbidden = () => {
  toast.error("Access Forbidden.");
  import("@/routes").then((m) => m.default.navigate(-1));
  return new Promise(() => {});
};

axiosClient.interceptors.response.use(
  (response) => {
    if (response.data?.status === "error") {
      if (response.data?.message === "Access Forbidden.") return handleForbidden();
      return Promise.reject(response);
    }
    return response;
  },
  (error) => {
    if (error.response?.status === 401) {
      storage.clear();
      window.location.href = ROUTES.LOGIN;
    }
    if (error.response?.status === 403) return handleForbidden();
    return Promise.reject(error);
  }
);

export default axiosClient;
