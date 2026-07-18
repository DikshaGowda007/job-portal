import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { seekerApi } from "@/api/seeker.api";
import { categoryApi } from "@/api/category.api";
import { JOB_TYPES, WORK_MODES, EXP_LEVELS } from "@/utils/constants";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import ConfirmModal from "@/components/modals/ConfirmModal";
import FilterGroup from "@/features/home/components/FilterGroup";
import { Bell, MapPin, Search, Pencil, Trash2, X } from "lucide-react";

const EMPTY_FORM = {
  keyword: "",
  location: "",
  job_category_id: "",
  job_type: [],
  work_mode: [],
  experience_level: [],
};

export default function SeekerJobAlertsPage() {
  const queryClient = useQueryClient();
  const [form, setForm] = useState(EMPTY_FORM);
  const [editingId, setEditingId] = useState(null);
  const [deleteId, setDeleteId] = useState(null);

  const { data, isLoading } = useQuery({
    queryKey: ["seeker-job-alerts"],
    queryFn: () => seekerApi.jobAlerts().then((r) => r.data?.data),
  });

  const { data: categories = [] } = useQuery({
    queryKey: ["categories"],
    queryFn: () => categoryApi.list({}).then((r) => r.data?.data?.list ?? []),
  });

  const resetForm = () => {
    setForm(EMPTY_FORM);
    setEditingId(null);
  };

  const saveMutation = useMutation({
    mutationFn: (payload) =>
      editingId
        ? seekerApi.updateJobAlert({ id: editingId, ...payload })
        : seekerApi.createJobAlert(payload),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-job-alerts"] });
      toast.success(editingId ? "Job alert updated" : "Job alert created");
      resetForm();
    },
    onError: () =>
      toast.error(editingId ? "Failed to update job alert" : "Failed to create job alert"),
  });

  const deleteMutation = useMutation({
    mutationFn: (id) => seekerApi.deleteJobAlert({ id }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["seeker-job-alerts"] });
      setDeleteId(null);
      toast.success("Job alert removed");
    },
    onError: () => toast.error("Failed to remove job alert"),
  });

  const handleToggleFilter = (key, value) => {
    setForm((prev) => ({
      ...prev,
      [key]: prev[key].includes(value) ? [] : [value],
    }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    saveMutation.mutate({
      keyword: form.keyword || undefined,
      location: form.location || undefined,
      job_category_id: form.job_category_id ? Number(form.job_category_id) : undefined,
      job_type: form.job_type[0] ?? undefined,
      work_mode: form.work_mode[0] ?? undefined,
      experience_level: form.experience_level[0] ?? undefined,
    });
  };

  const startEdit = (alert) => {
    setEditingId(alert.id);
    setForm({
      keyword: alert.keyword ?? "",
      location: alert.location ?? "",
      job_category_id: alert.job_category_id ? String(alert.job_category_id) : "",
      job_type: alert.job_type ? [alert.job_type] : [],
      work_mode: alert.work_mode ? [alert.work_mode] : [],
      experience_level: alert.experience_level ? [alert.experience_level] : [],
    });
  };

  const alerts = Array.isArray(data) ? data : [];

  const criteriaSummary = (alert) => {
    const parts = [];
    if (alert.keyword) parts.push(alert.keyword);
    if (alert.location) parts.push(alert.location);
    if (alert.job_type) parts.push(alert.job_type.replace(/_/g, " "));
    if (alert.work_mode) parts.push(alert.work_mode);
    if (alert.experience_level) parts.push(alert.experience_level);
    return parts.length > 0 ? parts.join(" · ") : "Any job";
  };

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Job Alerts</h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Get notified when a new job matches your saved criteria
        </p>
      </div>

      <form
        onSubmit={handleSubmit}
        className="mb-8 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900"
      >
        <div className="mb-4 flex items-center justify-between">
          <h2 className="font-semibold text-gray-900 dark:text-white">
            {editingId ? "Edit Alert" : "Create New Alert"}
          </h2>
          {editingId && (
            <button
              type="button"
              onClick={resetForm}
              className="flex items-center gap-1 text-xs font-medium text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
            >
              <X size={14} />
              Cancel edit
            </button>
          )}
        </div>

        <div className="mb-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
          <div className="relative">
            <Search size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              value={form.keyword}
              onChange={(e) => setForm((prev) => ({ ...prev, keyword: e.target.value }))}
              placeholder="Job title or keyword"
              className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900"
            />
          </div>
          <div className="relative">
            <MapPin size={15} className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
              type="text"
              value={form.location}
              onChange={(e) => setForm((prev) => ({ ...prev, location: e.target.value }))}
              placeholder="Location"
              className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-9 pr-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900"
            />
          </div>
        </div>

        {categories.length > 0 && (
          <div className="mb-4">
            <label className="mb-1.5 block text-xs font-medium text-gray-500 dark:text-gray-400">
              Category
            </label>
            <select
              value={form.job_category_id}
              onChange={(e) => setForm((prev) => ({ ...prev, job_category_id: e.target.value }))}
              className="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900"
            >
              <option value="">Any category</option>
              {categories.map((cat) => (
                <option key={cat.id} value={cat.id}>
                  {cat.name}
                </option>
              ))}
            </select>
          </div>
        )}

        <div className="mb-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
          <FilterGroup
            title="Job Type"
            items={JOB_TYPES}
            selected={form.job_type}
            onToggle={(v) => handleToggleFilter("job_type", v)}
            format={(v) => v.replace(/_/g, " ")}
          />
          <FilterGroup
            title="Work Mode"
            items={WORK_MODES}
            selected={form.work_mode}
            onToggle={(v) => handleToggleFilter("work_mode", v)}
            format={(v) => v.charAt(0).toUpperCase() + v.slice(1)}
          />
          <FilterGroup
            title="Experience"
            items={EXP_LEVELS}
            selected={form.experience_level}
            onToggle={(v) => handleToggleFilter("experience_level", v)}
          />
        </div>

        <button
          type="submit"
          disabled={saveMutation.isPending}
          className="flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500 dark:hover:bg-indigo-400"
        >
          <Bell size={15} />
          {saveMutation.isPending ? "Saving..." : editingId ? "Update Alert" : "Create Alert"}
        </button>
      </form>

      {isLoading ? (
        <Loader />
      ) : alerts.length === 0 ? (
        <EmptyState
          title="No job alerts yet"
          description="Create an alert above to get notified about matching jobs."
        />
      ) : (
        <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
          {alerts.map((alert) => (
            <div
              key={alert.id}
              className="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
            >
              <div className="flex items-start gap-3">
                <div className="flex size-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                  <Bell size={18} />
                </div>
                <div className="min-w-0 flex-1">
                  <p className="truncate text-sm font-semibold text-gray-900 dark:text-white">
                    {criteriaSummary(alert)}
                  </p>
                  <p className="mt-0.5 text-xs text-gray-400 dark:text-gray-500">
                    Created {new Date(alert.created_at).toLocaleDateString()}
                  </p>
                </div>
              </div>

              <div className="mt-auto flex items-center justify-end gap-2 border-t border-gray-100 pt-3 dark:border-gray-800">
                <button
                  onClick={() => startEdit(alert)}
                  className="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                >
                  <Pencil size={14} />
                  Edit
                </button>
                <button
                  onClick={() => setDeleteId(alert.id)}
                  className="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-red-500 transition hover:bg-red-50 dark:hover:bg-red-950/30"
                >
                  <Trash2 size={14} />
                  Delete
                </button>
              </div>
            </div>
          ))}
        </div>
      )}

      <ConfirmModal
        open={!!deleteId}
        onClose={() => setDeleteId(null)}
        onConfirm={() => deleteMutation.mutate(deleteId)}
        title="Remove this job alert?"
        description="You'll stop getting notified about jobs matching this criteria."
        confirmText="Remove"
        loading={deleteMutation.isPending}
        danger
      />
    </div>
  );
}
