import { Link, useNavigate } from "react-router-dom";
import { ROUTES } from "@/utils/routePaths";
import { useAuth } from "@/context/AuthContext";
import { ROLES } from "@/utils/roles";

const ROLE_DASHBOARD = {
  [ROLES.ADMIN]:      ROUTES.ADMIN_DASHBOARD,
  [ROLES.SUB_ADMIN]:  ROUTES.ADMIN_DASHBOARD,
  [ROLES.RECRUITER]:  ROUTES.RECRUITER_DASHBOARD,
  [ROLES.JOB_SEEKER]: ROUTES.SEEKER_DASHBOARD,
};

export default function PublicNavbar() {
  const { isAuthenticated, role, logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate(ROUTES.LOGIN, { replace: true });
  };

  return (
    <header className="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/80">
      <div className="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">

        <Link to={ROUTES.HOME} className="text-xl font-bold text-indigo-600 dark:text-indigo-400">
          Job Portal
        </Link>

        <nav className="hidden items-center gap-6 text-sm font-medium text-gray-500 md:flex dark:text-gray-400">
          <Link to={ROUTES.PUBLIC_JOBS} className="hover:text-gray-900 transition-colors dark:hover:text-white">
            Browse Jobs
          </Link>
        </nav>

        <div className="flex items-center gap-3">
          {isAuthenticated ? (
            <div className="flex items-center gap-3">
              <Link
                to={ROLE_DASHBOARD[role] ?? ROUTES.LOGIN}
                className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
              >
                Dashboard
              </Link>
              <button
                onClick={handleLogout}
                className="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
              >
                Logout
              </button>
            </div>
          ) : (
            <>
              <Link
                to={ROUTES.LOGIN}
                className="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium hover:bg-gray-100 transition-colors dark:border-gray-700 dark:hover:bg-gray-800"
              >
                Login
              </Link>
              <Link
                to={ROUTES.SIGNUP}
                className="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
              >
                Sign Up
              </Link>
            </>
          )}
        </div>
      </div>
    </header>
  );
}
