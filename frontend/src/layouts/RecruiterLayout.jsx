import DashboardLayout from "./DashboardLayout";
import { LayoutDashboard, Briefcase, Users, MessageSquare } from "lucide-react";
import { ROUTES } from "@/utils/routePaths";

const navItems = [
  { label: "Dashboard", icon: LayoutDashboard, to: ROUTES.RECRUITER_DASHBOARD },
  { label: "My Jobs", icon: Briefcase, to: ROUTES.RECRUITER_JOBS },
  { label: "Applications", icon: Users, to: ROUTES.RECRUITER_APPLICATIONS },
  { label: "Messages",     icon: MessageSquare,    to: ROUTES.RECRUITER_MESSAGES },
];

export default function RecruiterLayout() {
  return <DashboardLayout navItems={navItems} />;
}
