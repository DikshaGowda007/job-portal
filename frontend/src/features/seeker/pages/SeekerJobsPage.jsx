import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useQuery, useMutation } from "@tanstack/react-query";
import { toast } from "sonner";
import { jobsApi } from "@/api/jobs.api";
import { seekerApi } from "@/api/seeker.api";
import { categoryApi } from "@/api/category.api";
import { useAuth } from "@/context/AuthContext";
import { ROLES } from "@/utils/roles";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import { WORK_MODE_STYLE, JOB_TYPE_STYLE, entityColor } from "@/utils/styles";
import { formatSalary, timeAgo } from "@/utils/formatters";
import { ROUTES } from "@/utils/routePaths";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import JobFilters from "@/features/home/components/JobFilters";
import { MapPin, Clock, Bookmark, BookmarkCheck, Search, CheckCircle2, SlidersHorizontal, X } from "lucide-react";

const EMPTY_FILTERS = { job_type: [], work_mode: [], experience_level: [] };

export default function SeekerJobsPage() {
  const navigate = useNavigate();
  const { isAuthenticated, role } = useAuth();
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [search, setSearch] = useState("");
  const [filters, setFilters] = useState(EMPTY_FILTERS);
  const [categoryId, setCategoryId] = useState("");
  const [savedIds, setSavedIds] = useState(new Set());
  const [mobileFiltersOpen, setMobileFiltersOpen] = useState(false);

  const { data: applicationsData } = useQuery({
    queryKey: ["seeker-applied-ids"],
    queryFn: () =>
      seekerApi.myApplications({ page: 1, per_page: 500 })
        .then((r) => r.data?.data?.applications ?? []),
    enabled: isAuthenticated && role === ROLES.JOB_SEEKER,
  });

  const { data: categories = [] } = useQuery({
    queryKey: ["categories"],
    queryFn: () => categoryApi.list({}).then((r) => r.data?.data?.list ?? []),
  });

  const appliedIds = new Set(
    (Array.isArray(applicationsData) ? applicationsData : []).map((a) => a.job_post_id)
  );

  const activeFilters = {
    job_type: filters.job_type[0] ?? undefined,
    work_mode: filters.work_mode[0] ?? undefined,
    experience_level: filters.experience_level[0] ?? undefined,
    job_category_id: categoryId ? Number(categoryId) : undefined,
  };

  const hasActiveFilters =
    !!activeFilters.job_type ||
    !!activeFilters.work_mode ||
    !!activeFilters.experience_level ||
    !!activeFilters.job_category_id;

  const { data, isLoading } = useQuery({
    queryKey: ["seeker-jobs", page, search, activeFilters],
    queryFn: () =>
      jobsApi
        .list({
          page,
          per_page: PAGINATION_DEFAULT.PER_PAGE,
          text: search || undefined,
          ...activeFilters,
        })
        .then((r) => r.data?.data),
  });

  const saveMutation = useMutation({
    mutationFn: ({ job_post_id, remove }) =>
      remove
        ? seekerApi.unsaveJob({ job_post_id })
        : seekerApi.saveJob({ job_post_id }),
    onSuccess: (_, { job_post_id, remove }) => {
      setSavedIds((prev) => {
        const next = new Set(prev);
        remove ? next.delete(job_post_id) : next.add(job_post_id);
        return next;
      });
      toast.success(remove ? "Job removed from saved" : "Job saved successfully");
    },
    onError: (_, { remove }) =>
      toast.error(remove ? "Failed to remove saved job" : "Failed to save job"),
  });

  const handleToggleFilter = (key, value) => {
    setPage(1);
    setFilters((prev) => ({
      ...prev,
      [key]: prev[key].includes(value) ? [] : [value],
    }));
  };

  const clearAllFilters = () => {
    setFilters(EMPTY_FILTERS);
    setCategoryId("");
    setPage(1);
  };

  const jobs = data?.jobs ?? [];
  const totalPages = data?.total_pages ?? 1;
  const total = data?.total ?? null;

  const filterSidebar = (
    <div className="space-y-4">
      {/* Category */}
      {categories.length > 0 && (
        <div className="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
          <h3 className="mb-3 text-xs font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
            Category
          </h3>
          <div className="space-y-1">
            {categories.map((cat) => {
              const checked = categoryId === String(cat.id);
              return (
                <label
                  key={cat.id}
                  onClick={() => { setCategoryId(checked ? "" : String(cat.id)); setPage(1); }}
                  className={`flex cursor-pointer items-center gap-3 rounded-lg px-2 py-2 text-sm transition-colors ${
                    checked
                      ? "bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
                      : "text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-800"
                  }`}
                >
                  <span className={`flex h-4 w-4 shrink-0 items-center justify-center rounded border transition-colors ${checked ? "border-indigo-600 bg-indigo-600" : "border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-800"}`}>
                    {checked && (
                      <svg className="h-2.5 w-2.5 text-white" viewBox="0 0 10 10" fill="none">
                        <path d="M1.5 5l2.5 2.5 4.5-4.5" stroke="currentColor" strokeWidth="1.8" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                    )}
                  </span>
                  <span className="font-medium">{cat.name}</span>
                </label>
              );
            })}
          </div>
        </div>
      )}

      <JobFilters filters={filters} onToggle={handleToggleFilter} />

      {hasActiveFilters && (
        <button
          onClick={clearAllFilters}
          className="w-full rounded-xl border border-red-200 py-2 text-sm font-medium text-red-500 hover:bg-red-50 dark:border-red-900 dark:hover:bg-red-950/20"
        >
          Clear all filters
        </button>
      )}
    </div>
  );

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Browse Jobs</h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">Find your next opportunity</p>
      </div>

      {/* Search + mobile filter toggle */}
      <div className="mb-6 flex gap-3">
        <div className="relative flex-1 max-w-lg">
          <Search size={16} className="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400" />
          <input
            type="text"
            value={search}
            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
            placeholder="Search by title, company, or keyword…"
            className="w-full rounded-xl border border-gray-300 bg-white py-2.5 pl-10 pr-4 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:focus:border-indigo-500"
          />
        </div>
        <button
          onClick={() => setMobileFiltersOpen(true)}
          className={`flex items-center gap-2 rounded-xl border px-4 py-2.5 text-sm font-medium transition lg:hidden ${
            hasActiveFilters
              ? "border-indigo-300 bg-indigo-50 text-indigo-700 dark:border-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
              : "border-gray-300 bg-white text-gray-600 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400"
          }`}
        >
          <SlidersHorizontal size={15} />
          Filters
          {hasActiveFilters && <span className="flex h-4 w-4 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white">
            {[activeFilters.job_type, activeFilters.work_mode, activeFilters.experience_level, activeFilters.job_category_id].filter(Boolean).length}
          </span>}
        </button>
      </div>

      <div className="flex gap-6">
        {/* Desktop sidebar */}
        <aside className="hidden w-56 shrink-0 lg:block">
          <div className="sticky top-24">{filterSidebar}</div>
        </aside>

        {/* Job list */}
        <div className="min-w-0 flex-1">
          {isLoading ? (
            <Loader />
          ) : jobs.length === 0 ? (
            <EmptyState title="No jobs found" description="Try adjusting your search or filters." />
          ) : (
            <>
              {total !== null && (
                <p className="mb-4 text-sm text-gray-500 dark:text-gray-400">
                  <span className="font-semibold text-gray-900 dark:text-white">{total}</span> jobs found
                </p>
              )}

              <div className="grid grid-cols-1 gap-4 xl:grid-cols-2">
                {jobs.map((job) => {
                  const isSaved = savedIds.has(job.job_id);
                  const initial = job.company_name?.charAt(0)?.toUpperCase() ?? "?";

                  return (
                    <div
                      key={job.job_id}
                      className="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-indigo-300 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-700"
                    >
                      <div className="flex items-start gap-3">
                        <div className={`flex size-12 shrink-0 items-center justify-center rounded-xl text-lg font-bold ${entityColor(job.company_name)}`}>
                          {initial}
                        </div>
                        <div className="min-w-0 flex-1">
                          <h3 className="truncate font-semibold text-gray-900 dark:text-white">{job.title}</h3>
                          <p className="mt-0.5 truncate text-sm text-gray-500 dark:text-gray-400">{job.company_name}</p>
                          {job.location && (
                            <p className="mt-0.5 flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                              <MapPin size={11} className="shrink-0" />{job.location}
                            </p>
                          )}
                        </div>
                        <button
                          onClick={() => saveMutation.mutate({ job_post_id: job.job_id, remove: isSaved })}
                          className="shrink-0 rounded-lg p-1.5 text-gray-400 transition hover:bg-gray-100 hover:text-indigo-600 dark:hover:bg-gray-800 dark:hover:text-indigo-400"
                        >
                          {isSaved ? <BookmarkCheck size={18} className="text-indigo-600 dark:text-indigo-400" /> : <Bookmark size={18} />}
                        </button>
                      </div>

                      <div className="flex flex-wrap gap-2">
                        {job.work_mode && (
                          <span className={`rounded-full px-2.5 py-1 text-xs font-medium capitalize ${WORK_MODE_STYLE[job.work_mode] ?? "bg-gray-100 text-gray-600"}`}>
                            {job.work_mode}
                          </span>
                        )}
                        {job.job_type && (
                          <span className={`rounded-full px-2.5 py-1 text-xs font-medium ${JOB_TYPE_STYLE[job.job_type] ?? "bg-gray-100 text-gray-600"}`}>
                            {job.job_type.replace(/_/g, " ")}
                          </span>
                        )}
                        {job.experience?.experience_level && (
                          <span className="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            {job.experience.experience_level}
                          </span>
                        )}
                      </div>

                      {(job.salary_details?.min || job.salary_details?.max) && (
                        <p className="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                          {formatSalary(job.salary_details.min, job.salary_details.max, job.salary_details.currency, job.salary_details.salary_type)}
                        </p>
                      )}

                      <div className="mt-auto flex items-center justify-between border-t border-gray-100 pt-3 dark:border-gray-800">
                        <span className="flex items-center gap-1 text-xs text-gray-400 dark:text-gray-500">
                          <Clock size={12} />{timeAgo(job.created_at)}
                        </span>
                        {appliedIds.has(job.job_id) ? (
                          <span className="flex items-center gap-1.5 rounded-lg bg-green-50 px-4 py-1.5 text-sm font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">
                            <CheckCircle2 size={14} /> Applied
                          </span>
                        ) : (
                          <button
                            onClick={() => navigate(ROUTES.SEEKER_JOB_DETAIL.replace(":id", job.job_id))}
                            className="rounded-lg bg-indigo-600 px-4 py-1.5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                          >
                            Apply Now
                          </button>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>

              <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
            </>
          )}
        </div>
      </div>

      {/* Mobile filters drawer */}
      {mobileFiltersOpen && (
        <>
          <div className="fixed inset-0 z-40 bg-black/40" onClick={() => setMobileFiltersOpen(false)} />
          <div className="fixed bottom-0 left-0 right-0 z-50 max-h-[80vh] overflow-y-auto rounded-t-2xl bg-gray-100 p-4 dark:bg-gray-950">
            <div className="mb-4 flex items-center justify-between">
              <h2 className="font-semibold text-gray-900 dark:text-white">Filters</h2>
              <button onClick={() => setMobileFiltersOpen(false)} className="rounded-lg p-1.5 text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800">
                <X size={18} />
              </button>
            </div>
            {filterSidebar}
            <button
              onClick={() => setMobileFiltersOpen(false)}
              className="mt-4 w-full rounded-xl bg-indigo-600 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700"
            >
              Show Results
            </button>
          </div>
        </>
      )}
    </div>
  );
}
