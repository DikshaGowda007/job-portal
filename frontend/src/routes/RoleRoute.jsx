import { Navigate, Outlet } from "react-router-dom";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";

export default function RoleRoute({ allowedRoles }) {
  const { role } = useAuth();
  return allowedRoles.includes(role) ? <Outlet /> : <Navigate to={ROUTES.LOGIN} replace />;
}
