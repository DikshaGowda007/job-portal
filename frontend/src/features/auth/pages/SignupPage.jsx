import { Link } from "react-router-dom";
import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useSignupForm } from "@/features/auth/hooks/useSignupForm";
import {
  Briefcase,
  AlertCircle,
  Eye,
  EyeOff,
  UserRound,
  Building2,
} from "lucide-react";

const ROLES_OPTIONS = [
  {
    value: ROLES.JOB_SEEKER,
    label: "Job Seeker",
    description: "Browse and apply for jobs",
    icon: UserRound,
  },
  {
    value: ROLES.RECRUITER,
    label: "Recruiter",
    description: "Post jobs and hire talent",
    icon: Building2,
  },
];

export default function SignupPage() {
  const {
    form,
    error,
    loading,
    showPassword,
    setShowPassword,
    handleChange,
    setRole,
    handleSubmit,
  } = useSignupForm();

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 px-4 py-10 dark:bg-gray-950">
      <div className="w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        {/* Logo */}
        <div className="mb-6 flex flex-col items-center gap-2 text-center">
          <div className="flex size-12 items-center justify-center rounded-xl bg-indigo-600 text-white">
            <Briefcase size={22} />
          </div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
            Create an account
          </h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            Join thousands of job seekers and recruiters
          </p>
        </div>

        {/* Role selector */}
        <div className="mb-5 grid grid-cols-2 gap-3">
          {ROLES_OPTIONS.map(({ value, label, description, icon: Icon }) => (
            <button
              key={value}
              type="button"
              onClick={() => setRole(value)}
              className={`flex flex-col items-center gap-1.5 rounded-xl border-2 p-3 text-center transition ${
                form.role === value
                  ? "border-indigo-600 bg-indigo-50 dark:border-indigo-500 dark:bg-indigo-950"
                  : "border-gray-200 hover:border-gray-300 dark:border-gray-700 dark:hover:border-gray-600"
              }`}
            >
              <Icon
                size={20}
                className={
                  form.role === value
                    ? "text-indigo-600 dark:text-indigo-400"
                    : "text-gray-400"
                }
              />
              <span
                className={`text-sm font-medium ${form.role === value ? "text-indigo-700 dark:text-indigo-400" : "text-gray-700 dark:text-gray-300"}`}
              >
                {label}
              </span>
              <span className="text-xs text-gray-400 dark:text-gray-500">
                {description}
              </span>
            </button>
          ))}
        </div>

        {/* Error */}
        {error && (
          <div className="mb-4 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400">
            <AlertCircle size={15} className="mt-0.5 shrink-0" />
            {error}
          </div>
        )}

        {/* Form */}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div className="space-y-1.5">
              <Label htmlFor="first_name">First Name</Label>
              <Input
                id="first_name"
                name="first_name"
                value={form.first_name}
                onChange={handleChange}
                required
                placeholder="John"
                autoComplete="given-name"
                className="focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
              />
            </div>
            <div className="space-y-1.5">
              <Label htmlFor="last_name">Last Name</Label>
              <Input
                id="last_name"
                name="last_name"
                value={form.last_name}
                onChange={handleChange}
                required
                placeholder="Doe"
                autoComplete="family-name"
                className="focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
              />
            </div>
          </div>

          <div className="space-y-1.5">
            <Label htmlFor="email">Email</Label>
            <Input
              id="email"
              type="email"
              name="email"
              value={form.email}
              onChange={handleChange}
              required
              placeholder="you@example.com"
              autoComplete="email"
              className="focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800"
            />
          </div>

          <div className="space-y-1.5">
            <Label htmlFor="password">Password</Label>
            <div className="relative">
              <Input
                id="password"
                type={showPassword ? "text" : "password"}
                name="password"
                value={form.password}
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

          <button
            type="submit"
            disabled={loading}
            className="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            {loading ? "Creating account…" : "Create Account"}
          </button>
        </form>

        {/* Footer */}
        <p className="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
          Already have an account?{" "}
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
