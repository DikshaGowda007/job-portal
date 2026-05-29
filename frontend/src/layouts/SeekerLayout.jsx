import { Outlet } from "react-router-dom";
import Navbar from "@/components/common/Navbar";
import TopTabs from "@/components/common/TopTabs";
import { ROUTES } from "@/utils/routePaths";

const SEEKER_TABS = [
  { label: "My Applications", to: ROUTES.SEEKER_APPLICATIONS },
  { label: "Saved Jobs", to: ROUTES.SEEKER_SAVED_JOBS },
  { label: "My Profile", to: ROUTES.SEEKER_PROFILE },
  { label: "Messages", to: ROUTES.SEEKER_MESSAGES },
];

export default function SeekerLayout() {
  return (
    <div className="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-white">
      <Navbar />
      <TopTabs tabs={SEEKER_TABS} />
      <main className="mx-auto max-w-5xl px-4 py-6 sm:px-6 sm:py-8">
        <Outlet />
      </main>
    </div>
  );
}
