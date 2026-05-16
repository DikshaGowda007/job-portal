import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useQuery, useMutation } from "@tanstack/react-query";
import { seekerApi } from "@/api/seeker.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";
import ApplyForm from "@/features/home/components/ApplyForm";
import { CheckCircle2 } from "lucide-react";

export default function ApplyCard({ jobId }) {
  const navigate = useNavigate();
  const { isAuthenticated, role } = useAuth();
  const canApply = !isAuthenticated || role === ROLES.JOB_SEEKER;

  const [showForm, setShowForm] = useState(false);
  const [applied, setApplied] = useState(false);
  const [form, setForm] = useState({
    cover_letter: "",
    expected_salary: "",
    expected_salary_currency: "INR",
    notice_period: "",
    experience_years: "",
  });
  const [applyError, setApplyError] = useState("");

  const { data: profile, refetch: refetchProfile } = useQuery({
    queryKey: ["seeker-profile-apply"],
    queryFn: () => seekerApi.getProfile().then((r) => r.data?.data),
    enabled: isAuthenticated && role === ROLES.JOB_SEEKER,
  });

  useEffect(() => {
    if (profile) {
      setForm((f) => ({
        ...f,
        notice_period: f.notice_period || profile.notice_period || "",
        expected_salary:
          f.expected_salary ||
          (profile.expected_salary ? String(profile.expected_salary) : ""),
        expected_salary_currency:
          f.expected_salary_currency ||
          profile.expected_salary_currency ||
          "INR",
        experience_years:
          f.experience_years ||
          (profile.total_experience_years
            ? String(profile.total_experience_years)
            : ""),
      }));
    }
  }, [profile]);

  const uploadResumeMutation = useMutation({
    mutationFn: (file) => {
      const fd = new FormData();
      fd.append("resume", file);
      return seekerApi.uploadResume(fd);
    },
    onSuccess: () => refetchProfile(),
  });

  const applyMutation = useMutation({
    mutationFn: (payload) => seekerApi.applyJob(payload),
    onSuccess: () => {
      setApplied(true);
      setShowForm(false);
    },
    onError: (err) => {
      setApplyError(
        err.data?.message,
      );
    },
  });

  const handleApplyClick = () => {
    if (!isAuthenticated) {
      navigate(ROUTES.LOGIN, { state: { from: `/jobs/${jobId}` } });
      return;
    }
    setShowForm(true);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    setApplyError("");
    applyMutation.mutate({
      job_post_id: Number(jobId),
      resume_path: profile?.resume_path || undefined,
      cover_letter: form.cover_letter || undefined,
      expected_salary: form.expected_salary
        ? Number(form.expected_salary)
        : undefined,
      expected_salary_currency: form.expected_salary_currency || undefined,
      notice_period: form.notice_period || undefined,
      experience_years: form.experience_years
        ? Number(form.experience_years)
        : undefined,
    });
  };

  if (!canApply) return null;

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      {applied ? (
        <div className="text-center">
          <CheckCircle2 size={32} className="mx-auto mb-2 text-green-500" />
          <p className="font-semibold text-gray-900 dark:text-white">
            Application Submitted!
          </p>
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Good luck with your application.
          </p>
        </div>
      ) : showForm ? (
        <ApplyForm
          form={form}
          onChange={(k, v) => setForm((f) => ({ ...f, [k]: v }))}
          onSubmit={handleSubmit}
          onCancel={() => setShowForm(false)}
          loading={applyMutation.isPending}
          error={applyError}
          resumeFilename={profile?.resume_filename}
          resumeUploading={uploadResumeMutation.isPending}
          onResumeUpload={(file) => uploadResumeMutation.mutate(file)}
        />
      ) : (
        <div className="text-center">
          <button
            onClick={handleApplyClick}
            className="w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            Apply Now
          </button>
          {!isAuthenticated && (
            <p className="mt-2 text-xs text-gray-400 dark:text-gray-500">
              You'll be asked to log in before applying.
            </p>
          )}
        </div>
      )}
    </div>
  );
}
