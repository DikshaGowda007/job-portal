import { createContext, useContext, useState } from "react";
import { authApi } from "@/api/auth.api";
import { storage } from "@/services/storage.service";

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(() => storage.getUser());
  const [token, setToken] = useState(() => storage.getToken());
  const [loading, setLoading] = useState(false);

  const login = (userData, jwtToken) => {
    storage.setToken(jwtToken);
    storage.setUser(userData);
    setToken(jwtToken);
    setUser(userData);
  };

  const logout = async () => {
    try {
      await authApi.logout();
    } catch {
      // ignore errors on logout
    } finally {
      storage.clear();
      setToken(null);
      setUser(null);
    }
  };

  const isAuthenticated = !!token;
  const role = user?.role ?? null;

  return (
    <AuthContext.Provider
      value={{
        user,
        token,
        loading,
        isAuthenticated,
        role,
        login,
        logout,
        updateUser,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used inside AuthProvider");
  return ctx;
}
