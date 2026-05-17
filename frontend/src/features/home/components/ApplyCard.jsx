import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { toast } from "sonner";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { seekerApi } from "@/api/seeker.api";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";
import ApplyForm from "@/features/home/components/ApplyForm";
import { CheckCircle2, Bookmark, BookmarkCheck } from "lucide-react";

export default function ApplyCard({ jobId }) {
  const navigate = useNavigate();
  const queryClient = useQueryClient();
  const { isAuthenticated, role } = useAuth();
  const canApply = !isAuthenticated || role === ROLES.JOB_SEEKER;

  const [showForm, setShowForm] = useState(false);
  const [appliedLocally, setAppliedLocally] = useState(false);
  const [form, setForm] = useState({
    cover_letter: "",
    expected_salary: "",
    expected_salary_currency: "INR",
    notice_period: "",
    experience_years: "",
  });
  const [applyError, setApplyError] = useState("");

  const { data: myApplications = [] } = useQuery({
    queryKey: ["my-applications-check"],
    queryFn: () =>
      seekerApi.myApplications({ per_page: 100, page: 1 })
        .then((r) => r.data?.data ?? []),
    enabled: isAuthenticated && role === ROLES.JOB_SEEKER,
  });

  const { data: savedJobs = [] } = useQuery({
    queryKey: ["seeker-saved-jobs-check"],
    queryFn: () =>
      seekerApi.savedJobs({ page: 1, per_page: 100 })
        .then((r) => Array.isArray(r.data?.data) ? r.data.data : []),
    enabled: isAuthenticated && role === ROLES.JOB_SEEKER,
  });

  const isSaved = savedJobs.some((j) => j.job_id === Number(jobId));

  const saveMutation = useMutation({
    mutationFn: () => seekerApi.saveJob({ job_post_id: Number(jobId) }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs"] });
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs-check"] });
      toast.success("Job saved successfully");
    },
    onError: (err) => toast.error(err?.data?.message ?? "Failed to save job"),
  });

  const unsaveMutation = useMutation({
    mutationFn: () => seekerApi.unsaveJob({ job_post_id: Number(jobId) }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs"] });
      queryClient.invalidateQueries({ queryKey: ["seeker-saved-jobs-check"] });
      toast.success("Job removed from saved");
    },
    onError: () => toast.error("Failed to remove saved job"),
  });

  const applied =
    appliedLocally ||
    myApplications.some((a) => a.job_post_id === Number(jobId));

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
          f.expected_salary_currency || profile.expected_salary_currency || "INR",
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
      setAppliedLocally(true);
      setShowForm(false);
    },
    onError: (err) => {
      setApplyError(err.response?.data?.message ?? err.data?.message ?? "Failed to apply. Please try again.");
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
      expected_salary: form.expected_salary ? Number(form.expected_salary) : undefined,
      expected_salary_currency: form.expected_salary_currency || undefined,
      notice_period: form.notice_period || undefined,
      experience_years: form.experience_years ? Number(form.experience_years) : undefined,
    });
  };

  if (!canApply) return null;

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      {applied ? (
        <div className="text-center">
          <CheckCircle2 size={32} className="mx-auto mb-2 text-green-500" />
          <p className="font-semibold text-gray-900 dark:text-white">Already Applied!</p>
          <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
            You've already applied for this job.
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
          {isAuthenticated && role === ROLES.JOB_SEEKER && (
            <button
              onClick={() => isSaved ? unsaveMutation.mutate() : saveMutation.mutate()}
              disabled={saveMutation.isPending || unsaveMutation.isPending}
              className="mt-3 flex w-full items-center justify-center gap-1.5 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 transition hover:border-indigo-300 hover:text-indigo-600 disabled:opacity-60 dark:border-gray-700 dark:text-gray-400 dark:hover:border-indigo-600 dark:hover:text-indigo-400"
            >
              {isSaved ? (
                <>
                  <BookmarkCheck size={15} className="text-indigo-600 dark:text-indigo-400" />
                  Saved
                </>
              ) : (
                <>
                  <Bookmark size={15} />
                  Save Job
                </>
              )}
            </button>
          )}
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
