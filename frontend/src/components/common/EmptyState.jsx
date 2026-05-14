import { Inbox } from "lucide-react";

export default function EmptyState({ title = "No results", description = "", action }) {
  return (
    <div className="flex flex-col items-center justify-center py-16 gap-3 text-center">
      <Inbox size={40} className="text-gray-300 dark:text-gray-600" />
      <p className="font-medium text-gray-700 dark:text-gray-300">{title}</p>
      {description && <p className="text-sm text-gray-500 dark:text-gray-400">{description}</p>}
      {action}
    </div>
  );
}
