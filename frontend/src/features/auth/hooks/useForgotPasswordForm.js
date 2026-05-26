import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { ROUTES } from "@/utils/routePaths";

export function useForgotPasswordForm() {
  const navigate = useNavigate();

  const [email, setEmail] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [sent, setSent] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await authApi.forgotPassword({ email });
      setSent(true);
      setTimeout(() => {
        navigate(ROUTES.RESET_PASSWORD, { state: { email } });
      }, 1500);
    } catch (err) {
      setError(
        err.response?.data?.message ?? "Failed to send reset OTP. Try again.",
      );
    } finally {
      setLoading(false);
    }
  };

  return { email, setEmail, error, loading, sent, handleSubmit };
}
