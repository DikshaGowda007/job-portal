import { useRef, useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { ROUTES } from "@/utils/routePaths";
import { useAuth } from "@/context/AuthContext";
import { useTheme } from "@/context/ThemeContext";
import { ROLES } from "@/utils/roles";
import {
  FileText,
  Bookmark,
  User,
  KeyRound,
  LogOut,
  LayoutDashboard,
  Moon,
  Sun,
} from "lucide-react";

const SEEKER_MENU = [
  { label: "My Applications", icon: FileText, to: ROUTES.SEEKER_APPLICATIONS },
  { label: "Saved Jobs", icon: Bookmark, to: ROUTES.SEEKER_SAVED_JOBS },
  { label: "My Profile", icon: User, to: ROUTES.SEEKER_PROFILE },
];

const ROLE_DASHBOARD = {
  [ROLES.ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.SUB_ADMIN]: ROUTES.ADMIN_DASHBOARD,
  [ROLES.RECRUITER]: ROUTES.RECRUITER_DASHBOARD,
};

export default function PublicNavbar() {
  const { isAuthenticated, role, user, logout } = useAuth();
  const { theme, toggle } = useTheme();
  const navigate = useNavigate();
  const [open, setOpen] = useState(false);
  const dropdownRef = useRef(null);

  useEffect(() => {
    const handleClickOutside = (e) => {
      if (dropdownRef.current && !dropdownRef.current.contains(e.target)) {
        setOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const handleLogout = async () => {
    setOpen(false);
    await logout();
    navigate(ROUTES.LOGIN, { replace: true });
  };

  const isSeeker = role === ROLES.JOB_SEEKER;

  return (
    <header className="sticky top-0 z-50 border-b border-gray-200 bg-white/80 backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/80">
      <div className="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
        <Link
          to={ROUTES.HOME}
          className="text-xl font-bold text-indigo-600 dark:text-indigo-400"
        >
          Job Portal
        </Link>

        <nav className="hidden items-center gap-6 text-sm font-medium text-gray-500 md:flex dark:text-gray-400">
          <Link
            to={ROUTES.HOME}
            className="transition-colors hover:text-gray-900 dark:hover:text-white"
          >
            Browse Jobs
          </Link>
        </nav>

        <div className="flex items-center gap-3">
          <button
            onClick={toggle}
            className="flex size-9 shrink-0 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
            aria-label="Toggle theme"
          >
            {theme === "dark" ? <Sun size={18} /> : <Moon size={18} />}
          </button>

          {isAuthenticated ? (
            isSeeker ? (
              <div className="relative" ref={dropdownRef}>
                <div className="flex items-center gap-2">
                  {user?.name && (
                    <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                      {user.name}
                    </span>
                  )}
                  <button
                    onClick={() => setOpen((v) => !v)}
                    className="flex size-9 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-200 dark:bg-indigo-500/20 dark:text-indigo-400 dark:hover:bg-indigo-500/30"
                  >
                    {user?.name?.charAt(0)?.toUpperCase() ?? "?"}
                  </button>
                </div>

                {open && (
                  <div className="absolute right-0 top-12 z-50 w-52 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
                    <div className="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                      <p className="truncate text-xs font-medium text-gray-900 dark:text-white">
                        {user?.name}
                      </p>
                      <p className="truncate text-xs text-gray-400">
                        {user?.email}
                      </p>
                    </div>

                    <div className="p-1">
                      {SEEKER_MENU.map(({ label, icon: Icon, to }) => (
                        <Link
                          key={to}
                          to={to}
                          onClick={() => setOpen(false)}
                          className="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                        >
                          <Icon size={14} />
                          {label}
                        </Link>
                      ))}
                    </div>

                    <div className="border-t border-gray-100 p-1 dark:border-gray-800">
                      <Link
                        to={ROUTES.CHANGE_PASSWORD}
                        onClick={() => setOpen(false)}
                        className="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                      >
                        <KeyRound size={14} />
                        Change Password
                      </Link>
                      <button
                        onClick={handleLogout}
                        className="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-red-600 transition hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30"
                      >
                        <LogOut size={14} />
                        Logout
                      </button>
                    </div>
                  </div>
                )}
              </div>
            ) : (
              <div className="flex items-center gap-3">
                <Link
                  to={ROLE_DASHBOARD[role] ?? ROUTES.LOGIN}
                  className="flex items-center gap-1.5 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                >
                  <LayoutDashboard size={14} />
                  Dashboard
                </Link>
                <button
                  onClick={handleLogout}
                  className="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
                >
                  Logout
                </button>
              </div>
            )
          ) : (
            <>
              <Link
                to={ROUTES.LOGIN}
                className="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium transition-colors hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
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
