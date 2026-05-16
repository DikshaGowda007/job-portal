export default function JobDetailRow({ label, children }) {
  return (
    <div className="flex items-start justify-between gap-2">
      <dt className="text-gray-500 dark:text-gray-400">{label}</dt>
      <dd className="text-right font-medium text-gray-900 dark:text-white">
        {children}
      </dd>
    </div>
  );
}
