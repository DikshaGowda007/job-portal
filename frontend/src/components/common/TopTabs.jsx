import { NavLink } from "react-router-dom";

const TopTabs = ({ tabs = [] }) => {
  return (
    <div className="border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
      <div className="mx-auto flex max-w-5xl gap-1 px-6">
        {tabs.map(({ label, to }) => (
          <NavLink
            key={to}
            to={to}
            className={({ isActive }) =>
              isActive
                ? "border-b-2 border-indigo-600 px-4 py-3 text-sm font-semibold text-indigo-600 dark:border-indigo-400 dark:text-indigo-400"
                : "border-b-2 border-transparent px-4 py-3 text-sm font-medium text-gray-500 transition hover:text-gray-900 dark:text-gray-400 dark:hover:text-white"
            }
          >
            {label}
          </NavLink>
        ))}
      </div>
    </div>
  );
};

export default TopTabs;
