import { NavLink } from "react-router-dom";

const TopTabs = ({ tabs = [] }) => {
  return (
    <div className="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
      <div className="mx-auto flex max-w-5xl overflow-x-auto px-4 sm:px-6">
        {tabs.map(({ label, to, badge }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              isActive
                ? "shrink-0 border-b-2 border-indigo-600 px-4 py-3 text-sm font-semibold text-indigo-600 dark:border-indigo-400 dark:text-indigo-400"
                : "shrink-0 border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            }
          >
            <span className="flex items-center gap-1.5">
              {label}
              {badge > 0 && (
                <span className="inline-flex h-4 min-w-4 items-center justify-center rounded-full bg-indigo-600 px-1 text-[9px] font-bold text-white">
                  {badge > 9 ? "9+" : badge}
                </span>
              )}
            </span>
          </NavLink>
        ))}
      </div>
    </div>
  );
};

export default TopTabs;
