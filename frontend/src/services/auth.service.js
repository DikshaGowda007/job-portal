import axios from "axios";
import { API } from "../constants/api";

export const loginUser = async (payload) => {
  const response = await axios.post(API.AUTH.LOGIN, payload);

  return response.data;
};