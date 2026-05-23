import DashboardLayout from "./DashboardLayout";
import {
  LayoutDashboard,
  Briefcase,
  Users,
  Shield,
  Tag,
  ClipboardList,
} from "lucide-react";
import { ROUTES } from "@/utils/routePaths";
import { useAuth } from "@/context/AuthContext";
import { ROLES } from "@/utils/roles";

const ALL_NAV = [
  { label: "Dashboard", icon: LayoutDashboard, to: ROUTES.ADMIN_DASHBOARD },
  { label: "Jobs", icon: Briefcase, to: ROUTES.ADMIN_JOBS },
  { label: "Applications", icon: ClipboardList, to: ROUTES.ADMIN_APPLICATIONS },
  { label: "Categories", icon: Tag, to: ROUTES.ADMIN_CATEGORIES },
  { label: "Users", icon: Users, to: ROUTES.ADMIN_USERS },
  {
    label: "Sub-Admins",
    icon: Shield,
    to: ROUTES.ADMIN_SUB_ADMINS,
    adminOnly: true,
  },
];

export default function AdminLayout() {
  const { role } = useAuth();
  const navItems = ALL_NAV.filter(
    (item) => !item.adminOnly || role === ROLES.ADMIN,
  );
  return <DashboardLayout navItems={navItems} />;
}
