import { Outlet } from "react-router-dom";
import PublicNavbar from "@/components/common/PublicNavbar";

export default function PublicLayout() {
  return (
    <div className="min-h-screen bg-gray-100 dark:bg-gray-950">
      <PublicNavbar />
      <Outlet />
    </div>
  );
}
