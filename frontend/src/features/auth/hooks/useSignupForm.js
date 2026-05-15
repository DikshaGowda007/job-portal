import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { ROUTES } from "@/utils/routePaths";
import { ROLES, USER_TYPE_MAP } from "@/utils/roles";

export function useSignupForm() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    role: ROLES.JOB_SEEKER,
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
    setError("");
  };

  const setRole = (role) => {
    setForm((f) => ({ ...f, role }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      const res = await authApi.signup({
        first_name: form.first_name,
        last_name: form.last_name,
        email: form.email,
        password: form.password,
        user_type: USER_TYPE_MAP[form.role],
      });
      const userId = res.data?.data?.id;
      navigate(ROUTES.OTP, { state: { email: form.email, user_id: userId } });
    } catch (err) {
      setError(
        err.data?.message ??
          err.response?.data?.message ??
          "Signup failed. Please try again.",
      );
    } finally {
      setLoading(false);
    }
  };

  return {
    form,
    error,
    loading,
    showPassword,
    setShowPassword,
    handleChange,
    setRole,
    handleSubmit,
  };
}
