export default function JobSection({ title, children }) {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <h2 className="mb-4 text-base font-semibold text-gray-900 dark:text-white">
        {title}
      </h2>
      {children}
    </div>
  );
}
