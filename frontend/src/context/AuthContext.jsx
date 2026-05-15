import { createContext, useContext, useState } from "react";
import { storage } from "@/services/storage.service";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => storage.getUser());
  const [token, setToken] = useState(() => storage.getToken());

  const login = (userData, jwtToken) => {
    storage.setToken(jwtToken);
    storage.setUser(userData);
    setToken(jwtToken);
    setUser(userData);
  };

  const isAuthenticated = !!token;
  const role = user?.role ?? null;

  return (
    <AuthContext.Provider value={{ user, token, isAuthenticated, role, login }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used inside AuthProvider");
  return ctx;
}
