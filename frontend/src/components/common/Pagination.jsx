export default function Pagination({ page, totalPages, onPageChange }) {
  if (totalPages <= 1) return null;

  return (
    <div className="flex items-center gap-2 justify-end mt-4">
      <button
        onClick={() => onPageChange(page - 1)}
        disabled={page <= 1}
        className="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm hover:bg-gray-100 disabled:opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"
      >
        Previous
      </button>
      <span className="text-sm text-gray-500 dark:text-gray-400">
        {page} / {totalPages}
      </span>
      <button
        onClick={() => onPageChange(page + 1)}
        disabled={page >= totalPages}
        className="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm hover:bg-gray-100 disabled:opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:hover:bg-gray-800"
      >
        Next
      </button>
    </div>
  );
}
