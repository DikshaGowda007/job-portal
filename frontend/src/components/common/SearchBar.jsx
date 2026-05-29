import { Search, MapPin } from "lucide-react";

const SearchBar = () => {
  return (
    <div className="flex w-full max-w-2xl overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900">

      <div className="flex flex-1 items-center gap-2 px-3">
        <Search size={15} className="shrink-0 text-gray-400" />
        <input
          type="text"
          placeholder="Job title or keyword"
          className="h-10 w-full bg-transparent text-sm outline-none placeholder:text-gray-400 dark:placeholder:text-gray-500"
        />
      </div>

      <div className="my-2 w-px bg-gray-200 dark:bg-gray-700" />

      <div className="flex flex-1 items-center gap-2 px-3">
        <MapPin size={15} className="shrink-0 text-gray-400" />
        <input
          type="text"
          placeholder="City, state, or zip"
          className="h-10 w-full bg-transparent text-sm outline-none placeholder:text-gray-400 dark:placeholder:text-gray-500"
        />
      </div>

      <button className="shrink-0 bg-indigo-600 px-5 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
        Find Jobs
      </button>

    </div>
  );
};

export default SearchBar;