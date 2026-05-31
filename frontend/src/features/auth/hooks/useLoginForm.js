import { useState } from "react";
import { useNavigate, useLocation } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { ROLES, USER_TYPE_TO_ROLE } from "@/utils/roles";
import { tokenService } from "@/services/token.service";

const ROLE_REDIRECT = {
  [ROLES.ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.SUB_ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.RECRUITER]: ROUTES.RECRUITER_DASHBOARD,
  [ROLES.JOB_SEEKER]: ROUTES.HOME,
};

export function useLoginForm() {
  const navigate = useNavigate();
  const location = useLocation();
  const { login } = useAuth();

  const from = location.state?.from ?? null;
  const verified = location.state?.verified ?? false;
  const verifiedMessage = location.state?.message ?? null;

  const [form, setForm] = useState({ email: "", password: "" });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
    setError("");
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      const res = await authApi.login(form);
      const { request_token, name } = res.data?.data ?? {};
      const payload = tokenService.decode(request_token);
      const user = {
        name,
        role: USER_TYPE_TO_ROLE[payload?.loggedin_user_type] ?? null,
        id: payload?.loggedin_user_id,
        email: payload?.loggedin_user_email,
      };
      login(user, request_token);

      if (from && user?.role === ROLES.JOB_SEEKER) {
        navigate(from, { replace: true });
      } else {
        navigate(ROLE_REDIRECT[user?.role] ?? ROUTES.HOME, { replace: true });
      }
    } catch (err) {
      const msg = err.message;
      if (
        msg?.toLowerCase().includes("otp") ||
        msg?.toLowerCase().includes("verify")
      ) {
        navigate(ROUTES.OTP, { state: { email: form.email, notice: "Please verify your account before signing in." } });
      } else {
        setError(msg);
      }
    } finally {
      setLoading(false);
    }
  };

  return {
    form,
    error,
    loading,
    verified,
    verifiedMessage,
    showPassword,
    setShowPassword,
    handleChange,
    handleSubmit,
  };
}
