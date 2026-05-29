import { useState } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { ROUTES } from "@/utils/routePaths";

export function useOtpForm() {
  const navigate = useNavigate();
  const location = useLocation();

  const email = location.state?.email ?? "";
  const userId = location.state?.user_id ?? null;

  const [otp, setOtp] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [resending, setResending] = useState(false);
  const [resent, setResent] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await authApi.verifyOtp({ user_id: userId, otp });
      navigate(ROUTES.LOGIN, { replace: true, state: { verified: true } });
    } catch (err) {
      setError(
        err.data?.message ??
          err.response?.data?.message ??
          "Invalid OTP. Please try again.",
      );
    } finally {
      setLoading(false);
    }
  };

  const handleResend = async () => {
    setResending(true);
    setResent(false);
    setError("");
    try {
      await authApi.resendOtp({ email });
      setResent(true);
    } catch (err) {
      setError(
        err.data?.message ??
          err.response?.data?.message ??
          "Failed to resend OTP.",
      );
    } finally {
      setResending(false);
    }
  };

  return {
    email,
    otp,
    setOtp,
    error,
    loading,
    resending,
    resent,
    handleSubmit,
    handleResend,
  };
}
