import { useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { jobsApi } from "@/api/jobs.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import JobSearchBar from "../components/JobSearchBar";
import JobFilters from "../components/JobFilters";
import PublicJobCard from "../components/PublicJobCard";
import JobDetailModal from "../components/JobDetailModal";

export default function HomePage() {
  const [selectedJobId, setSelectedJobId] = useState(null);
  const [page, setPage] = useState(1);
  const [keyword, setKeyword] = useState("");
  const [location, setLocation] = useState("");
  const [filters, setFilters] = useState({
    job_type: [],
    work_mode: [],
    experience_level: [],
  });

  const params = {
    page,
    per_page: PAGINATION_DEFAULT.PER_PAGE,
    status: "OPEN",
    text: keyword || undefined,
    location: location || undefined,
    job_type: filters.job_type.length ? filters.job_type : undefined,
    work_mode: filters.work_mode.length ? filters.work_mode : undefined,
    experience_level: filters.experience_level.length
      ? filters.experience_level
      : undefined,
  };

  const { data, isLoading } = useQuery({
    queryKey: ["public-jobs", params],
    queryFn: () => jobsApi.list(params).then((r) => r.data?.data),
  });

  const jobs = data?.jobs ?? [];
  const totalPages = data?.total_pages ?? 1;

  const handleSearch = (kw, loc) => {
    setKeyword(kw);
    setLocation(loc);
    setPage(1);
  };

  const toggleFilter = (key, value) => {
    setFilters((prev) => ({
      ...prev,
      [key]: prev[key].includes(value)
        ? prev[key].filter((v) => v !== value)
        : [...prev[key], value],
    }));
    setPage(1);
  };

  return (
    <div className="min-h-screen bg-gray-100 dark:bg-gray-950">
      <JobSearchBar onSearch={handleSearch} />

      <div className="mx-auto flex max-w-7xl gap-6 px-6 py-8">
        <JobFilters filters={filters} onToggle={toggleFilter} />

        <main className="min-w-0 flex-1">
          <p className="mb-4 text-sm text-gray-500 dark:text-gray-400">
            {isLoading
              ? "Searching…"
              : `${data?.total ?? jobs.length} jobs found`}
          </p>

          {isLoading ? (
            <Loader />
          ) : jobs.length === 0 ? (
            <EmptyState
              title="No jobs found"
              description="Try different keywords or adjust the filters."
            />
          ) : (
            <>
              <div className="space-y-3">
                {jobs.map((job) => (
                  <PublicJobCard
                    key={job.job_id}
                    job={job}
                    onClick={() => setSelectedJobId(job.job_id)}
                  />
                ))}
              </div>
              <Pagination
                page={page}
                totalPages={totalPages}
                onPageChange={setPage}
              />
            </>
          )}
        </main>
      </div>
      {selectedJobId && (
        <JobDetailModal
          jobId={selectedJobId}
          onClose={() => setSelectedJobId(null)}
        />
      )}
    </div>
  );
}
