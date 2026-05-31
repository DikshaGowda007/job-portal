import { useState, useEffect } from "react";
import { useLocation, useNavigate } from "react-router-dom";
import { authApi } from "@/api/auth.api";
import { ROUTES } from "@/utils/routePaths";

export function useOtpForm() {
  const navigate = useNavigate();
  const location = useLocation();

  const email = location.state?.email ?? "";
  const userId = location.state?.user_id ?? null;
  const notice = location.state?.notice ?? null;

  const [otp, setOtp] = useState("");
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [resending, setResending] = useState(false);
  const [resent, setResent] = useState(false);

  const submitOtp = async (otpValue) => {
    setError("");
    setLoading(true);
    try {
      await authApi.verifyOtp({ user_id: userId, otp: otpValue });
      navigate(ROUTES.LOGIN, { replace: true, state: { verified: true } });
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    if (otp.length === 6 && !loading) {
      submitOtp(otp);
    }
  }, [otp]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    await submitOtp(otp);
  };

  const handleResend = async () => {
    setResending(true);
    setResent(false);
    setError("");
    try {
      await authApi.resendOtp({ email });
      setResent(true);
    } catch (err) {
      setError(err.message);
    } finally {
      setResending(false);
    }
  };

  return {
    email,
    otp,
    setOtp,
    notice,
    error,
    loading,
    resending,
    resent,
    handleSubmit,
    handleResend,
  };
}
