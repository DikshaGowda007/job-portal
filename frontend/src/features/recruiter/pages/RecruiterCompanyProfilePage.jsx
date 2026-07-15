import { useState, useEffect } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { Section, Field, SelectField, inp } from "@/components/forms/FormUI";
import Loader from "@/components/common/Loader";
import { AlertCircle, Building2 } from "lucide-react";

const COMPANY_SIZE_OPTIONS = [
  { value: "1-10", label: "1-10 employees" },
  { value: "11-50", label: "11-50 employees" },
  { value: "51-200", label: "51-200 employees" },
  { value: "201-500", label: "201-500 employees" },
  { value: "500+", label: "500+ employees" },
];

const EMPTY_FORM = {
  company_name: "",
  company_about: "",
  company_website: "",
  company_size: "",
  industry: "",
  company_phone: "",
  city: "",
  state: "",
  country: "",
  linkedin_url: "",
};

export default function RecruiterCompanyProfilePage() {
  const queryClient = useQueryClient();
  const [form, setForm] = useState(EMPTY_FORM);
  const [error, setError] = useState("");

  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-company-profile"],
    queryFn: () =>
      recruiterApi
        .getCompanyProfile()
        .then((r) => r.data?.data)
        .catch((err) => {
          if (err?.message === "Recruiter profile not found") return null;
          throw err;
        }),
  });

  useEffect(() => {
    if (!data) return;
    setForm({
      company_name: data.company_name ?? "",
      company_about: data.company_about ?? "",
      company_website: data.company_website ?? "",
      company_size: data.company_size ?? "",
      industry: data.industry ?? "",
      company_phone: data.company_phone ?? "",
      city: data.city ?? "",
      state: data.state ?? "",
      country: data.country ?? "",
      linkedin_url: data.linkedin_url ?? "",
    });
  }, [data]);

  const mutation = useMutation({
    mutationFn: (payload) => recruiterApi.updateCompanyProfile(payload),
    onSuccess: () => {
      toast.success("Company profile updated successfully");
      setError("");
      queryClient.invalidateQueries({ queryKey: ["recruiter-company-profile"] });
    },
    onError: (err) => {
      setError(err.message ?? "Failed to update company profile");
    },
  });

  const set = (name) => (e) => {
    setForm((prev) => ({ ...prev, [name]: e.target.value }));
    setError("");
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    mutation.mutate(form);
  };

  if (isLoading) return <Loader />;

  return (
    <div className="max-w-2xl">
      <div className="mb-6 flex items-center gap-3">
        <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-900/30">
          <Building2 size={20} className="text-indigo-600 dark:text-indigo-400" />
        </div>
        <div>
          <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Company Profile</h1>
          <p className="text-sm text-gray-500 dark:text-gray-400">
            Tell job seekers about your company
          </p>
        </div>
      </div>

      {error && (
        <div className="mb-4 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 px-3.5 py-3 text-sm text-red-600 dark:border-red-900 dark:bg-red-950/50 dark:text-red-400">
          <AlertCircle size={15} className="mt-0.5 shrink-0" />
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-6">
        <Section
          title="Company details"
          icon={Building2}
          iconColor="text-indigo-600 dark:text-indigo-400"
          iconBg="bg-indigo-50 dark:bg-indigo-900/30"
        >
          <Field label="Company name">
            <input
              value={form.company_name}
              onChange={set("company_name")}
              placeholder="Acme Corp"
              {...inp}
            />
          </Field>

          <Field label="About the company">
            <textarea
              rows={4}
              value={form.company_about}
              onChange={set("company_about")}
              placeholder="What does your company do?"
              {...inp}
            />
          </Field>

          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <SelectField
              label="Company size"
              value={form.company_size}
              onChange={set("company_size")}
              options={COMPANY_SIZE_OPTIONS}
              placeholder="— Select size —"
            />
            <Field label="Industry">
              <input
                value={form.industry}
                onChange={set("industry")}
                placeholder="Technology"
                {...inp}
              />
            </Field>
          </div>

          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <Field label="Website">
              <input
                type="url"
                value={form.company_website}
                onChange={set("company_website")}
                placeholder="https://acme.example.com"
                {...inp}
              />
            </Field>
            <Field label="LinkedIn">
              <input
                type="url"
                value={form.linkedin_url}
                onChange={set("linkedin_url")}
                placeholder="https://linkedin.com/company/acme"
                {...inp}
              />
            </Field>
          </div>

          <Field label="Phone">
            <input
              value={form.company_phone}
              onChange={set("company_phone")}
              placeholder="9876543210"
              {...inp}
            />
          </Field>
        </Section>

        <Section
          title="Location"
          icon={Building2}
          iconColor="text-violet-600 dark:text-violet-400"
          iconBg="bg-violet-50 dark:bg-violet-900/30"
        >
          <div className="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <Field label="City">
              <input value={form.city} onChange={set("city")} placeholder="Bengaluru" {...inp} />
            </Field>
            <Field label="State">
              <input value={form.state} onChange={set("state")} placeholder="Karnataka" {...inp} />
            </Field>
            <Field label="Country">
              <input value={form.country} onChange={set("country")} placeholder="India" {...inp} />
            </Field>
          </div>
        </Section>

        <button
          type="submit"
          disabled={mutation.isPending}
          className="w-full rounded-lg bg-indigo-600 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400 sm:w-auto sm:px-8"
        >
          {mutation.isPending ? "Saving…" : "Save changes"}
        </button>
      </form>
    </div>
  );
}
