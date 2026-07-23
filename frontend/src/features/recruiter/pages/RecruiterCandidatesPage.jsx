import { useEffect, useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import CandidateCard from "@/components/cards/CandidateCard";
import CandidateProfileDrawer from "../components/CandidateProfileDrawer";
import { Search, X } from "lucide-react";

const WORK_MODE_OPTIONS = ["ONSITE", "REMOTE", "HYBRID"];

export default function RecruiterCandidatesPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [text, setText] = useState("");
  const [location, setLocation] = useState("");
  const [experienceMin, setExperienceMin] = useState("");
  const [experienceMax, setExperienceMax] = useState("");
  const [workMode, setWorkMode] = useState([]);
  const [skills, setSkills] = useState([]);
  const [skillInput, setSkillInput] = useState("");
  const [viewingUserId, setViewingUserId] = useState(null);
  const queryClient = useQueryClient();

  const [filters, setFilters] = useState({});

  useEffect(() => {
    const timeout = setTimeout(() => {
      setFilters({
        text: text || undefined,
        location: location || undefined,
        experience_min: experienceMin || undefined,
        experience_max: experienceMax || undefined,
        work_mode: workMode.length ? workMode : undefined,
        skills: skills.length ? skills : undefined,
      });
      setPage(1);
    }, 300);
    return () => clearTimeout(timeout);
  }, [text, location, experienceMin, experienceMax, workMode, skills]);

  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-candidates", page, filters],
    queryFn: () =>
      recruiterApi
        .searchCandidates({ page, per_page: PAGINATION_DEFAULT.PER_PAGE, ...filters })
        .then((r) => r.data?.data),
  });

  const candidates = data?.candidates ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;

  const { data: shortlistData } = useQuery({
    queryKey: ["recruiter-shortlist-ids"],
    queryFn: () =>
      recruiterApi
        .getShortlistedCandidates({ page: 1, per_page: 100 })
        .then((r) => r.data?.data),
  });

  const shortlistedIds = new Set(
    (shortlistData?.candidates ?? []).map((c) => c.user_id)
  );

  const shortlistMutation = useMutation({
    mutationFn: ({ candidateUserId, shortlisted }) =>
      shortlisted
        ? recruiterApi.unshortlistCandidate({ candidate_user_id: candidateUserId })
        : recruiterApi.shortlistCandidate({ candidate_user_id: candidateUserId }),
    onSuccess: (_, { shortlisted }) => {
      queryClient.invalidateQueries({ queryKey: ["recruiter-shortlist-ids"] });
      toast.success(shortlisted ? "Removed from shortlist" : "Added to shortlist");
    },
    onError: () => toast.error("Failed to update shortlist"),
  });

  const toggleShortlist = (candidateUserId) => {
    shortlistMutation.mutate({
      candidateUserId,
      shortlisted: shortlistedIds.has(candidateUserId),
    });
  };

  const toggleWorkMode = (mode) => {
    setWorkMode((prev) =>
      prev.includes(mode) ? prev.filter((m) => m !== mode) : [...prev, mode]
    );
  };

  const addSkill = (e) => {
    if ((e.key === "Enter" || e.key === ",") && skillInput.trim()) {
      e.preventDefault();
      const value = skillInput.trim().replace(/,$/, "");
      if (value && !skills.includes(value)) {
        setSkills((prev) => [...prev, value]);
      }
      setSkillInput("");
    } else if (e.key === "Backspace" && !skillInput && skills.length > 0) {
      setSkills((prev) => prev.slice(0, -1));
    }
  };

  const removeSkill = (skill) => {
    setSkills((prev) => prev.filter((s) => s !== skill));
  };

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
          Find Candidates
        </h1>
        <p className="mt-1 text-gray-500 dark:text-gray-400">
          Search job seekers who are open to opportunities
        </p>
      </div>

      <div className="mb-6 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
          <div className="flex items-center gap-2 rounded-xl border border-gray-200 px-3 dark:border-gray-700">
            <Search size={15} className="shrink-0 text-gray-400" />
            <input
              value={text}
              onChange={(e) => setText(e.target.value)}
              placeholder="Title, headline, company..."
              className="h-10 w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
            />
          </div>

          <input
            value={location}
            onChange={(e) => setLocation(e.target.value)}
            placeholder="City, state, or country"
            className="h-10 rounded-xl border border-gray-200 bg-transparent px-3 text-sm outline-none placeholder:text-gray-400 dark:border-gray-700"
          />

          <div className="flex items-center gap-2">
            <input
              type="number"
              min="0"
              value={experienceMin}
              onChange={(e) => setExperienceMin(e.target.value)}
              placeholder="Min yrs"
              className="h-10 w-full rounded-xl border border-gray-200 bg-transparent px-3 text-sm outline-none placeholder:text-gray-400 dark:border-gray-700"
            />
            <input
              type="number"
              min="0"
              value={experienceMax}
              onChange={(e) => setExperienceMax(e.target.value)}
              placeholder="Max yrs"
              className="h-10 w-full rounded-xl border border-gray-200 bg-transparent px-3 text-sm outline-none placeholder:text-gray-400 dark:border-gray-700"
            />
          </div>

          <div className="flex flex-wrap items-center gap-2 rounded-xl border border-gray-200 px-2 py-1.5 dark:border-gray-700">
            {skills.map((skill) => (
              <span
                key={skill}
                className="flex items-center gap-1 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300"
              >
                {skill}
                <button onClick={() => removeSkill(skill)} type="button">
                  <X size={11} />
                </button>
              </span>
            ))}
            <input
              value={skillInput}
              onChange={(e) => setSkillInput(e.target.value)}
              onKeyDown={addSkill}
              placeholder={skills.length ? "" : "Add skill, Enter"}
              className="h-7 min-w-[6rem] flex-1 bg-transparent text-sm outline-none placeholder:text-gray-400"
            />
          </div>
        </div>

        <div className="mt-3 flex flex-wrap gap-2">
          {WORK_MODE_OPTIONS.map((mode) => (
            <button
              key={mode}
              type="button"
              onClick={() => toggleWorkMode(mode)}
              className={`rounded-full border px-3 py-1 text-xs font-medium transition ${
                workMode.includes(mode)
                  ? "border-indigo-600 bg-indigo-50 text-indigo-700 dark:border-indigo-400 dark:bg-indigo-900/30 dark:text-indigo-300"
                  : "border-gray-300 text-gray-600 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
              }`}
            >
              {mode.charAt(0) + mode.slice(1).toLowerCase()}
            </button>
          ))}
        </div>
      </div>

      {isLoading ? (
        <Loader />
      ) : candidates.length === 0 ? (
        <EmptyState
          title="No candidates found"
          description="Try widening your filters."
        />
      ) : (
        <>
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            {candidates.map((candidate) => (
              <CandidateCard
                key={candidate.user_id}
                candidate={candidate}
                onViewProfile={setViewingUserId}
                isShortlisted={shortlistedIds.has(candidate.user_id)}
                onToggleShortlist={toggleShortlist}
              />
            ))}
          </div>

          <Pagination page={page} totalPages={totalPages} onPageChange={setPage} />
        </>
      )}

      <CandidateProfileDrawer
        userId={viewingUserId}
        onClose={() => setViewingUserId(null)}
      />
    </div>
  );
}
