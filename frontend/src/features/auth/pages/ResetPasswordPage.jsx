import { Link } from "react-router-dom";
import { ROUTES } from "@/utils/routePaths";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useResetPasswordForm } from "@/features/auth/hooks/useResetPasswordForm";
import { Briefcase, AlertCircle, Eye, EyeOff } from "lucide-react";

export default function ResetPasswordPage() {
  const {
    email,
    form,
    error,
    loading,
    showPassword,
    setShowPassword,
    handleChange,
    handleSubmit,
  } = useResetPasswordForm();

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4 dark:bg-gray-950">
      <div className="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div className="mb-6 flex flex-col items-center gap-2 text-center">
          <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
            <Briefcase size={22} />
          </div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
            Reset password
          </h1>
          {email && (
            <p className="text-sm text-gray-500 dark:text-gray-400">
              OTP sent to{" "}
              <span className="font-medium text-gray-700 dark:text-gray-300">
                {email}
              </span>
            </p>
          )}
        </div>

        {error && (
          <div className="mb-4 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400">
            <AlertCircle size={15} className="mt-0.5 shrink-0" />
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-1.5">
            <Label htmlFor="otp">OTP</Label>
            <Input
              id="otp"
              name="otp"
              value={form.otp}
              onChange={handleChange}
              required
              placeholder="Enter 6-digit OTP"
              maxLength={6}
              inputMode="numeric"
              className="tracking-widest focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
            />
          </div>

          <div className="space-y-1.5">
            <Label htmlFor="new_password">New password</Label>
            <div className="relative">
              <Input
                id="new_password"
                type={showPassword ? "text" : "password"}
                name="new_password"
                value={form.new_password}
                onChange={handleChange}
                required
                placeholder="••••••••"
                autoComplete="new-password"
                className="pr-10 focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
              />
              <button
                type="button"
                onClick={() => setShowPassword((v) => !v)}
                tabIndex={-1}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
              >
                {showPassword ? <EyeOff size={15} /> : <Eye size={15} />}
              </button>
            </div>
          </div>

          <div className="space-y-1.5">
            <Label htmlFor="new_password_confirmation">
              Confirm new password
            </Label>
            <Input
              id="new_password_confirmation"
              type={showPassword ? "text" : "password"}
              name="new_password_confirmation"
              value={form.new_password_confirmation}
              onChange={handleChange}
              required
              placeholder="••••••••"
              autoComplete="new-password"
              className="focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            {loading ? "Resetting…" : "Reset Password"}
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          <Link
            to={ROUTES.FORGOT_PASSWORD}
            className="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
          >
            Resend OTP
          </Link>
        </p>
      </div>
    </div>
  );
}
