import { useState } from "react";
import { authApi } from "@/api/auth.api";

export function useChangePasswordForm() {
  const [form, setForm] = useState({ current_password: "", new_password: "", new_password_confirmation: "" });
  const [error, setError] = useState("");
  const [success, setSuccess] = useState(false);
  const [loading, setLoading] = useState(false);
  const [showCurrent, setShowCurrent] = useState(false);
  const [showNew, setShowNew] = useState(false);

  const handleChange = (e) => {
    setForm((f) => ({ ...f, [e.target.name]: e.target.value }));
    setError("");
    setSuccess(false);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      const res = await authApi.changePassword({
        current_password: form.current_password,
        new_password: form.new_password,
        new_password_confirmation: form.new_password_confirmation,
      });
      if (res.data?.status === "error") {
        setError(res.data.message);
        return;
      }
      setSuccess(true);
      setForm({ current_password: "", new_password: "", new_password_confirmation: "" });
    } catch (err) {
      setError(
        err.message,
      );
    } finally {
      setLoading(false);
    }
  };

  return { form, error, success, loading, showCurrent, setShowCurrent, showNew, setShowNew, handleChange, handleSubmit };
}
