import DashboardLayout from "./DashboardLayout";
import { LayoutDashboard, Briefcase, Users } from "lucide-react";
import { ROUTES } from "@/utils/routePaths";

const navItems = [
  { label: "Dashboard", icon: LayoutDashboard, to: ROUTES.RECRUITER_DASHBOARD },
  { label: "My Jobs", icon: Briefcase, to: ROUTES.RECRUITER_JOBS },
  { label: "Applications", icon: Users, to: ROUTES.RECRUITER_APPLICATIONS },
];

export default function RecruiterLayout() {
  return <DashboardLayout navItems={navItems} />;
}
