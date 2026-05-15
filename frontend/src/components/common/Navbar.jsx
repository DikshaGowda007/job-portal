import { useAuth } from "@/context/AuthContext";
import SearchBar from "./SearchBar";

const Navbar = () => {
  const { user } = useAuth();

  return (
    <header className="sticky top-0 z-50 border-b border-gray-200 bg-white/80 px-6 py-4 backdrop-blur-sm dark:border-gray-800 dark:bg-gray-900/80">
      <div className="flex items-center justify-between gap-6">

        <h1 className="whitespace-nowrap text-2xl font-bold text-indigo-600 dark:text-indigo-400">
          Job Portal
        </h1>

        <SearchBar />

        <div className="flex items-center gap-3">
          {user?.name && (
            <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
              {user.name}
            </span>
          )}
          <div className="flex size-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">
            {user?.name?.charAt(0)?.toUpperCase() ?? "?"}
          </div>
        </div>

      </div>
    </header>
  );
};

export default Navbar;
