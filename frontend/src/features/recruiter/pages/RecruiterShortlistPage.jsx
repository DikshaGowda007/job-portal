import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { toast } from "sonner";
import { recruiterApi } from "@/api/recruiter.api";
import { PAGINATION_DEFAULT } from "@/utils/constants";
import Loader from "@/components/common/Loader";
import EmptyState from "@/components/common/EmptyState";
import Pagination from "@/components/common/Pagination";
import CandidateCard from "@/components/cards/CandidateCard";
import CandidateProfileDrawer from "../components/CandidateProfileDrawer";

export default function RecruiterShortlistPage() {
  const [page, setPage] = useState(PAGINATION_DEFAULT.PAGE);
  const [viewingUserId, setViewingUserId] = useState(null);
  const queryClient = useQueryClient();

  const { data, isLoading } = useQuery({
    queryKey: ["recruiter-shortlist-ids", page],
    queryFn: () =>
      recruiterApi
        .getShortlistedCandidates({ page, per_page: PAGINATION_DEFAULT.PER_PAGE })
        .then((r) => r.data?.data),
  });

  const unshortlistMutation = useMutation({
    mutationFn: (candidateUserId) =>
      recruiterApi.unshortlistCandidate({ candidate_user_id: candidateUserId }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["recruiter-shortlist-ids"] });
      toast.success("Removed from shortlist");
    },
    onError: () => toast.error("Failed to remove from shortlist"),
  });

  const candidates = data?.candidates ?? [];
  const totalPages = data?.pagination?.last_page ?? 1;

  return (
    <div>
      <div className="mb-6">
        <h1 className="text-2xl font-bold text-gray-900 dark:text-white sm:text-3xl">
          Shortlisted Candidates
        </h1>
        <p className="mt-1 text-gray-500 dark:text-gray-400">
          Candidates you've bookmarked for later
        </p>
      </div>

      {isLoading ? (
        <Loader />
      ) : candidates.length === 0 ? (
        <EmptyState
          title="No shortlisted candidates"
          description="Star a candidate from Find Candidates to see them here."
        />
      ) : (
        <>
          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            {candidates.map((candidate) => (
              <CandidateCard
                key={candidate.user_id}
                candidate={candidate}
                onViewProfile={setViewingUserId}
                isShortlisted
                onToggleShortlist={unshortlistMutation.mutate}
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
