import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "@/context/AuthContext";
import { ROLES } from "@/utils/roles";
import { ROUTES } from "@/utils/routePaths";

const ROLE_DASHBOARD = {
  [ROLES.ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.SUB_ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.RECRUITER]: ROUTES.RECRUITER_DASHBOARD,
  [ROLES.JOB_SEEKER]: ROUTES.SEEKER_DASHBOARD,
};

export default function GuestRoute() {
  const { isAuthenticated, role } = useAuth();
  if (isAuthenticated) {
    return <Navigate to={ROLE_DASHBOARD[role] ?? ROUTES.HOME} replace />;
  }
  return <Outlet />;
}
