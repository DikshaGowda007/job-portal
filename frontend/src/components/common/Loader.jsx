export default function Loader({ text = "Loading…" }) {
  return (
    <div className="flex flex-col items-center justify-center py-16 gap-3 text-gray-500 dark:text-gray-400">
      <div className="h-8 w-8 rounded-full border-2 border-indigo-600 border-t-transparent animate-spin dark:border-indigo-400" />
      <span className="text-sm">{text}</span>
    </div>
  );
}
