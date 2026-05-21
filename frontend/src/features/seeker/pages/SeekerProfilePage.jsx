import { useState, useEffect, useRef } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import Loader from "@/components/common/Loader";
import {
  Plus,
  Trash2,
  Save,
  CheckCircle2,
  Circle,
  Pencil,
  Phone,
  MapPin,
  Briefcase,
  GraduationCap,
  UserCircle,
  X,
} from "lucide-react";
import DatePicker from "@/components/ui/DatePicker";
import YearPicker from "@/components/ui/YearPicker";

const COMPLETION_STEPS = [
  { key: "headline", label: "Headline", check: (p) => !!p?.headline },
  { key: "phone", label: "Phone", check: (p) => !!p?.phone },
  { key: "city", label: "City", check: (p) => !!p?.city },
  { key: "summary", label: "Summary", check: (p) => !!p?.summary },
  { key: "skills", label: "Skills", check: (p) => p?.skills?.length > 0 },
  {
    key: "experience",
    label: "Experience",
    check: (p) => p?.experiences?.length > 0,
  },
  {
    key: "education",
    label: "Education",
    check: (p) => p?.education?.length > 0,
  },
];

function ProfileCompletion({ profile }) {
  const done = COMPLETION_STEPS.filter((s) => s.check(profile)).length;
  const pct = Math.round((done / COMPLETION_STEPS.length) * 100);
  const bar =
    pct >= 80 ? "bg-green-500" : pct >= 50 ? "bg-indigo-500" : "bg-amber-400";
  const col =
    pct >= 80
      ? "text-green-600 dark:text-green-400"
      : pct >= 50
        ? "text-indigo-600 dark:text-indigo-400"
        : "text-amber-500";
  const lbl =
    pct >= 80
      ? "Looking great!"
      : pct >= 50
        ? "Good progress"
        : "Just getting started";

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-center justify-between gap-4">
        <div>
          <p className="text-sm font-semibold text-gray-900 dark:text-white">
            Profile Completion
          </p>
          <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
            {lbl} · {done}/{COMPLETION_STEPS.length} steps done
          </p>
        </div>
        <span className={`text-2xl font-bold tabular-nums ${col}`}>{pct}%</span>
      </div>

      <div className="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-800">
        <div
          className={`h-full rounded-full transition-all duration-700 ${bar}`}
          style={{ width: `${pct}%` }}
        />
      </div>

      <div className="mt-4 flex flex-wrap gap-2">
        {COMPLETION_STEPS.map((s) => {
          const ok = s.check(profile);
          return ok ? (
            <span
              key={s.key}
              className="flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 dark:bg-green-900/20 dark:text-green-400"
            >
              <CheckCircle2 size={10} /> {s.label}
            </span>
          ) : (
            <span
              key={s.key}
              className="flex items-center gap-1 rounded-full border border-dashed border-gray-300 px-2.5 py-1 text-xs text-gray-400 dark:border-gray-700 dark:text-gray-500"
            >
              <Circle size={10} /> {s.label}
            </span>
          );
        })}
      </div>
    </div>
  );
}

export default function SeekerProfilePage() {
  const queryClient = useQueryClient();

  const { data: profile, isLoading } = useQuery({
    queryKey: ["seeker-profile"],
    queryFn: () => seekerApi.getProfile().then((r) => r.data?.data),
  });

  const invalidate = () =>
    queryClient.invalidateQueries({ queryKey: ["seeker-profile"] });

  const updateMutation = useMutation({
    mutationFn: (p) => seekerApi.updateProfile(p),
    onSuccess: () => {
      invalidate();
      toast.success("Profile saved");
    },
    onError: () => toast.error("Failed to save profile"),
  });
  const addExpMutation = useMutation({
    mutationFn: (p) => seekerApi.addExperience(p),
    onSuccess: () => {
      invalidate();
      toast.success("Experience added");
    },
    onError: () => toast.error("Failed to add experience"),
  });
  const updateExpMutation = useMutation({
    mutationFn: (p) => seekerApi.updateExperience(p),
    onSuccess: () => {
      invalidate();
      toast.success("Experience updated");
    },
    onError: () => toast.error("Failed to update experience"),
  });
  const delExpMutation = useMutation({
    mutationFn: (id) => seekerApi.deleteExperience({ experience_id: id }),
    onSuccess: () => {
      invalidate();
      toast.success("Experience removed");
    },
    onError: () => toast.error("Failed to remove experience"),
  });
  const addEduMutation = useMutation({
    mutationFn: (p) => seekerApi.addEducation(p),
    onSuccess: () => {
      invalidate();
      toast.success("Education added");
    },
    onError: () => toast.error("Failed to add education"),
  });
  const updateEduMutation = useMutation({
    mutationFn: (p) => seekerApi.updateEducation(p),
    onSuccess: () => {
      invalidate();
      toast.success("Education updated");
    },
    onError: () => toast.error("Failed to update education"),
  });
  const delEduMutation = useMutation({
    mutationFn: (id) => seekerApi.deleteEducation({ education_id: id }),
    onSuccess: () => {
      invalidate();
      toast.success("Education removed");
    },
    onError: () => toast.error("Failed to remove education"),
  });

  if (isLoading) return <Loader />;

  return (
    <div className="mx-auto max-w-3xl space-y-5">
      <div>
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">
          My Profile
        </h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Keep your profile updated to get the best job matches
        </p>
      </div>

      <ProfileCompletion profile={profile} />

      <BasicInfoSection
        profile={profile}
        onSave={(p) => updateMutation.mutate(p)}
        saving={updateMutation.isPending}
      />

      <ExperienceSection
        experiences={profile?.experiences ?? []}
        onAdd={(p) => addExpMutation.mutate(p)}
        onUpdate={(p) => updateExpMutation.mutate(p)}
        onDelete={(id) => delExpMutation.mutate(id)}
        loading={
          addExpMutation.isPending ||
          updateExpMutation.isPending ||
          delExpMutation.isPending
        }
      />

      <EducationSection
        educations={profile?.education ?? []}
        onAdd={(p) => addEduMutation.mutate(p)}
        onUpdate={(p) => updateEduMutation.mutate(p)}
        onDelete={(id) => delEduMutation.mutate(id)}
        loading={
          addEduMutation.isPending ||
          updateEduMutation.isPending ||
          delEduMutation.isPending
        }
      />
    </div>
  );
}

/* ─── Basic Info ─────────────────────────────────────────────── */

function BasicInfoSection({ profile, onSave, saving }) {
  const [form, setForm] = useState(blankBasic(profile));
  const [skillInput, setSkillInput] = useState("");

  useEffect(() => {
    if (profile) setForm(blankBasic(profile));
  }, [profile]);

  function blankBasic(p) {
    return {
      headline: p?.headline ?? "",
      phone: p?.phone ?? "",
      city: p?.city ?? "",
      summary: p?.summary ?? "",
      skills: Array.isArray(p?.skills) ? p.skills : [],
    };
  }

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const addSkill = () => {
    const v = skillInput.trim();
    if (v && !form.skills.includes(v))
      setForm((f) => ({ ...f, skills: [...f.skills, v] }));
    setSkillInput("");
  };

  const removeSkill = (s) =>
    setForm((f) => ({ ...f, skills: f.skills.filter((x) => x !== s) }));

  const handleSubmit = (e) => {
    e.preventDefault();
    onSave(form);
  };

  return (
    <Section
      title="Basic Information"
      icon={UserCircle}
      iconColor="text-indigo-600"
      iconBg="bg-indigo-50 dark:bg-indigo-900/30"
    >
      <form onSubmit={handleSubmit} className="space-y-5">
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Professional Headline">
            <input
              value={form.headline}
              onChange={set("headline")}
              placeholder="e.g. Senior React Developer"
              {...inp}
            />
          </Field>
          <Field label="Phone">
            <div className="relative">
              <Phone
                size={14}
                className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
              />
              <input
                value={form.phone}
                onChange={set("phone")}
                placeholder="+91 98765 43210"
                className={`${inp.className} pl-8`}
              />
            </div>
          </Field>
        </div>

        <Field label="City">
          <div className="relative">
            <MapPin
              size={14}
              className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
            />
            <input
              value={form.city}
              onChange={set("city")}
              placeholder="e.g. Bangalore"
              className={`${inp.className} pl-8`}
            />
          </div>
        </Field>

        <Field label="Professional Summary">
          <textarea
            rows={3}
            value={form.summary}
            onChange={set("summary")}
            placeholder="Tell recruiters about yourself…"
            {...inp}
          />
        </Field>

        <Field label="Skills">
          <div className="rounded-xl border border-gray-300 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
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
                      onClick={() => removeSkill(s)}
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
                  if (e.key === "Enter") {
                    e.preventDefault();
                    addSkill();
                  }
                  if (e.key === ",") {
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

        <button
          type="submit"
          disabled={saving}
          className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60"
        >
          <Save size={14} />
          {saving ? "Saving…" : "Save Profile"}
        </button>
      </form>
    </Section>
  );
}

/* ─── Experience ─────────────────────────────────────────────── */

const EMPTY_EXP = {
  company_name: "",
  job_title: "",
  start_date: "",
  end_date: "",
  is_current: false,
  description: "",
};

function ExperienceSection({
  experiences,
  onAdd,
  onUpdate,
  onDelete,
  loading,
}) {
  const [form, setForm] = useState(EMPTY_EXP);
  const [editingId, setEditId] = useState(null);
  const [showForm, setShow] = useState(false);
  const formRef = useRef(null);

  useEffect(() => {
    if (showForm && formRef.current)
      formRef.current.scrollIntoView({ behavior: "smooth", block: "nearest" });
  }, [showForm, editingId]);

  const set = (k) => (e) =>
    setForm((f) => ({
      ...f,
      [k]: e.target.type === "checkbox" ? e.target.checked : e.target.value,
    }));
  const toDate = (v) => (v ? v.slice(0, 10) : "");
  const fmtDate = (v) => {
    if (!v) return "";
    const [y, m] = v.slice(0, 10).split("-").map(Number);
    return `${["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][m - 1]} ${y}`;
  };

  const openAdd = () => {
    setForm(EMPTY_EXP);
    setEditId(null);
    setShow(true);
  };
  const openEdit = (exp) => {
    setForm({
      company_name: exp.company_name ?? "",
      job_title: exp.job_title ?? "",
      start_date: toDate(exp.start_date),
      end_date: toDate(exp.end_date),
      is_current: !!exp.is_current,
      description: exp.description ?? "",
    });
    setEditId(exp.id);
    setShow(true);
  };
  const close = () => {
    setShow(false);
    setEditId(null);
    setForm(EMPTY_EXP);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    editingId ? onUpdate({ ...form, experience_id: editingId }) : onAdd(form);
    close();
  };

  return (
    <Section
      title="Work Experience"
      icon={Briefcase}
      iconColor="text-indigo-600"
      iconBg="bg-indigo-50 dark:bg-indigo-900/30"
      count={experiences.length}
    >
      <div className="space-y-3">
        {experiences.map((exp) => (
          <div
            key={exp.id}
            className="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50"
          >
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
              {exp.company_name?.charAt(0)?.toUpperCase() ?? "?"}
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-start justify-between gap-2">
                <div>
                  <p className="font-semibold text-gray-900 dark:text-white">
                    {exp.job_title}
                  </p>
                  <p className="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                    {exp.company_name}
                  </p>
                  <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                    {fmtDate(exp.start_date)} –{" "}
                    {exp.is_current ? "Present" : fmtDate(exp.end_date)}
                  </p>
                </div>
                <div className="flex shrink-0 gap-1">
                  <IconBtn onClick={() => openEdit(exp)}>
                    <Pencil size={13} />
                  </IconBtn>
                  <IconBtn
                    onClick={() => onDelete(exp.id)}
                    disabled={loading}
                    danger
                  >
                    <Trash2 size={13} />
                  </IconBtn>
                </div>
              </div>
              {exp.description && (
                <p className="mt-2 text-sm text-gray-500 line-clamp-2 dark:text-gray-400">
                  {exp.description}
                </p>
              )}
            </div>
          </div>
        ))}
      </div>

      {showForm ? (
        <form
          ref={formRef}
          onSubmit={handleSubmit}
          className="mt-4 space-y-4 rounded-xl border border-indigo-100 bg-indigo-50/40 p-4 dark:border-indigo-900/50 dark:bg-indigo-950/20"
        >
          <p className="text-xs font-semibold uppercase tracking-wide text-indigo-500 dark:text-indigo-400">
            {editingId ? "Edit Experience" : "Add Experience"}
          </p>
          <div className="grid grid-cols-2 gap-3">
            <Field label="Company">
              <input
                required
                value={form.company_name}
                onChange={set("company_name")}
                {...inp}
              />
            </Field>
            <Field label="Job Title">
              <input
                required
                value={form.job_title}
                onChange={set("job_title")}
                {...inp}
              />
            </Field>
          </div>
          <div className="grid grid-cols-2 gap-3">
            <Field label="Start Date">
              <DatePicker
                value={form.start_date}
                onChange={(v) => setForm((f) => ({ ...f, start_date: v }))}
                placeholder="Start date"
              />
            </Field>
            <Field label="End Date">
              <DatePicker
                value={form.end_date}
                onChange={(v) => setForm((f) => ({ ...f, end_date: v }))}
                placeholder="End date"
                disabled={form.is_current}
              />
            </Field>
          </div>
          <label className="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300 cursor-pointer">
            <input
              type="checkbox"
              checked={form.is_current}
              onChange={set("is_current")}
              className="accent-indigo-600"
            />
            Currently working here
          </label>
          <Field label="Description">
            <textarea
              rows={2}
              value={form.description}
              onChange={set("description")}
              {...inp}
            />
          </Field>
          <FormActions
            onCancel={close}
            loading={loading}
            editing={!!editingId}
          />
        </form>
      ) : (
        <AddButton onClick={openAdd} label="Add Experience" />
      )}
    </Section>
  );
}

/* ─── Education ──────────────────────────────────────────────── */

const EMPTY_EDU = {
  institution: "",
  degree: "",
  field_of_study: "",
  start_year: "",
  end_year: "",
};

function EducationSection({ educations, onAdd, onUpdate, onDelete, loading }) {
  const [form, setForm] = useState(EMPTY_EDU);
  const [editingId, setEditId] = useState(null);
  const [showForm, setShow] = useState(false);

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));
  const openAdd = () => {
    setForm(EMPTY_EDU);
    setEditId(null);
    setShow(true);
  };
  const openEdit = (edu) => {
    setForm({
      institution: edu.institution ?? "",
      degree: edu.degree ?? "",
      field_of_study: edu.field_of_study ?? "",
      start_year: edu.start_year ?? "",
      end_year: edu.end_year ?? "",
    });
    setEditId(edu.id);
    setShow(true);
  };
  const close = () => {
    setShow(false);
    setEditId(null);
    setForm(EMPTY_EDU);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    editingId ? onUpdate({ ...form, education_id: editingId }) : onAdd(form);
    close();
  };

  return (
    <Section
      title="Education"
      icon={GraduationCap}
      iconColor="text-violet-600"
      iconBg="bg-violet-50 dark:bg-violet-900/30"
      count={educations.length}
    >
      <div className="space-y-3">
        {educations.map((edu) => (
          <div
            key={edu.id}
            className="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50"
          >
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-sm font-bold text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
              {edu.institution?.charAt(0)?.toUpperCase() ?? "?"}
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-start justify-between gap-2">
                <div>
                  <p className="font-semibold text-gray-900 dark:text-white">
                    {edu.degree}
                  </p>
                  <p className="text-sm font-medium text-violet-600 dark:text-violet-400">
                    {edu.institution}
                  </p>
                  <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                    {edu.field_of_study && <>{edu.field_of_study} · </>}
                    {edu.start_year} – {edu.end_year}
                  </p>
                </div>
                <div className="flex shrink-0 gap-1">
                  <IconBtn onClick={() => openEdit(edu)}>
                    <Pencil size={13} />
                  </IconBtn>
                  <IconBtn
                    onClick={() => onDelete(edu.id)}
                    disabled={loading}
                    danger
                  >
                    <Trash2 size={13} />
                  </IconBtn>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>

      {showForm ? (
        <form
          onSubmit={handleSubmit}
          className="mt-4 space-y-4 rounded-xl border border-violet-100 bg-violet-50/40 p-4 dark:border-violet-900/50 dark:bg-violet-950/20"
        >
          <p className="text-xs font-semibold uppercase tracking-wide text-violet-500 dark:text-violet-400">
            {editingId ? "Edit Education" : "Add Education"}
          </p>
          <div className="grid grid-cols-2 gap-3">
            <Field label="Institution">
              <input
                required
                value={form.institution}
                onChange={set("institution")}
                {...inp}
              />
            </Field>
            <Field label="Degree">
              <input
                required
                value={form.degree}
                onChange={set("degree")}
                {...inp}
              />
            </Field>
          </div>
          <div className="grid grid-cols-2 gap-3">
            <Field label="Field of Study">
              <input
                value={form.field_of_study}
                onChange={set("field_of_study")}
                {...inp}
              />
            </Field>
            <div className="grid grid-cols-2 gap-2">
              <Field label="From">
                <YearPicker
                  value={form.start_year}
                  onChange={(v) => setForm((f) => ({ ...f, start_year: v }))}
                  placeholder="Start year"
                />
              </Field>
              <Field label="To">
                <YearPicker
                  value={form.end_year}
                  onChange={(v) => setForm((f) => ({ ...f, end_year: v }))}
                  placeholder="End year"
                />
              </Field>
            </div>
          </div>
          <FormActions
            onCancel={close}
            loading={loading}
            editing={!!editingId}
          />
        </form>
      ) : (
        <AddButton onClick={openAdd} label="Add Education" />
      )}
    </Section>
  );
}

/* ─── Shared primitives ──────────────────────────────────────── */

const inp = {
  className:
    "w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-indigo-500 dark:focus:ring-indigo-900/40",
};

function Section({ title, icon: Icon, iconColor, iconBg, count, children }) {
  return (
    <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-gray-800">
        {Icon && (
          <div
            className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${iconBg}`}
          >
            <Icon size={15} className={iconColor} />
          </div>
        )}
        <h2 className="flex-1 text-sm font-semibold text-gray-900 dark:text-white">
          {title}
        </h2>
        {count > 0 && (
          <span className="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
            {count}
          </span>
        )}
      </div>
      <div className="p-6">{children}</div>
    </div>
  );
}

function Field({ label, children }) {
  return (
    <div className="flex flex-col gap-1.5">
      <label className="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
        {label}
      </label>
      {children}
    </div>
  );
}

function IconBtn({ onClick, disabled, danger, children }) {
  return (
    <button
      type="button"
      onClick={onClick}
      disabled={disabled}
      className={`rounded-lg p-1.5 transition disabled:opacity-40 ${danger ? "text-gray-400 hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-900/20 dark:hover:text-red-400" : "text-gray-400 hover:bg-gray-200 hover:text-indigo-600 dark:hover:bg-gray-700 dark:hover:text-indigo-400"}`}
    >
      {children}
    </button>
  );
}

function AddButton({ onClick, label }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="mt-3 flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-200 py-3 text-sm font-medium text-gray-400 transition hover:border-indigo-300 hover:text-indigo-600 dark:border-gray-700 dark:hover:border-indigo-700 dark:hover:text-indigo-400"
    >
      <Plus size={15} /> {label}
    </button>
  );
}

function FormActions({ onCancel, loading, editing }) {
  return (
    <div className="flex items-center gap-2 pt-1">
      <button
        type="submit"
        disabled={loading}
        className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60"
      >
        {editing ? "Update" : "Add"}
      </button>
      <button
        type="button"
        onClick={onCancel}
        className="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
      >
        Cancel
      </button>
    </div>
  );
}
