import { useEffect } from "react";
import { NavLink, useNavigate, useLocation } from "react-router-dom";
import { LogOut, X } from "lucide-react";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";

const Sidebar = ({ navItems = [], open, onClose }) => {
  const { logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  useEffect(() => {
    onClose?.();
  }, [location.pathname]);

  const handleLogout = async () => {
    await logout();
    navigate(ROUTES.LOGIN, { replace: true });
  };

  return (
    <>
      {/* Mobile overlay */}
      {open && (
        <div
          className="fixed inset-0 z-40 bg-black/40 md:hidden"
          onClick={onClose}
        />
      )}

      <aside
        className={`fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-gray-200 bg-white p-4 transition-transform duration-200 dark:border-gray-800 dark:bg-gray-900 md:static md:min-h-screen md:translate-x-0 md:transition-none ${
          open ? "translate-x-0" : "-translate-x-full"
        }`}
      >
        {/* Close button — mobile only */}
        <button
          onClick={onClose}
          className="mb-2 self-end rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800 md:hidden"
        >
          <X size={18} />
        </button>

        <nav className="flex-1 space-y-2">
          {navItems.map(({ label, icon: Icon, to, badge }) => (
            <NavLink
              key={to}
              to={to}
              className={({ isActive }) =>
                isActive
                  ? "flex cursor-pointer items-center gap-3 rounded-xl bg-indigo-600 p-3 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                  : "flex cursor-pointer items-center gap-3 rounded-xl p-3 transition hover:bg-gray-100 dark:hover:bg-gray-800"
              }
            >
              {({ isActive }) => (
                <>
                  <Icon size={20} />
                  <span className="flex-1">{label}</span>
                  {badge > 0 && (
                    <span className={`inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1 text-[10px] font-bold ${
                      isActive ? "bg-white text-indigo-600" : "bg-indigo-600 text-white"
                    }`}>
                      {badge > 9 ? "9+" : badge}
                    </span>
                  )}
                </>
              )}
            </NavLink>
          ))}
        </nav>

        <button
          onClick={handleLogout}
          className="mt-4 flex w-full items-center gap-3 rounded-xl p-3 text-gray-600 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
        >
          <LogOut size={20} />
          <span className="font-medium">Logout</span>
        </button>
      </aside>
    </>
  );
};

export default Sidebar;
