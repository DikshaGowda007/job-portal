import { useRef, useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";
import SearchBar from "./SearchBar";
import { KeyRound, LogOut, User } from "lucide-react";

const Navbar = () => {
  const { user, role, logout } = useAuth();
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

  return (
    <header className="sticky top-0 z-50 border-b border-gray-200 bg-white/80 px-6 py-4 backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/80">
      <div className="flex items-center justify-between gap-6">
        <h1 className="whitespace-nowrap text-2xl font-bold text-indigo-600 dark:text-indigo-400">
          Job Portal
        </h1>

        <SearchBar />

        <div className="relative flex items-center gap-3" ref={dropdownRef}>
          {user?.name && (
            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
              {user.name}
            </span>
          )}

          <button
            onClick={() => setOpen((v) => !v)}
            className="flex size-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600 transition hover:bg-indigo-200 dark:bg-indigo-500/20 dark:text-indigo-400 dark:hover:bg-indigo-500/30"
          >
            {user?.name?.charAt(0)?.toUpperCase() ?? "?"}
          </button>

          {open && (
            <div className="absolute right-0 top-12 z-50 w-48 rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-700 dark:bg-gray-900">
              <div className="border-b border-gray-100 px-4 py-3 dark:border-gray-800">
                <p className="truncate text-xs font-medium text-gray-900 dark:text-white">
                  {user?.name}
                </p>
                <p className="truncate text-xs text-gray-400">{user?.email}</p>
              </div>

              <div className="p-1">
                {role === ROLES.JOB_SEEKER && (
                  <Link
                    to={ROUTES.SEEKER_PROFILE}
                    onClick={() => setOpen(false)}
                    className="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                  >
                    <User size={14} />
                    My Profile
                  </Link>
                )}
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
      </div>
    </header>
  );
};

export default Navbar;
