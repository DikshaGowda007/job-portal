import { storage } from "./storage.service";

export const tokenService = {
  get: () => storage.getToken(),
  set: (token) => storage.setToken(token),
  remove: () => storage.removeToken(),
  exists: () => !!storage.getToken(),

  decode: (token) => {
    try {
      const payload = token.split(".")[1];
      return JSON.parse(atob(payload.replace(/-/g, "+").replace(/_/g, "/")));
    } catch {
      return null;
    }
  },
};
