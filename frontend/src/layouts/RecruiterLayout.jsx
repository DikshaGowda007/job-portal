import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "./DashboardLayout";
import { LayoutDashboard, Briefcase, Users, MessageSquare, UserCircle } from "lucide-react";
import { recruiterApi } from "@/api/recruiter.api";
import { ROUTES } from "@/utils/routePaths";

export default function RecruiterLayout() {
  const { data: conversations = [] } = useQuery({
    queryKey: ["recruiter-conversations"],
    queryFn: () => recruiterApi.getConversations().then((r) => r.data?.data ?? []),
    staleTime: 30_000,
  });

  const unreadCount = conversations.reduce((sum, c) => sum + (c.unread_count ?? 0), 0);

  const navItems = [
    { label: "Dashboard",    icon: LayoutDashboard, to: ROUTES.RECRUITER_DASHBOARD },
    { label: "My Jobs",      icon: Briefcase,        to: ROUTES.RECRUITER_JOBS },
    { label: "Applications", icon: Users,            to: ROUTES.RECRUITER_APPLICATIONS },
    { label: "Messages",     icon: MessageSquare,    to: ROUTES.RECRUITER_MESSAGES, badge: unreadCount },
    { label: "My Profile",   icon: UserCircle,       to: ROUTES.RECRUITER_PROFILE },
  ];

  return <DashboardLayout navItems={navItems} />;
}
