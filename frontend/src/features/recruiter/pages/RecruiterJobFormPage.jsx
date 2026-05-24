import { useState, useEffect } from "react";
import { useNavigate, useParams, useLocation } from "react-router-dom";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { jobsApi } from "@/api/jobs.api";
import { categoryApi } from "@/api/category.api";
import { ROUTES } from "@/utils/routePaths";
import Loader from "@/components/common/Loader";
import DatePicker from "@/components/ui/DatePicker";
import {
  Section,
  GroupLabel,
  Field,
  SelectField,
  inp,
} from "@/components/forms/FormUI";
import {
  Briefcase,
  Building2,
  MapPin,
  GraduationCap,
  DollarSign,
  Settings,
  X,
  Save,
} from "lucide-react";

const WORK_MODES = [
  { value: "onsite", label: "Onsite" },
  { value: "remote", label: "Remote" },
  { value: "hybrid", label: "Hybrid" },
];
const JOB_TYPES = [
  { value: "FULL_TIME", label: "Full Time" },
  { value: "PART_TIME", label: "Part Time" },
  { value: "REMOTE", label: "Remote" },
  { value: "INTERNSHIP", label: "Internship" },
];
const EXP_LEVELS = [
  { value: "FRESHER", label: "Fresher" },
  { value: "JUNIOR", label: "Junior" },
  { value: "MID", label: "Mid" },
  { value: "SENIOR", label: "Senior" },
  { value: "TEAM_LEAD", label: "Team Lead" },
];
const CURRENCIES = [
  { value: "INR", label: "INR" },
  { value: "USD", label: "USD" },
];
const SALARY_TYPES = [
  { value: "monthly", label: "Monthly" },
  { value: "yearly", label: "Yearly" },
];
const STATUSES = [
  { value: "OPEN", label: "Open" },
  { value: "CLOSED", label: "Closed" },
  { value: "EXPIRED", label: "Expired" },
];

const EMPTY_FORM = {
  title: "",
  company_name: "",
  location: "",
  work_mode: "onsite",
  job_type: "FULL_TIME",
  status: "OPEN",
  job_category_id: "",
  job_description: "",
  roles_responsibility: "",
  skills: [],
  education: "",
  experience_level: "",
  experience_min: "",
  experience_max: "",
  salary_min: "",
  salary_max: "",
  salary_currency: "INR",
  salary_type: "monthly",
  openings_count: "",
  expires_at: "",
};

export default function RecruiterJobFormPage() {
  const { id } = useParams();
  const isEdit = Boolean(id);
  const navigate = useNavigate();
  const { pathname } = useLocation();
  const isPublish = pathname === ROUTES.RECRUITER_JOB_PUBLISH;
  const queryClient = useQueryClient();

  const [form, setForm] = useState(EMPTY_FORM);
  const [skillInput, setSkillInput] = useState("");

  const { data: jobData, isLoading: jobLoading } = useQuery({
    queryKey: ["job-detail", id],
    queryFn: () => jobsApi.get({ id: Number(id) }).then((r) => r.data?.data),
    enabled: isEdit,
  });

  const { data: categories = [] } = useQuery({
    queryKey: ["categories"],
    queryFn: () => categoryApi.list({}).then((r) => r.data?.data?.list ?? []),
  });

  useEffect(() => {
    if (!jobData) return;
    const j = jobData;
    setForm({
      title: j.title ?? "",
      company_name: j.company_name ?? "",
      location: j.location ?? "",
      work_mode: j.work_mode ?? "onsite",
      job_type: j.job_type ?? "FULL_TIME",
      status: j.status ?? "OPEN",
      job_category_id: j.job_category_id ?? "",
      job_description: j.job_description ?? "",
      roles_responsibility: Array.isArray(j.roles_responsibility)
        ? j.roles_responsibility.join("\n")
        : (j.roles_responsibility ?? ""),
      skills: Array.isArray(j.skills) ? j.skills : [],
      education: j.education ?? "",
      experience_level: j.experience_level ?? "",
      experience_min: j.experience_min ?? "",
      experience_max: j.experience_max ?? "",
      salary_min: j.salary_min ?? "",
      salary_max: j.salary_max ?? "",
      salary_currency: j.salary_currency ?? "INR",
      salary_type: j.salary_type ?? "monthly",
      openings_count: j.openings_count ?? "",
      expires_at: j.expires_at ? j.expires_at.slice(0, 10) : "",
    });
  }, [jobData]);

  const mutation = useMutation({
    mutationFn: (payload) => {
      if (isEdit) return jobsApi.edit(payload);
      if (isPublish) return jobsApi.publish(payload);
      return jobsApi.add(payload);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["recruiter-jobs"] });
      toast.success(
        isEdit ? "Job updated" : isPublish ? "Job published" : "Job saved",
      );
      navigate(ROUTES.RECRUITER_JOBS);
    },
    onError: (err) =>
      toast.error(err.response?.data?.message ?? "Something went wrong."),
  });

  const set = (key) => (e) => setForm((f) => ({ ...f, [key]: e.target.value }));

  const addSkill = () => {
    const v = skillInput.trim();
    if (v && !form.skills.includes(v))
      setForm((f) => ({ ...f, skills: [...f.skills, v] }));
    setSkillInput("");
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    const payload = {
      ...form,
      salary_min: form.salary_min ? Number(form.salary_min) : undefined,
      salary_max: form.salary_max ? Number(form.salary_max) : undefined,
      experience_min: form.experience_min
        ? Number(form.experience_min)
        : undefined,
      experience_max: form.experience_max
        ? Number(form.experience_max)
        : undefined,
      openings_count: form.openings_count
        ? Number(form.openings_count)
        : undefined,
      job_category_id: form.job_category_id
        ? Number(form.job_category_id)
        : undefined,
      expires_at: form.expires_at || undefined,
      experience_level: form.experience_level || undefined,
      education: form.education || undefined,
      roles_responsibility: form.roles_responsibility
        ? form.roles_responsibility
            .split("\n")
            .map((s) => s.trim())
            .filter(Boolean)
        : [],
    };
    if (isEdit) payload.id = Number(id);
    mutation.mutate(payload);
  };

  if (isEdit && jobLoading) return <Loader />;

  const pageTitle = isEdit
    ? "Edit Job"
    : isPublish
      ? "Publish a Job"
      : "Save as Draft";

  return (
    <div className="mx-auto max-w-3xl">
      <div className="mb-6">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
          {pageTitle}
        </h1>
        <p className="mt-1 text-gray-500 dark:text-gray-400">
          {isEdit
            ? "Update job details below."
            : "Fill in the details to create a new job posting."}
        </p>
      </div>

      <form onSubmit={handleSubmit} className="space-y-5">
        <Section
          title="Basic Information"
          icon={Briefcase}
          iconColor="text-indigo-600"
          iconBg="bg-indigo-50 dark:bg-indigo-900/30"
        >
          <GroupLabel>Role</GroupLabel>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <Field label="Job Title *">
              <input
                required
                value={form.title}
                onChange={set("title")}
                placeholder="e.g. Senior React Developer"
                {...inp}
              />
            </Field>
            <Field label="Company Name *">
              <div className="relative">
                <Building2
                  size={14}
                  className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                />
                <input
                  required
                  value={form.company_name}
                  onChange={set("company_name")}
                  placeholder="e.g. Acme Corp"
                  className={`${inp.className} pl-8`}
                />
              </div>
            </Field>
            <Field label="Location *">
              <div className="relative">
                <MapPin
                  size={14}
                  className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
                />
                <input
                  required
                  value={form.location}
                  onChange={set("location")}
                  placeholder="e.g. Bangalore, Karnataka"
                  className={`${inp.className} pl-8`}
                />
              </div>
            </Field>
            <Field label="Job Category">
              <div className="flex flex-wrap gap-2 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800/60">
                {categories.length === 0 ? (
                  <p className="text-xs text-gray-400">No categories available</p>
                ) : (
                  categories.map((cat) => {
                    const selected = String(form.job_category_id) === String(cat.id);
                    return (
                      <button
                        key={cat.id}
                        type="button"
                        onClick={() =>
                          setForm((f) => ({ ...f, job_category_id: selected ? "" : cat.id }))
                        }
                        className={`rounded-full px-3.5 py-1.5 text-xs font-semibold transition-all ${
                          selected
                            ? "bg-indigo-600 text-white shadow-sm dark:bg-indigo-500"
                            : "bg-white text-gray-600 ring-1 ring-inset ring-gray-200 hover:text-indigo-600 hover:ring-indigo-300 dark:bg-gray-900 dark:text-gray-400 dark:ring-gray-700 dark:hover:text-indigo-400 dark:hover:ring-indigo-700"
                        }`}
                      >
                        {cat.name}
                      </button>
                    );
                  })
                )}
              </div>
            </Field>
          </div>

          <GroupLabel>Type & Status</GroupLabel>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <SelectField
              label="Work Mode *"
              required
              value={form.work_mode}
              onChange={set("work_mode")}
              options={WORK_MODES}
            />
            <SelectField
              label="Job Type *"
              required
              value={form.job_type}
              onChange={set("job_type")}
              options={JOB_TYPES}
            />
            <SelectField
              label="Status *"
              required
              value={form.status}
              onChange={set("status")}
              options={STATUSES}
            />
          </div>
        </Section>

        <Section
          title="Job Description"
          icon={Settings}
          iconColor="text-sky-600"
          iconBg="bg-sky-50 dark:bg-sky-900/30"
        >
          <Field label="Description *">
            <textarea
              required
              rows={6}
              value={form.job_description}
              onChange={set("job_description")}
              placeholder="Describe the role, responsibilities, and requirements…"
              {...inp}
            />
          </Field>
          <Field label="Roles & Responsibilities">
            <textarea
              rows={4}
              value={form.roles_responsibility}
              onChange={set("roles_responsibility")}
              placeholder={"Develop frontend features\nReview pull requests"}
              {...inp}
            />
            <p className="mt-1 text-xs text-gray-400">
              One responsibility per line
            </p>
          </Field>
        </Section>

        <Section
          title="Skills & Education"
          icon={GraduationCap}
          iconColor="text-emerald-600"
          iconBg="bg-emerald-50 dark:bg-emerald-900/30"
        >
          <Field label="Skills">
            <div className="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-800">
              {form.skills.length > 0 && (
                <div className="mb-2 flex flex-wrap gap-1.5">
                  {form.skills.map((s) => (
                    <span
                      key={s}
                      className="flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
                    >
                      {s}
                      <button
                        type="button"
                        onClick={() =>
                          setForm((f) => ({
                            ...f,
                            skills: f.skills.filter((x) => x !== s),
                          }))
                        }
                        className="ml-0.5 text-indigo-400 hover:text-indigo-600"
                      >
                        <X size={10} />
                      </button>
                    </span>
                  ))}
                </div>
              )}
              <div className="flex gap-2">
                <input
                  value={skillInput}
                  onChange={(e) => setSkillInput(e.target.value)}
                  onKeyDown={(e) => {
                    if (e.key === "Enter" || e.key === ",") {
                      e.preventDefault();
                      addSkill();
                    }
                  }}
                  placeholder="Type a skill and press Enter"
                  className="min-w-0 flex-1 bg-transparent text-sm text-gray-700 outline-none placeholder:text-gray-400 dark:text-gray-300"
                />
                {skillInput.trim() && (
                  <button
                    type="button"
                    onClick={addSkill}
                    className="shrink-0 rounded-lg bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-indigo-700"
                  >
                    Add
                  </button>
                )}
              </div>
            </div>
            <p className="mt-1 text-xs text-gray-400">
              Press Enter or comma to add each skill
            </p>
          </Field>
          <Field label="Education">
            <input
              value={form.education}
              onChange={set("education")}
              placeholder="e.g. B.Tech / B.E."
              {...inp}
            />
          </Field>
        </Section>

        <Section
          title="Experience"
          icon={Briefcase}
          iconColor="text-violet-600"
          iconBg="bg-violet-50 dark:bg-violet-900/30"
        >
          <SelectField
            label="Experience Level"
            value={form.experience_level}
            onChange={set("experience_level")}
            options={EXP_LEVELS}
            placeholder="— Select level —"
          />
          <div className="grid grid-cols-2 gap-4">
            <Field label="Min Years">
              <input
                type="number"
                min="0"
                value={form.experience_min}
                onChange={set("experience_min")}
                placeholder="0"
                {...inp}
              />
            </Field>
            <Field label="Max Years">
              <input
                type="number"
                min="0"
                value={form.experience_max}
                onChange={set("experience_max")}
                placeholder="5"
                {...inp}
              />
            </Field>
          </div>
        </Section>

        <Section
          title="Salary"
          icon={DollarSign}
          iconColor="text-amber-600"
          iconBg="bg-amber-50 dark:bg-amber-900/30"
        >
          <div className="grid grid-cols-2 gap-4">
            <Field label="Min Salary">
              <input
                type="number"
                min="0"
                value={form.salary_min}
                onChange={set("salary_min")}
                placeholder="0"
                {...inp}
              />
            </Field>
            <Field label="Max Salary">
              <input
                type="number"
                min="0"
                value={form.salary_max}
                onChange={set("salary_max")}
                placeholder="100000"
                {...inp}
              />
            </Field>
            <SelectField
              label="Currency"
              value={form.salary_currency}
              onChange={set("salary_currency")}
              options={CURRENCIES}
            />
            <SelectField
              label="Salary Type"
              value={form.salary_type}
              onChange={set("salary_type")}
              options={SALARY_TYPES}
            />
          </div>
        </Section>

        <Section
          title="Other Details"
          icon={Settings}
          iconColor="text-gray-500"
          iconBg="bg-gray-100 dark:bg-gray-800"
        >
          <div className="grid grid-cols-2 gap-4">
            <Field label="Openings">
              <input
                type="number"
                min="1"
                value={form.openings_count}
                onChange={set("openings_count")}
                placeholder="1"
                {...inp}
              />
            </Field>
            <Field label="Expires At">
              <DatePicker
                value={form.expires_at}
                onChange={(v) => setForm((f) => ({ ...f, expires_at: v }))}
                placeholder="Pick expiry date"
              />
            </Field>
          </div>
        </Section>

        <div className="flex gap-3 pb-6">
          <button
            type="submit"
            disabled={mutation.isPending}
            className="flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
          >
            <Save size={14} />
            {mutation.isPending
              ? "Saving…"
              : isEdit
                ? "Save Changes"
                : isPublish
                  ? "Publish Job"
                  : "Save Draft"}
          </button>
          <button
            type="button"
            onClick={() => navigate(ROUTES.RECRUITER_JOBS)}
            className="rounded-xl border border-gray-200 bg-white px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
          >
            Cancel
          </button>
        </div>
      </form>
    </div>
  );
}
