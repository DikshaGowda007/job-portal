const colorMap = {
  blue:   "bg-blue-100 text-blue-600",
  green:  "bg-green-100 text-green-600",
  purple: "bg-purple-100 text-purple-600",
  yellow: "bg-yellow-100 text-yellow-600",
  red:    "bg-red-100 text-red-600",
  indigo: "bg-indigo-100 text-indigo-600",
};

export default function StatsCard({ title, value, icon: Icon, color = "indigo" }) {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm flex items-center gap-4 dark:border-gray-800 dark:bg-gray-900">
      <div className={`rounded-xl p-3 ${colorMap[color] ?? colorMap.indigo}`}>
        <Icon size={22} />
      </div>
      <div>
        <p className="text-sm text-gray-500 dark:text-gray-400">{title}</p>
        <p className="text-2xl font-bold mt-0.5 text-gray-900 dark:text-white">{value}</p>
      </div>
    </div>
  );
}
