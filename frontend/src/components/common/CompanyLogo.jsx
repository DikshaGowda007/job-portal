import { Building2 } from "lucide-react";

const LOGO_COLORS = [
  "bg-indigo-100 text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-400",
  "bg-emerald-100 text-emerald-600 dark:bg-emerald-900/50 dark:text-emerald-400",
  "bg-violet-100 text-violet-600 dark:bg-violet-900/50 dark:text-violet-400",
  "bg-amber-100 text-amber-600 dark:bg-amber-900/50 dark:text-amber-400",
  "bg-rose-100 text-rose-600 dark:bg-rose-900/50 dark:text-rose-400",
  "bg-sky-100 text-sky-600 dark:bg-sky-900/50 dark:text-sky-400",
];

function logoColor(name) {
  const code = name.charCodeAt(0);
  return LOGO_COLORS[(isNaN(code) ? 0 : code) % LOGO_COLORS.length];
}

export default function CompanyLogo({ name = "" }) {
  if (!name) {
    return (
      <div className="flex size-12 shrink-0 items-center justify-center rounded-xl bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500">
        <Building2 size={20} />
      </div>
    );
  }
  return (
    <div
      className={`flex size-12 shrink-0 items-center justify-center rounded-xl text-lg font-bold ${logoColor(name)}`}
    >
      {name.charAt(0).toUpperCase()}
    </div>
  );
}
