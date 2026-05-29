import { useState, useEffect, useRef } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import Loader from "@/components/common/Loader";
import {
  Plus,
  Trash2,
  Save,
  FileText,
  Upload,
  CheckCircle2,
  Circle,
  Pencil,
  Phone,
  MapPin,
  Briefcase,
  GraduationCap,
  UserCircle,
  X,
  Building2,
  SlidersHorizontal,
  Link2,
  ChevronDown,
} from "lucide-react";
import DatePicker from "@/components/ui/DatePicker";
import YearPicker from "@/components/ui/YearPicker";

const CURRENCIES = ["INR", "USD", "EUR", "GBP"];

const JOB_TYPES = [
  { value: "FULL_TIME", label: "Full-time" },
  { value: "PART_TIME", label: "Part-time" },
  { value: "CONTRACT", label: "Contract" },
  { value: "INTERNSHIP", label: "Internship" },
  { value: "FREELANCE", label: "Freelance" },
];

const WORK_MODES = [
  { value: "REMOTE", label: "Remote" },
  { value: "ONSITE", label: "On-site" },
  { value: "HYBRID", label: "Hybrid" },
];

const EMP_TYPE_LABEL = Object.fromEntries(JOB_TYPES.map((t) => [t.value, t.label]));
const WORK_MODE_LABEL = Object.fromEntries(WORK_MODES.map((m) => [m.value, m.label]));

const COMPLETION_STEPS = [
  { key: "headline", label: "Headline", check: (p) => !!p?.headline },
  { key: "phone", label: "Phone", check: (p) => !!p?.phone },
  { key: "city", label: "City", check: (p) => !!p?.city },
  { key: "summary", label: "Summary", check: (p) => !!p?.summary },
  { key: "skills", label: "Skills", check: (p) => p?.skills?.length > 0 },
  { key: "resume", label: "Resume", check: (p) => !!p?.resume_filename },
  { key: "experience", label: "Experience", check: (p) => p?.experiences?.length > 0 },
  { key: "education", label: "Education", check: (p) => p?.education?.length > 0 },
];

function ProfileCompletion({ profile }) {
  const done = COMPLETION_STEPS.filter((s) => s.check(profile)).length;
  const pct = Math.round((done / COMPLETION_STEPS.length) * 100);
  const bar = pct >= 80 ? "bg-green-500" : pct >= 50 ? "bg-indigo-500" : "bg-amber-400";
  const col =
    pct >= 80
      ? "text-green-600 dark:text-green-400"
      : pct >= 50
        ? "text-indigo-600 dark:text-indigo-400"
        : "text-amber-500";
  const lbl =
    pct >= 80 ? "Looking great!" : pct >= 50 ? "Good progress" : "Just getting started";

  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-center justify-between gap-4">
        <div>
          <p className="text-sm font-semibold text-gray-900 dark:text-white">Profile Completion</p>
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

  const invalidate = () => queryClient.invalidateQueries({ queryKey: ["seeker-profile"] });

  const updateMutation = useMutation({
    mutationFn: (p) => seekerApi.updateProfile(p),
    onSuccess: () => { invalidate(); toast.success("Profile saved"); },
    onError: () => toast.error("Failed to save profile"),
  });
  const uploadMutation = useMutation({
    mutationFn: (fd) => seekerApi.uploadResume(fd),
    onSuccess: () => { invalidate(); toast.success("Resume uploaded"); },
    onError: () => toast.error("Failed to upload resume"),
  });
  const addExpMutation = useMutation({
    mutationFn: (p) => seekerApi.addExperience(p),
    onSuccess: () => { invalidate(); toast.success("Experience added"); },
    onError: () => toast.error("Failed to add experience"),
  });
  const updateExpMutation = useMutation({
    mutationFn: (p) => seekerApi.updateExperience(p),
    onSuccess: () => { invalidate(); toast.success("Experience updated"); },
    onError: () => toast.error("Failed to update experience"),
  });
  const delExpMutation = useMutation({
    mutationFn: (id) => seekerApi.deleteExperience({ experience_id: id }),
    onSuccess: () => { invalidate(); toast.success("Experience removed"); },
    onError: () => toast.error("Failed to remove experience"),
  });
  const addEduMutation = useMutation({
    mutationFn: (p) => seekerApi.addEducation(p),
    onSuccess: () => { invalidate(); toast.success("Education added"); },
    onError: () => toast.error("Failed to add education"),
  });
  const updateEduMutation = useMutation({
    mutationFn: (p) => seekerApi.updateEducation(p),
    onSuccess: () => { invalidate(); toast.success("Education updated"); },
    onError: () => toast.error("Failed to update education"),
  });
  const delEduMutation = useMutation({
    mutationFn: (id) => seekerApi.deleteEducation({ education_id: id }),
    onSuccess: () => { invalidate(); toast.success("Education removed"); },
    onError: () => toast.error("Failed to remove education"),
  });

  if (isLoading) return <Loader />;

  const saving = updateMutation.isPending;

  return (
    <div className="mx-auto max-w-3xl space-y-5">
      <div>
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">My Profile</h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Keep your profile updated to get the best job matches
        </p>
      </div>

      <ProfileCompletion profile={profile} />

      <BasicInfoSection profile={profile} onSave={(p) => updateMutation.mutate(p)} saving={saving} />
      <ProfessionalSection profile={profile} onSave={(p) => updateMutation.mutate(p)} saving={saving} />
      <PreferencesSection profile={profile} onSave={(p) => updateMutation.mutate(p)} saving={saving} />

      <ResumeSection
        profile={profile}
        onUpload={(fd) => uploadMutation.mutate(fd)}
        uploading={uploadMutation.isPending}
      />

      <ExperienceSection
        experiences={profile?.experiences ?? []}
        onAdd={(p) => addExpMutation.mutate(p)}
        onUpdate={(p) => updateExpMutation.mutate(p)}
        onDelete={(id) => delExpMutation.mutate(id)}
        loading={addExpMutation.isPending || updateExpMutation.isPending || delExpMutation.isPending}
      />

      <EducationSection
        educations={profile?.education ?? []}
        onAdd={(p) => addEduMutation.mutate(p)}
        onUpdate={(p) => updateEduMutation.mutate(p)}
        onDelete={(id) => delEduMutation.mutate(id)}
        loading={addEduMutation.isPending || updateEduMutation.isPending || delEduMutation.isPending}
      />
    </div>
  );
}

/* ─── Basic Info ─────────────────────────────────────────────── */

function blankBasic(p) {
  return {
    headline: p?.headline ?? "",
    phone: p?.phone ?? "",
    date_of_birth: p?.date_of_birth ? p.date_of_birth.slice(0, 10) : "",
    gender: p?.gender ?? "",
    city: p?.city ?? "",
    state: p?.state ?? "",
    country: p?.country ?? "",
    pincode: p?.pincode ?? "",
    summary: p?.summary ?? "",
    skills: Array.isArray(p?.skills) ? p.skills : [],
  };
}

function BasicInfoSection({ profile, onSave, saving }) {
  const [form, setForm] = useState(blankBasic(profile));
  const [skillInput, setSkillInput] = useState("");

  useEffect(() => { if (profile) setForm(blankBasic(profile)); }, [profile]);

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const addSkill = () => {
    const v = skillInput.trim();
    if (v && !form.skills.includes(v)) setForm((f) => ({ ...f, skills: [...f.skills, v] }));
    setSkillInput("");
  };
  const removeSkill = (s) => setForm((f) => ({ ...f, skills: f.skills.filter((x) => x !== s) }));

  const handleSubmit = (e) => { e.preventDefault(); onSave(form); };

  return (
    <Section title="Basic Information" icon={UserCircle} iconColor="text-indigo-600" iconBg="bg-indigo-50 dark:bg-indigo-900/30">
      <form onSubmit={handleSubmit} className="space-y-5">
        {/* Personal */}
        <GroupLabel>Personal</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Professional Headline">
            <input value={form.headline} onChange={set("headline")} placeholder="e.g. Senior React Developer" {...inp} />
          </Field>
          <Field label="Phone">
            <div className="relative">
              <Phone size={14} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={form.phone} onChange={set("phone")} placeholder="+91 98765 43210" className={`${inp.className} pl-8`} />
            </div>
          </Field>
          <Field label="Date of Birth">
            <DatePicker
              value={form.date_of_birth}
              onChange={(v) => setForm((f) => ({ ...f, date_of_birth: v }))}
              placeholder="Pick date of birth"
            />
          </Field>
          <Field label="Gender">
            <StyledSelect value={form.gender} onChange={set("gender")}>
              <option value="">Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
              <option value="prefer_not_to_say">Prefer not to say</option>
            </StyledSelect>
          </Field>
        </div>

        {/* Location */}
        <GroupLabel>Location</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="City">
            <div className="relative">
              <MapPin size={14} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={form.city} onChange={set("city")} placeholder="Bangalore" className={`${inp.className} pl-8`} />
            </div>
          </Field>
          <Field label="State">
            <input value={form.state} onChange={set("state")} placeholder="Karnataka" {...inp} />
          </Field>
          <Field label="Country">
            <input value={form.country} onChange={set("country")} placeholder="India" {...inp} />
          </Field>
          <Field label="Pincode">
            <input value={form.pincode} onChange={set("pincode")} placeholder="560001" {...inp} />
          </Field>
        </div>

        {/* Summary */}
        <GroupLabel>Summary</GroupLabel>
        <Field label="Professional Summary">
          <textarea rows={3} value={form.summary} onChange={set("summary")} placeholder="Tell recruiters about yourself…" {...inp} />
        </Field>

        {/* Skills */}
        <GroupLabel>Skills</GroupLabel>
        <Field label="Skills">
          <div className="rounded-xl border border-gray-300 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
            {form.skills.length > 0 && (
              <div className="mb-2 flex flex-wrap gap-1.5">
                {form.skills.map((s) => (
                  <span key={s} className="flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300">
                    {s}
                    <button type="button" onClick={() => removeSkill(s)} className="ml-0.5 text-indigo-400 hover:text-indigo-600">
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
                  if (e.key === "Enter") { e.preventDefault(); addSkill(); }
                  if (e.key === ",") { e.preventDefault(); addSkill(); }
                }}
                placeholder="Type a skill and press Enter"
                className="min-w-0 flex-1 bg-transparent text-sm text-gray-700 outline-none placeholder:text-gray-400 dark:text-gray-300"
              />
              {skillInput.trim() && (
                <button type="button" onClick={addSkill} className="shrink-0 rounded-lg bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-indigo-700">
                  Add
                </button>
              )}
            </div>
          </div>
          <p className="mt-1 text-xs text-gray-400">Press Enter or comma to add each skill</p>
        </Field>

        <button type="submit" disabled={saving} className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">
          <Save size={14} />
          {saving ? "Saving…" : "Save"}
        </button>
      </form>
    </Section>
  );
}

/* ─── Professional ───────────────────────────────────────────── */

function blankProfessional(p) {
  return {
    current_job_title: p?.current_job_title ?? "",
    current_company: p?.current_company ?? "",
    total_experience_years: p?.total_experience_years ?? "",
    total_experience_months: p?.total_experience_months ?? "",
    linkedin_url: p?.linkedin_url ?? "",
    github_url: p?.github_url ?? "",
    portfolio_url: p?.portfolio_url ?? "",
  };
}

function ProfessionalSection({ profile, onSave, saving }) {
  const [form, setForm] = useState(blankProfessional(profile));

  useEffect(() => { if (profile) setForm(blankProfessional(profile)); }, [profile]);

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));

  const handleSubmit = (e) => { e.preventDefault(); onSave(form); };

  return (
    <Section title="Professional Info" icon={Building2} iconColor="text-sky-600" iconBg="bg-sky-50 dark:bg-sky-900/30">
      <form onSubmit={handleSubmit} className="space-y-5">
        <GroupLabel>Current Role</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Current Job Title">
            <input value={form.current_job_title} onChange={set("current_job_title")} placeholder="e.g. Software Engineer" {...inp} />
          </Field>
          <Field label="Current Company">
            <input value={form.current_company} onChange={set("current_company")} placeholder="e.g. Acme Corp" {...inp} />
          </Field>
        </div>

        <GroupLabel>Experience</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Years of Experience">
            <input type="number" min="0" max="50" value={form.total_experience_years} onChange={set("total_experience_years")} placeholder="3" {...inp} />
          </Field>
          <Field label="Months">
            <input type="number" min="0" max="11" value={form.total_experience_months} onChange={set("total_experience_months")} placeholder="6" {...inp} />
          </Field>
        </div>

        <GroupLabel>Online Presence</GroupLabel>
        <div className="space-y-3">
          <Field label="LinkedIn URL">
            <div className="relative">
              <Link2 size={14} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={form.linkedin_url} onChange={set("linkedin_url")} placeholder="https://linkedin.com/in/yourname" className={`${inp.className} pl-8`} />
            </div>
          </Field>
          <Field label="GitHub URL">
            <div className="relative">
              <Link2 size={14} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={form.github_url} onChange={set("github_url")} placeholder="https://github.com/yourname" className={`${inp.className} pl-8`} />
            </div>
          </Field>
          <Field label="Portfolio URL">
            <div className="relative">
              <Link2 size={14} className="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
              <input value={form.portfolio_url} onChange={set("portfolio_url")} placeholder="https://yourportfolio.com" className={`${inp.className} pl-8`} />
            </div>
          </Field>
        </div>

        <button type="submit" disabled={saving} className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">
          <Save size={14} />
          {saving ? "Saving…" : "Save"}
        </button>
      </form>
    </Section>
  );
}

/* ─── Preferences ────────────────────────────────────────────── */

function blankPreferences(p) {
  return {
    current_salary: p?.current_salary ?? "",
    current_salary_currency: p?.current_salary_currency ?? "INR",
    expected_salary: p?.expected_salary ?? "",
    expected_salary_currency: p?.expected_salary_currency ?? "INR",
    preferred_job_types: Array.isArray(p?.preferred_job_types) ? p.preferred_job_types : [],
    preferred_work_modes: Array.isArray(p?.preferred_work_modes) ? p.preferred_work_modes : [],
    preferred_locations: Array.isArray(p?.preferred_locations) ? p.preferred_locations : [],
    notice_period: p?.notice_period ?? "",
    immediate_joiner: p?.immediate_joiner ?? false,
    is_public: p?.is_public ?? true,
    open_to_opportunities: p?.open_to_opportunities ?? true,
  };
}

function toggleItem(arr, val) {
  return arr.includes(val) ? arr.filter((x) => x !== val) : [...arr, val];
}

function PreferencesSection({ profile, onSave, saving }) {
  const [form, setForm] = useState(blankPreferences(profile));
  const [locInput, setLocInput] = useState("");

  useEffect(() => { if (profile) setForm(blankPreferences(profile)); }, [profile]);

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));
  const setCheck = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.checked }));
  const setBool = (k, v) => setForm((f) => ({ ...f, [k]: v }));

  const addLoc = () => {
    const v = locInput.trim();
    if (v && !form.preferred_locations.includes(v))
      setForm((f) => ({ ...f, preferred_locations: [...f.preferred_locations, v] }));
    setLocInput("");
  };
  const removeLoc = (l) => setForm((f) => ({ ...f, preferred_locations: f.preferred_locations.filter((x) => x !== l) }));

  const handleSubmit = (e) => { e.preventDefault(); onSave(form); };

  return (
    <Section title="Preferences & Availability" icon={SlidersHorizontal} iconColor="text-purple-600" iconBg="bg-purple-50 dark:bg-purple-900/30">
      <form onSubmit={handleSubmit} className="space-y-5">
        {/* Salary */}
        <GroupLabel>Salary</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Current Salary">
            <div className="flex gap-2">
              <StyledSelect value={form.current_salary_currency} onChange={set("current_salary_currency")} wrapperClassName="w-24 shrink-0">
                {CURRENCIES.map((c) => <option key={c}>{c}</option>)}
              </StyledSelect>
              <input type="number" min="0" value={form.current_salary} onChange={set("current_salary")} placeholder="e.g. 800000" {...inp} />
            </div>
          </Field>
          <Field label="Expected Salary">
            <div className="flex gap-2">
              <StyledSelect value={form.expected_salary_currency} onChange={set("expected_salary_currency")} wrapperClassName="w-24 shrink-0">
                {CURRENCIES.map((c) => <option key={c}>{c}</option>)}
              </StyledSelect>
              <input type="number" min="0" value={form.expected_salary} onChange={set("expected_salary")} placeholder="e.g. 1200000" {...inp} />
            </div>
          </Field>
        </div>

        {/* Job type */}
        <GroupLabel>Preferred Job Types</GroupLabel>
        <div className="flex flex-wrap gap-2">
          {JOB_TYPES.map((t) => {
            const active = form.preferred_job_types.includes(t.value);
            return (
              <button
                key={t.value}
                type="button"
                onClick={() => setForm((f) => ({ ...f, preferred_job_types: toggleItem(f.preferred_job_types, t.value) }))}
                className={`rounded-full border px-3.5 py-1.5 text-xs font-medium transition ${active ? "border-indigo-600 bg-indigo-600 text-white" : "border-gray-300 text-gray-600 hover:border-indigo-400 dark:border-gray-700 dark:text-gray-400"}`}
              >
                {t.label}
              </button>
            );
          })}
        </div>

        {/* Work mode */}
        <GroupLabel>Preferred Work Modes</GroupLabel>
        <div className="flex flex-wrap gap-2">
          {WORK_MODES.map((m) => {
            const active = form.preferred_work_modes.includes(m.value);
            return (
              <button
                key={m.value}
                type="button"
                onClick={() => setForm((f) => ({ ...f, preferred_work_modes: toggleItem(f.preferred_work_modes, m.value) }))}
                className={`rounded-full border px-3.5 py-1.5 text-xs font-medium transition ${active ? "border-indigo-600 bg-indigo-600 text-white" : "border-gray-300 text-gray-600 hover:border-indigo-400 dark:border-gray-700 dark:text-gray-400"}`}
              >
                {m.label}
              </button>
            );
          })}
        </div>

        {/* Preferred locations */}
        <GroupLabel>Preferred Locations</GroupLabel>
        <div className="rounded-xl border border-gray-300 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
          {form.preferred_locations.length > 0 && (
            <div className="mb-2 flex flex-wrap gap-1.5">
              {form.preferred_locations.map((l) => (
                <span key={l} className="flex items-center gap-1 rounded-full bg-purple-50 px-2.5 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/30 dark:text-purple-300">
                  {l}
                  <button type="button" onClick={() => removeLoc(l)} className="ml-0.5 text-purple-400 hover:text-purple-600">
                    <X size={10} />
                  </button>
                </span>
              ))}
            </div>
          )}
          <div className="flex gap-2">
            <input
              value={locInput}
              onChange={(e) => setLocInput(e.target.value)}
              onKeyDown={(e) => {
                if (e.key === "Enter") { e.preventDefault(); addLoc(); }
                if (e.key === ",") { e.preventDefault(); addLoc(); }
              }}
              placeholder="Type a city and press Enter"
              className="min-w-0 flex-1 bg-transparent text-sm text-gray-700 outline-none placeholder:text-gray-400 dark:text-gray-300"
            />
            {locInput.trim() && (
              <button type="button" onClick={addLoc} className="shrink-0 rounded-lg bg-indigo-600 px-2.5 py-1 text-xs font-semibold text-white hover:bg-indigo-700">
                Add
              </button>
            )}
          </div>
        </div>

        {/* Notice period + immediate joiner */}
        <GroupLabel>Availability</GroupLabel>
        <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <Field label="Notice Period (days)">
            <input type="number" min="0" value={form.notice_period} onChange={set("notice_period")} placeholder="30" {...inp} />
          </Field>
          <Field label="Availability">
            <label className="flex h-full cursor-pointer items-center gap-2.5 rounded-xl border border-gray-300 px-3 py-2.5 dark:border-gray-700">
              <input type="checkbox" checked={form.immediate_joiner} onChange={setCheck("immediate_joiner")} className="accent-indigo-600" />
              <span className="text-sm text-gray-700 dark:text-gray-300">Immediate joiner</span>
            </label>
          </Field>
        </div>

        {/* Visibility */}
        <GroupLabel>Visibility</GroupLabel>
        <div className="flex flex-col gap-3 sm:flex-row sm:gap-4">
          <ToggleRow
            label="Public Profile"
            description="Allow recruiters to find you"
            checked={form.is_public}
            onChange={(v) => setBool("is_public", v)}
          />
          <ToggleRow
            label="Open to Opportunities"
            description="Signal that you're actively looking"
            checked={form.open_to_opportunities}
            onChange={(v) => setBool("open_to_opportunities", v)}
          />
        </div>

        <button type="submit" disabled={saving} className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">
          <Save size={14} />
          {saving ? "Saving…" : "Save"}
        </button>
      </form>
    </Section>
  );
}

function ToggleRow({ label, description, checked, onChange }) {
  return (
    <label className="flex flex-1 cursor-pointer items-center justify-between gap-3 rounded-xl border border-gray-200 px-4 py-3 dark:border-gray-700">
      <div>
        <p className="text-sm font-medium text-gray-900 dark:text-white">{label}</p>
        <p className="text-xs text-gray-400 dark:text-gray-500">{description}</p>
      </div>
      <div
        onClick={() => onChange(!checked)}
        className={`relative h-5 w-9 shrink-0 rounded-full transition-colors ${checked ? "bg-indigo-600" : "bg-gray-200 dark:bg-gray-700"}`}
      >
        <span
          className={`absolute top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform ${checked ? "translate-x-4" : "translate-x-0.5"}`}
        />
      </div>
    </label>
  );
}

/* ─── Resume ─────────────────────────────────────────────────── */

function ResumeSection({ profile, onUpload, uploading }) {
  const [file, setFile] = useState(null);
  const resumeName = profile?.resume_filename ?? null;

  const handleUpload = () => {
    if (!file) return;
    const fd = new FormData();
    fd.append("resume", file);
    onUpload(fd);
    setFile(null);
  };

  return (
    <Section title="Resume" icon={FileText} iconColor="text-emerald-600" iconBg="bg-emerald-50 dark:bg-emerald-900/30">
      {resumeName && (
        <div className="mb-4 flex items-center gap-3 rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 dark:border-emerald-900/40 dark:bg-emerald-900/20">
          <FileText size={16} className="shrink-0 text-emerald-600 dark:text-emerald-400" />
          <span className="min-w-0 flex-1 truncate text-sm font-medium text-gray-700 dark:text-gray-300">{resumeName}</span>
          <span className="shrink-0 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
            Active
          </span>
        </div>
      )}

      <label className="flex cursor-pointer flex-col items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-200 px-6 py-8 transition hover:border-indigo-300 hover:bg-indigo-50/40 dark:border-gray-700 dark:hover:border-indigo-700 dark:hover:bg-indigo-900/10">
        <input type="file" accept=".pdf,.doc,.docx" className="hidden" onChange={(e) => setFile(e.target.files[0] ?? null)} />
        <Upload size={22} className={file ? "text-indigo-500" : "text-gray-300 dark:text-gray-600"} />
        <div className="text-center">
          <p className="text-sm font-medium text-gray-700 dark:text-gray-300">
            {file ? file.name : "Click to upload resume"}
          </p>
          <p className="mt-0.5 text-xs text-gray-400">PDF, DOC or DOCX</p>
        </div>
      </label>

      {file && (
        <div className="mt-3 flex items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 dark:border-gray-700 dark:bg-gray-800/50">
          <span className="min-w-0 flex-1 truncate text-sm text-gray-600 dark:text-gray-400">{file.name}</span>
          <div className="flex shrink-0 items-center gap-2">
            <button type="button" onClick={() => setFile(null)} className="text-gray-400 hover:text-red-500">
              <X size={14} />
            </button>
            <button onClick={handleUpload} disabled={uploading} className="flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">
              <Upload size={12} />
              {uploading ? "Uploading…" : "Upload"}
            </button>
          </div>
        </div>
      )}
    </Section>
  );
}

/* ─── Experience ─────────────────────────────────────────────── */

const EMPTY_EXP = {
  company_name: "",
  job_title: "",
  employment_type: "",
  location: "",
  work_mode: "",
  start_date: "",
  end_date: "",
  is_current: false,
  description: "",
};

function ExperienceSection({ experiences, onAdd, onUpdate, onDelete, loading }) {
  const [form, setForm] = useState(EMPTY_EXP);
  const [editingId, setEditId] = useState(null);
  const [showForm, setShow] = useState(false);
  const formRef = useRef(null);

  useEffect(() => {
    if (showForm && formRef.current) {
      formRef.current.scrollIntoView({ behavior: "smooth", block: "nearest" });
    }
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

  const openAdd = () => { setForm(EMPTY_EXP); setEditId(null); setShow(true); };
  const openEdit = (exp) => {
    setForm({
      company_name: exp.company_name ?? "",
      job_title: exp.job_title ?? "",
      employment_type: exp.employment_type ?? "",
      location: exp.location ?? "",
      work_mode: exp.work_mode ?? "",
      start_date: toDate(exp.start_date),
      end_date: toDate(exp.end_date),
      is_current: exp.is_current ?? false,
      description: exp.description ?? "",
    });
    setEditId(exp.id);
    setShow(true);
  };
  const close = () => { setShow(false); setEditId(null); setForm(EMPTY_EXP); };

  const handleSubmit = (e) => {
    e.preventDefault();
    editingId ? onUpdate({ ...form, experience_id: editingId }) : onAdd(form);
    close();
  };

  return (
    <Section title="Experience" icon={Briefcase} iconColor="text-indigo-600" iconBg="bg-indigo-50 dark:bg-indigo-900/30" count={experiences.length}>
      <div className="space-y-3">
        {experiences.map((exp) => (
          <div key={exp.id} className="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-sm font-bold text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
              {exp.company_name?.charAt(0)?.toUpperCase() ?? "?"}
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-start justify-between gap-2">
                <div className="min-w-0">
                  <p className="font-semibold text-gray-900 dark:text-white">{exp.job_title}</p>
                  <p className="text-sm font-medium text-indigo-600 dark:text-indigo-400">{exp.company_name}</p>
                  <div className="mt-1 flex flex-wrap items-center gap-1.5">
                    {exp.employment_type && (
                      <Badge>{EMP_TYPE_LABEL[exp.employment_type] ?? exp.employment_type}</Badge>
                    )}
                    {exp.work_mode && (
                      <Badge>{WORK_MODE_LABEL[exp.work_mode] ?? exp.work_mode}</Badge>
                    )}
                    {exp.location && (
                      <span className="text-xs text-gray-400 dark:text-gray-500">· {exp.location}</span>
                    )}
                  </div>
                  <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                    {fmtDate(exp.start_date)} – {exp.is_current ? "Present" : fmtDate(exp.end_date)}
                  </p>
                </div>
                <div className="flex shrink-0 gap-1">
                  <IconBtn onClick={() => openEdit(exp)}><Pencil size={13} /></IconBtn>
                  <IconBtn onClick={() => onDelete(exp.id)} disabled={loading} danger><Trash2 size={13} /></IconBtn>
                </div>
              </div>
              {exp.description && (
                <p className="mt-2 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">{exp.description}</p>
              )}
            </div>
          </div>
        ))}
      </div>

      {showForm ? (
        <form ref={formRef} onSubmit={handleSubmit} className="mt-4 space-y-4 rounded-xl border border-indigo-100 bg-indigo-50/40 p-4 dark:border-indigo-900/50 dark:bg-indigo-950/20">
          <p className="text-xs font-semibold uppercase tracking-wide text-indigo-500 dark:text-indigo-400">
            {editingId ? "Edit Experience" : "Add Experience"}
          </p>
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <Field label="Company">
              <input required value={form.company_name} onChange={set("company_name")} {...inp} />
            </Field>
            <Field label="Job Title">
              <input required value={form.job_title} onChange={set("job_title")} {...inp} />
            </Field>
          </div>
          <div className="grid grid-cols-2 gap-3 sm:grid-cols-3">
            <Field label="Employment Type">
              <StyledSelect value={form.employment_type} onChange={set("employment_type")}>
                <option value="">Select type</option>
                {JOB_TYPES.map((t) => <option key={t.value} value={t.value}>{t.label}</option>)}
              </StyledSelect>
            </Field>
            <Field label="Work Mode">
              <StyledSelect value={form.work_mode} onChange={set("work_mode")}>
                <option value="">Select mode</option>
                {WORK_MODES.map((m) => <option key={m.value} value={m.value}>{m.label}</option>)}
              </StyledSelect>
            </Field>
            <Field label="Location">
              <input value={form.location} onChange={set("location")} placeholder="e.g. Bangalore" {...inp} />
            </Field>
          </div>
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <Field label="Start Date">
              <DatePicker value={form.start_date} onChange={(v) => setForm((f) => ({ ...f, start_date: v }))} placeholder="Start date" />
            </Field>
            <Field label="End Date">
              <DatePicker value={form.end_date} onChange={(v) => setForm((f) => ({ ...f, end_date: v }))} placeholder="End date" disabled={form.is_current} />
            </Field>
          </div>
          <label className="flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" checked={form.is_current} onChange={set("is_current")} className="accent-indigo-600" />
            Currently working here
          </label>
          <Field label="Description">
            <textarea rows={2} value={form.description} onChange={set("description")} {...inp} />
          </Field>
          <FormActions onCancel={close} loading={loading} editing={!!editingId} />
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
  location: "",
  start_year: "",
  end_year: "",
  is_current: false,
  description: "",
};

function EducationSection({ educations, onAdd, onUpdate, onDelete, loading }) {
  const [form, setForm] = useState(EMPTY_EDU);
  const [editingId, setEditId] = useState(null);
  const [showForm, setShow] = useState(false);

  const set = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.value }));
  const setCheck = (k) => (e) => setForm((f) => ({ ...f, [k]: e.target.checked }));

  const openAdd = () => { setForm(EMPTY_EDU); setEditId(null); setShow(true); };
  const openEdit = (edu) => {
    setForm({
      institution: edu.institution ?? "",
      degree: edu.degree ?? "",
      field_of_study: edu.field_of_study ?? "",
      location: edu.location ?? "",
      start_year: edu.start_year ?? "",
      end_year: edu.end_year ?? "",
      is_current: edu.is_current ?? false,
      description: edu.description ?? "",
    });
    setEditId(edu.id);
    setShow(true);
  };
  const close = () => { setShow(false); setEditId(null); setForm(EMPTY_EDU); };

  const handleSubmit = (e) => {
    e.preventDefault();
    editingId ? onUpdate({ ...form, education_id: editingId }) : onAdd(form);
    close();
  };

  return (
    <Section title="Education" icon={GraduationCap} iconColor="text-violet-600" iconBg="bg-violet-50 dark:bg-violet-900/30" count={educations.length}>
      <div className="space-y-3">
        {educations.map((edu) => (
          <div key={edu.id} className="flex items-start gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800/50">
            <div className="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-violet-100 text-sm font-bold text-violet-700 dark:bg-violet-900/40 dark:text-violet-300">
              {edu.institution?.charAt(0)?.toUpperCase() ?? "?"}
            </div>
            <div className="min-w-0 flex-1">
              <div className="flex items-start justify-between gap-2">
                <div>
                  <p className="font-semibold text-gray-900 dark:text-white">{edu.degree}</p>
                  <p className="text-sm font-medium text-violet-600 dark:text-violet-400">{edu.institution}</p>
                  <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                    {edu.field_of_study && <>{edu.field_of_study} · </>}
                    {edu.start_year} – {edu.is_current ? "Present" : edu.end_year}
                    {edu.location && <> · {edu.location}</>}
                  </p>
                </div>
                <div className="flex shrink-0 gap-1">
                  <IconBtn onClick={() => openEdit(edu)}><Pencil size={13} /></IconBtn>
                  <IconBtn onClick={() => onDelete(edu.id)} disabled={loading} danger><Trash2 size={13} /></IconBtn>
                </div>
              </div>
              {edu.description && (
                <p className="mt-2 line-clamp-2 text-sm text-gray-500 dark:text-gray-400">{edu.description}</p>
              )}
            </div>
          </div>
        ))}
      </div>

      {showForm ? (
        <form onSubmit={handleSubmit} className="mt-4 space-y-4 rounded-xl border border-violet-100 bg-violet-50/40 p-4 dark:border-violet-900/50 dark:bg-violet-950/20">
          <p className="text-xs font-semibold uppercase tracking-wide text-violet-500 dark:text-violet-400">
            {editingId ? "Edit Education" : "Add Education"}
          </p>
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <Field label="Institution">
              <input required value={form.institution} onChange={set("institution")} {...inp} />
            </Field>
            <Field label="Degree">
              <input required value={form.degree} onChange={set("degree")} {...inp} />
            </Field>
          </div>
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <Field label="Field of Study">
              <input value={form.field_of_study} onChange={set("field_of_study")} {...inp} />
            </Field>
            <Field label="Location">
              <input value={form.location} onChange={set("location")} placeholder="e.g. Bangalore" {...inp} />
            </Field>
          </div>
          <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <Field label="From">
              <YearPicker value={form.start_year} onChange={(v) => setForm((f) => ({ ...f, start_year: v }))} placeholder="Start year" />
            </Field>
            <Field label="To">
              <YearPicker value={form.end_year} onChange={(v) => setForm((f) => ({ ...f, end_year: v }))} placeholder="End year" disabled={form.is_current} />
            </Field>
          </div>
          <label className="flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" checked={form.is_current} onChange={setCheck("is_current")} className="accent-indigo-600" />
            Currently studying here
          </label>
          <Field label="Description">
            <textarea rows={2} value={form.description} onChange={set("description")} placeholder="Relevant coursework, achievements…" {...inp} />
          </Field>
          <FormActions onCancel={close} loading={loading} editing={!!editingId} />
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
    "w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-indigo-300 hover:bg-white focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:hover:border-indigo-600 dark:hover:bg-gray-800 dark:focus:border-indigo-500 dark:focus:bg-gray-900 dark:focus:ring-indigo-950/50",
};

function Section({ title, icon: Icon, iconColor, iconBg, count, children }) {
  return (
    <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-gray-800">
        {Icon && (
          <div className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${iconBg}`}>
            <Icon size={15} className={iconColor} />
          </div>
        )}
        <h2 className="flex-1 text-sm font-semibold text-gray-900 dark:text-white">{title}</h2>
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

function StyledSelect({ value, onChange, wrapperClassName = "", className = "", children }) {
  return (
    <div className={`relative ${wrapperClassName}`}>
      <select
        value={value}
        onChange={onChange}
        className={`${inp.className} w-full appearance-none pr-9 ${className}`}
      >
        {children}
      </select>
      <ChevronDown size={14} className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" />
    </div>
  );
}

function GroupLabel({ children }) {
  return (
    <p className="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
      {children}
    </p>
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

function Badge({ children }) {
  return (
    <span className="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
      {children}
    </span>
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
      <button type="submit" disabled={loading} className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60">
        {editing ? "Update" : "Add"}
      </button>
      <button type="button" onClick={onCancel} className="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
        Cancel
      </button>
    </div>
  );
}
