import { NavLink, useNavigate } from "react-router-dom";
import { LogOut } from "lucide-react";
import { useAuth } from "@/context/AuthContext";
import { ROUTES } from "@/utils/routePaths";

const Sidebar = ({ navItems = [] }) => {
  const { logout } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    await logout();
    navigate(ROUTES.LOGIN, { replace: true });
  };

  return (
    <aside className="flex min-h-screen w-64 flex-col border-r border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
      <div className="mb-8">
        <h2 className="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
          Dashboard
        </h2>
      </div>

      <nav className="flex-1 space-y-2">
        {navItems.map(({ label, icon: Icon, to }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              isActive
                ? "flex cursor-pointer items-center gap-3 rounded-xl bg-indigo-600 p-3 text-white hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
                : "flex cursor-pointer items-center gap-3 rounded-xl p-3 transition hover:bg-gray-100 dark:hover:bg-gray-800"
            }
          >
            <Icon size={20} />
            <span>{label}</span>
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
  );
};

export default Sidebar;
