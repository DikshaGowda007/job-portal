import { Outlet } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import Navbar from "@/components/common/Navbar";
import TopTabs from "@/components/common/TopTabs";
import { seekerApi } from "@/api/seeker.api";
import { ROUTES } from "@/utils/routePaths";

export default function SeekerLayout() {
  const { data: conversations = [] } = useQuery({
    queryKey: ["seeker-conversations"],
    queryFn: () => seekerApi.getConversations().then((r) => r.data?.data ?? []),
    staleTime: 30_000,
  });

  const unreadCount = conversations.reduce((sum, c) => sum + (c.unread_count ?? 0), 0);

  const tabs = [
    { label: "Browse Jobs",      to: ROUTES.SEEKER_JOBS },
    { label: "My Applications",  to: ROUTES.SEEKER_APPLICATIONS },
    { label: "Saved Jobs",       to: ROUTES.SEEKER_SAVED_JOBS },
    { label: "My Profile",       to: ROUTES.SEEKER_PROFILE },
    { label: "Messages",         to: ROUTES.SEEKER_MESSAGES, badge: unreadCount },
  ];

  return (
    <div className="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-white">
      <Navbar />
      <TopTabs tabs={tabs} />
      <main className="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8">
        <Outlet />
      </main>
    </div>
  );
}
