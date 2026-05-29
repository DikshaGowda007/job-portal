import axiosClient from "./axios";
import { API } from "@/constants/api";

export const notificationsApi = {
  list: () => axiosClient.post(API.NOTIFICATION.LIST),
  markRead: (notificationId) =>
    axiosClient.post(API.NOTIFICATION.MARK_READ, notificationId ? { notification_id: notificationId } : {}),
};
