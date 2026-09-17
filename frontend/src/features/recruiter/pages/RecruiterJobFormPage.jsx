import { useState, useEffect, useRef } from "react";
import { useNavigate, useParams, useLocation } from "react-router-dom";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { jobsApi } from "@/api/jobs.api";
import { categoryApi } from "@/api/category.api";
import { ROUTES } from "@/utils/routePaths";
import {
  WORK_MODE_OPTIONS,
  JOB_TYPE_OPTIONS,
  EXP_LEVEL_OPTIONS,
  SALARY_TYPE_OPTIONS,
  CURRENCY_OPTIONS,
} from "@/utils/constants";
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
  Send,
  Sparkles,
  Plus,
  Check,
} from "lucide-react";

const SENIORITY_TO_EXP_LEVEL = {
  Junior: "JUNIOR",
  Mid: "MID",
  Senior: "SENIOR",
  Lead: "TEAM_LEAD",
};

const EMPTY_FORM = {
  title: "",
  company_name: "",
  location: "",
  work_mode: "onsite",
  job_type: "FULL_TIME",
  status: "DRAFT",
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
  const queryClient = useQueryClient();
  const actionRef = useRef("draft");

  const [form, setForm] = useState(EMPTY_FORM);
  const [skillInput, setSkillInput] = useState("");
  const [analysis, setAnalysis] = useState(null);

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

  const isDraft = !isEdit || form.status === "DRAFT";

  const mutation = useMutation({
    mutationFn: (payload) => {
      if (isEdit) return jobsApi.edit(payload);
      return actionRef.current === "publish" ? jobsApi.publish(payload) : jobsApi.add(payload);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["recruiter-jobs"] });
      const action = isEdit
        ? (actionRef.current === "publish" ? "Job published" : "Job updated")
        : (actionRef.current === "publish" ? "Job published" : "Job saved as draft");
      toast.success(action);
      navigate(ROUTES.RECRUITER_JOBS);
    },
    onError: (err) => toast.error(err.message),
  });

  const analyzeMutation = useMutation({
    mutationFn: () => jobsApi.analyze({ job_description: form.job_description }),
    onSuccess: (res) => {
      const data = res.data?.data;
      if (!data) {
        toast.error("No suggestions found");
        return;
      }
      setAnalysis(data);
    },
    onError: (err) => toast.error(err.message || "Couldn't analyze description"),
  });

  const addSuggestedSkill = (skill) => {
    setForm((f) => (f.skills.includes(skill) ? f : { ...f, skills: [...f.skills, skill] }));
  };

  const applySuggestedExperience = () => {
    if (!analysis) return;
    setForm((f) => ({
      ...f,
      experience_level: SENIORITY_TO_EXP_LEVEL[analysis.seniority_level] ?? f.experience_level,
      experience_min:
        analysis.minimum_years_experience != null
          ? analysis.minimum_years_experience
          : f.experience_min,
    }));
    toast.success("Applied to Experience section below");
  };

  const applySuggestedSalary = () => {
    if (!analysis) return;
    setForm((f) => ({
      ...f,
      salary_min: analysis.salary_min ?? f.salary_min,
      salary_max: analysis.salary_max ?? f.salary_max,
      salary_currency: analysis.salary_currency ?? f.salary_currency,
      salary_type: analysis.salary_type ?? f.salary_type,
    }));
    toast.success("Applied to Salary section below");
  };

  const applySuggestedEducation = () => {
    if (!analysis?.education) return;
    setForm((f) => ({ ...f, education: analysis.education }));
    toast.success("Applied to Education field above");
  };

  const applySuggestedRoles = () => {
    if (!analysis?.roles_responsibility?.length) return;
    setForm((f) => ({
      ...f,
      roles_responsibility: f.roles_responsibility
        ? `${f.roles_responsibility}\n${analysis.roles_responsibility.join("\n")}`
        : analysis.roles_responsibility.join("\n"),
    }));
    toast.success("Added to Roles & Responsibilities below");
  };

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
    if (isEdit) {
      payload.id = Number(id);
      if (actionRef.current === "publish") payload.status = "OPEN";
    }
    mutation.mutate(payload);
  };

  if (isEdit && jobLoading) return <Loader />;

  const pageTitle = isEdit ? "Edit Job" : "New Job Posting";

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

          <GroupLabel>Type</GroupLabel>
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <SelectField
              label="Work Mode *"
              required
              value={form.work_mode}
              onChange={set("work_mode")}
              options={WORK_MODE_OPTIONS}
            />
            <SelectField
              label="Job Type *"
              required
              value={form.job_type}
              onChange={set("job_type")}
              options={JOB_TYPE_OPTIONS}
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
              onChange={(e) => {
                set("job_description")(e);
                setAnalysis(null);
              }}
              placeholder="Describe the role, responsibilities, and requirements…"
              {...inp}
            />
          </Field>

          <button
            type="button"
            onClick={() => analyzeMutation.mutate()}
            disabled={analyzeMutation.isPending || form.job_description.trim().length < 40}
            className="flex items-center gap-1.5 rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-1.5 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 disabled:cursor-not-allowed disabled:opacity-50 dark:border-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300"
          >
            <Sparkles size={13} />
            {analyzeMutation.isPending ? "Analyzing…" : "Review description"}
          </button>
          {form.job_description.trim().length > 0 &&
            form.job_description.trim().length < 40 && (
              <p className="mt-1 text-xs text-gray-400">
                Write at least 40 characters to enable review
              </p>
            )}

          {analysis && (
            <div className="mt-3 rounded-xl border border-indigo-100 bg-indigo-50/60 p-3 dark:border-indigo-900 dark:bg-indigo-900/10">
              <div className="mb-2 flex items-center justify-between">
                <p className="text-xs font-semibold text-indigo-700 dark:text-indigo-300">
                  Review — click a skill to add, apply below before saving
                </p>
                <button
                  type="button"
                  onClick={() => setAnalysis(null)}
                  className="text-indigo-400 hover:text-indigo-600"
                >
                  <X size={14} />
                </button>
              </div>

              {analysis.issues?.length > 0 ? (
                <div className="mb-3">
                  <p className="mb-1 text-[11px] font-medium uppercase tracking-wide text-gray-500">
                    Things to check
                  </p>
                  <ul className="space-y-1.5">
                    {analysis.issues.map((issue, idx) => (
                      <li
                        key={idx}
                        className="flex items-start gap-2 rounded-lg bg-white/80 px-2.5 py-1.5 text-xs text-gray-700 dark:bg-gray-900/40 dark:text-gray-300"
                      >
                        <span
                          className={`mt-1 h-1.5 w-1.5 shrink-0 rounded-full ${
                            issue.severity === "high"
                              ? "bg-red-500"
                              : issue.severity === "medium"
                                ? "bg-amber-500"
                                : "bg-gray-400"
                          }`}
                        />
                        <span>
                          <span className="font-semibold capitalize text-gray-500">
                            {issue.category?.replace("_", " ")}:
                          </span>{" "}
                          {issue.message}
                        </span>
                      </li>
                    ))}
                  </ul>
                </div>
              ) : (
                <p className="mb-3 text-xs text-emerald-600 dark:text-emerald-400">
                  No issues found in the description
                </p>
              )}

              {analysis.required_skills?.length > 0 && (
                <div className="mb-2">
                  <p className="mb-1 text-[11px] font-medium uppercase tracking-wide text-gray-500">
                    Required
                  </p>
                  <div className="flex flex-wrap gap-1.5">
                    {analysis.required_skills.map((s) => {
                      const added = form.skills.includes(s);
                      return (
                        <button
                          type="button"
                          key={s}
                          disabled={added}
                          onClick={() => addSuggestedSkill(s)}
                          className={`flex items-center gap-1 rounded-full px-2.5 py-1.5 text-xs font-medium shadow-sm ring-1 ring-inset transition disabled:cursor-default ${
                            added
                              ? "bg-indigo-600 text-white ring-indigo-600 dark:bg-indigo-500 dark:ring-indigo-500"
                              : "bg-white text-gray-700 ring-indigo-200 hover:-translate-y-0.5 hover:bg-indigo-600 hover:text-white hover:ring-indigo-600 dark:bg-gray-900 dark:text-gray-300 dark:ring-indigo-800 dark:hover:bg-indigo-500 dark:hover:ring-indigo-500"
                          }`}
                        >
                          {added ? <Check size={11} /> : <Plus size={11} />}
                          {s}
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}

              {analysis.preferred_skills?.length > 0 && (
                <div className="mb-2">
                  <p className="mb-1 text-[11px] font-medium uppercase tracking-wide text-gray-500">
                    Preferred
                  </p>
                  <div className="flex flex-wrap gap-1.5">
                    {analysis.preferred_skills.map((s) => {
                      const added = form.skills.includes(s);
                      return (
                        <button
                          type="button"
                          key={s}
                          disabled={added}
                          onClick={() => addSuggestedSkill(s)}
                          className={`flex items-center gap-1 rounded-full px-2.5 py-1.5 text-xs font-medium shadow-sm ring-1 ring-inset transition disabled:cursor-default ${
                            added
                              ? "bg-gray-700 text-white ring-gray-700 dark:bg-gray-600 dark:ring-gray-600"
                              : "bg-white text-gray-700 ring-gray-200 hover:-translate-y-0.5 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800"
                          }`}
                        >
                          {added ? <Check size={11} /> : <Plus size={11} />}
                          {s}
                        </button>
                      );
                    })}
                  </div>
                </div>
              )}

              {(analysis.seniority_level || analysis.minimum_years_experience != null) && (
                <div className="flex items-center justify-between rounded-lg bg-white/70 px-2.5 py-1.5 text-xs text-gray-600 dark:bg-gray-900/40 dark:text-gray-300">
                  <span>
                    Suggested level: <strong>{analysis.seniority_level}</strong>
                    {analysis.minimum_years_experience != null && (
                      <>
                        {" "}
                        · min <strong>{analysis.minimum_years_experience}</strong> yrs
                      </>
                    )}
                  </span>
                  <button
                    type="button"
                    onClick={applySuggestedExperience}
                    className="font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                  >
                    Apply
                  </button>
                </div>
              )}

              {(analysis.salary_min != null || analysis.salary_max != null) && (
                <div className="mt-1.5 flex items-center justify-between rounded-lg bg-white/70 px-2.5 py-1.5 text-xs text-gray-600 dark:bg-gray-900/40 dark:text-gray-300">
                  <span>
                    Suggested salary:{" "}
                    <strong>
                      {analysis.salary_currency} {analysis.salary_min?.toLocaleString()}
                      {analysis.salary_max != null &&
                        ` – ${analysis.salary_max.toLocaleString()}`}
                    </strong>
                    {analysis.salary_type && <> · {analysis.salary_type}</>}
                  </span>
                  <button
                    type="button"
                    onClick={applySuggestedSalary}
                    className="font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                  >
                    Apply
                  </button>
                </div>
              )}

              {analysis.education && (
                <div className="mt-1.5 flex items-center justify-between rounded-lg bg-white/70 px-2.5 py-1.5 text-xs text-gray-600 dark:bg-gray-900/40 dark:text-gray-300">
                  <span>
                    Suggested education: <strong>{analysis.education}</strong>
                  </span>
                  <button
                    type="button"
                    onClick={applySuggestedEducation}
                    className="font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                  >
                    Apply
                  </button>
                </div>
              )}

              {analysis.roles_responsibility?.length > 0 && (
                <div className="mt-1.5 rounded-lg bg-white/70 px-2.5 py-1.5 text-xs text-gray-600 dark:bg-gray-900/40 dark:text-gray-300">
                  <div className="mb-1 flex items-center justify-between">
                    <span className="font-medium text-gray-500 dark:text-gray-400">
                      Suggested responsibilities
                    </span>
                    <button
                      type="button"
                      onClick={applySuggestedRoles}
                      className="font-semibold text-indigo-600 hover:underline dark:text-indigo-400"
                    >
                      Add below
                    </button>
                  </div>
                  <ul className="list-inside list-disc space-y-0.5">
                    {analysis.roles_responsibility.map((r, idx) => (
                      <li key={idx}>{r}</li>
                    ))}
                  </ul>
                </div>
              )}
            </div>
          )}

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
            <div className="rounded-xl border border-gray-200 bg-gray-50 p-3 transition focus-within:border-indigo-500 focus-within:bg-white focus-within:ring-4 focus-within:ring-indigo-50 dark:border-gray-700 dark:bg-gray-800 dark:focus-within:border-indigo-500 dark:focus-within:bg-gray-900 dark:focus-within:ring-indigo-950/50">
              {form.skills.length > 0 && (
                <div className="mb-2 flex flex-wrap gap-1.5">
                  {form.skills.map((s) => (
                    <span
                      key={s}
                      className="group flex items-center gap-1.5 rounded-full bg-white px-3 py-1.5 text-xs font-medium text-indigo-700 shadow-sm ring-1 ring-inset ring-indigo-100 transition hover:ring-indigo-300 dark:bg-gray-900 dark:text-indigo-300 dark:ring-indigo-900 dark:hover:ring-indigo-700"
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
                        className="flex h-3.5 w-3.5 items-center justify-center rounded-full text-indigo-300 transition group-hover:text-indigo-500 hover:!bg-indigo-100 hover:!text-indigo-700 dark:text-indigo-600 dark:group-hover:text-indigo-400 dark:hover:!bg-indigo-900/60 dark:hover:!text-indigo-300"
                      >
                        <X size={10} />
                      </button>
                    </span>
                  ))}
                </div>
              )}
              <div className="flex items-center gap-2">
                <Plus size={14} className="shrink-0 text-gray-400" />
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
                    className="shrink-0 rounded-lg bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
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
            options={EXP_LEVEL_OPTIONS}
            placeholder="— Select level —"
          />
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
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
              options={CURRENCY_OPTIONS}
            />
            <SelectField
              label="Salary Type"
              value={form.salary_type}
              onChange={set("salary_type")}
              options={SALARY_TYPE_OPTIONS}
            />
          </div>
        </Section>

        <Section
          title="Other Details"
          icon={Settings}
          iconColor="text-gray-500"
          iconBg="bg-gray-100 dark:bg-gray-800"
        >
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
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

        <div className="flex flex-wrap gap-3 pb-6">
          {isDraft ? (
            <>
              <button
                type="submit"
                onClick={() => { actionRef.current = "draft"; }}
                disabled={mutation.isPending}
                className="flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-60 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800"
              >
                <Save size={14} />
                {mutation.isPending && actionRef.current === "draft" ? "Saving…" : "Save Draft"}
              </button>
              <button
                type="submit"
                onClick={() => { actionRef.current = "publish"; }}
                disabled={mutation.isPending}
                className="flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
              >
                <Send size={14} />
                {mutation.isPending && actionRef.current === "publish" ? "Publishing…" : "Publish Job"}
              </button>
            </>
          ) : (
            <button
              type="submit"
              onClick={() => { actionRef.current = "save"; }}
              disabled={mutation.isPending}
              className="flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
            >
              <Save size={14} />
              {mutation.isPending ? "Saving…" : "Save Changes"}
            </button>
          )}
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
