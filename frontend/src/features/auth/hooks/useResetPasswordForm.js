import { useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { ROUTES } from "@/utils/routePaths";

export function useResetPasswordForm() {
  const navigate = useNavigate();
  const location = useLocation();

  const email = location.state?.email ?? "";

  const [form, setForm] = useState({
    otp: "",
    new_password: "",
    new_password_confirmation: "",
  });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
    setError("");
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (form.new_password !== form.new_password_confirmation) {
      setError("Passwords do not match.");
      return;
    }
    setError("");
    setLoading(true);
    try {
      await authApi.resetPassword({
        email,
        otp: form.otp,
        new_password: form.new_password,
        new_password_confirmation: form.new_password_confirmation,
      });
      navigate(ROUTES.LOGIN, {
        replace: true,
        state: {
          verified: true,
          message: "Password reset successfully. Please sign in.",
        },
      });
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return {
    email,
    form,
    error,
    loading,
    showPassword,
    setShowPassword,
    handleChange,
    handleSubmit,
  };
}
