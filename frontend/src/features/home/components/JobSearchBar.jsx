import { Search, MapPin } from "lucide-react";

export default function JobSearchBar({ onSearch }) {
  const handleSubmit = (e) => {
    e.preventDefault();
    const form = new FormData(e.target);
    onSearch(form.get("keyword"), form.get("location"));
  };

  return (
    <section className="border-b border-gray-200 bg-white px-6 py-10 dark:border-gray-800 dark:bg-gray-900">
      <div className="mx-auto max-w-3xl text-center">
        <h1 className="text-4xl font-bold text-gray-900 dark:text-white">
          Find Your Next{" "}
          <span className="text-indigo-600 dark:text-indigo-400">
            Opportunity
          </span>
        </h1>
        <p className="mt-2 text-gray-500 dark:text-gray-400">
          Browse thousands of jobs. No account needed to search.
        </p>

        <form
          onSubmit={handleSubmit}
          className="mx-auto mt-7 flex max-w-3xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
        >
          <div className="flex flex-1 items-center gap-2 px-4">
            <Search size={15} className="shrink-0 text-gray-400" />
            <input
              name="keyword"
              placeholder="Job title or keyword"
              className="h-12 w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
            />
          </div>
          <div className="my-3 w-px bg-gray-200 dark:bg-gray-700" />
          <div className="flex flex-1 items-center gap-2 px-4">
            <MapPin size={15} className="shrink-0 text-gray-400" />
            <input
              name="location"
              placeholder="City, state, or zip"
              className="h-12 w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
            />
          </div>
          <button
            type="submit"
            className="shrink-0 bg-indigo-600 px-6 text-sm font-semibold text-white transition hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 cursor-pointer"
          >
            Find Jobs
          </button>
        </form>
      </div>
    </section>
  );
}
