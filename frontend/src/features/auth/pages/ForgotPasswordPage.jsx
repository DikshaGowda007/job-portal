import { Link } from "react-router-dom";
import { ROUTES } from "@/utils/routePaths";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useForgotPasswordForm } from "@/features/auth/hooks/useForgotPasswordForm";
import { Briefcase, AlertCircle, CheckCircle2 } from "lucide-react";

export default function ForgotPasswordPage() {
  const { email, setEmail, error, loading, sent, handleSubmit } = useForgotPasswordForm();

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4 dark:bg-gray-950">
      <div className="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div className="mb-6 flex flex-col items-center gap-2 text-center">
          <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
            <Briefcase size={22} />
          </div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
            Forgot password?
          </h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            Enter your email and we&apos;ll send you a reset OTP
          </p>
        </div>

        {sent && (
          <div className="mb-4 flex items-start gap-2.5 rounded-lg border border-green-200 bg-green-50 px-3.5 py-3 text-sm text-green-700 dark:border-green-900 dark:bg-green-950/50 dark:text-green-400">
            <CheckCircle2 size={15} className="mt-0.5 shrink-0" />
            OTP sent! Redirecting you to reset your password…
          </div>
        )}

        {error && (
          <div className="mb-4 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400">
            <AlertCircle size={15} className="mt-0.5 shrink-0" />
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="space-y-1.5">
            <Label htmlFor="email">Email address</Label>
            <Input
              id="email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              placeholder="you@example.com"
              autoComplete="email"
              className="focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
            />
          </div>

          <button
            type="submit"
            disabled={loading || sent}
            className="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            {loading ? "Sending OTP…" : "Send Reset OTP"}
          </button>
        </form>

        <p className="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          Remember your password?{" "}
          <Link
            to={ROUTES.LOGIN}
            className="font-medium text-indigo-600 hover:underline dark:text-indigo-400"
          >
            Sign in
          </Link>
        </p>
      </div>
    </div>
  );
}
