import { useState, useEffect } from "react";
import { Popover } from "radix-ui";
import { ChevronLeft, ChevronRight, CalendarIcon } from "lucide-react";

const DAYS = ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"];
const MONTHS = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

function buildCalendar(year, month) {
  const first = new Date(year, month, 1).getDay();
  const total = new Date(year, month + 1, 0).getDate();
  const cells = [];
  for (let i = 0; i < first; i++) cells.push(null);
  for (let d = 1; d <= total; d++) cells.push(d);
  return cells;
}

function formatDisplay(dateStr) {
  if (!dateStr) return "";
  const [y, m, d] = dateStr.split("-").map(Number);
  return `${MONTHS[m - 1]} ${d}, ${y}`;
}

export default function DatePicker({
  value,
  onChange,
  placeholder = "Pick a date",
  disabled = false,
}) {
  const today = new Date();
  const parsed = value ? new Date(value + "T00:00:00") : null;

  const [view, setView] = useState({
    year: parsed?.getFullYear() ?? today.getFullYear(),
    month: parsed?.getMonth() ?? today.getMonth(),
  });
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (value) {
      const d = new Date(value + "T00:00:00");
      setView({ year: d.getFullYear(), month: d.getMonth() });
    }
  }, [value]);

  const cells = buildCalendar(view.year, view.month);

  const prev = () =>
    setView((v) => {
      const d = new Date(v.year, v.month - 1, 1);
      return { year: d.getFullYear(), month: d.getMonth() };
    });

  const next = () =>
    setView((v) => {
      const d = new Date(v.year, v.month + 1, 1);
      return { year: d.getFullYear(), month: d.getMonth() };
    });

  const select = (day) => {
    if (!day) return;
    const m = String(view.month + 1).padStart(2, "0");
    const d = String(day).padStart(2, "0");
    onChange(`${view.year}-${m}-${d}`);
    setOpen(false);
  };

  const isSelected = (day) => {
    if (!day || !parsed) return false;
    return (
      parsed.getFullYear() === view.year &&
      parsed.getMonth() === view.month &&
      parsed.getDate() === day
    );
  };

  const isToday = (day) => {
    if (!day) return false;
    return (
      today.getFullYear() === view.year &&
      today.getMonth() === view.month &&
      today.getDate() === day
    );
  };

  return (
    <Popover.Root open={open} onOpenChange={disabled ? undefined : setOpen}>
      <Popover.Trigger asChild>
        <button
          type="button"
          disabled={disabled}
          className={`flex w-full items-center justify-between rounded-xl border px-3.5 py-2.5 text-sm outline-none transition
            ${
              disabled
                ? "cursor-not-allowed border-gray-200 bg-gray-50 text-gray-400 dark:border-gray-800 dark:bg-gray-800/40 dark:text-gray-600"
                : "border-gray-200 bg-gray-50 text-gray-900 hover:border-indigo-300 hover:bg-white focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:border-indigo-600 dark:focus:border-indigo-500 dark:focus:bg-gray-900 dark:focus:ring-indigo-950/50"
            }`}
        >
          <span className={value ? "" : "text-gray-400 dark:text-gray-500"}>
            {value ? formatDisplay(value) : placeholder}
          </span>
          <CalendarIcon
            size={15}
            className="shrink-0 text-gray-400 dark:text-gray-500"
          />
        </button>
      </Popover.Trigger>

      <Popover.Portal>
        <Popover.Content
          sideOffset={6}
          align="start"
          className="z-50 w-72 rounded-2xl border border-gray-200 bg-white p-4 shadow-xl dark:border-gray-700 dark:bg-gray-900"
        >
          {/* Header */}
          <div className="mb-3 flex items-center justify-between">
            <button
              type="button"
              onClick={prev}
              className="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
            >
              <ChevronLeft size={16} />
            </button>
            <span className="text-sm font-semibold text-gray-900 dark:text-white">
              {MONTHS[view.month]} {view.year}
            </span>
            <button
              type="button"
              onClick={next}
              className="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
            >
              <ChevronRight size={16} />
            </button>
          </div>

          {/* Day headers */}
          <div className="mb-1 grid grid-cols-7 text-center">
            {DAYS.map((d) => (
              <span
                key={d}
                className="text-xs font-medium text-gray-400 dark:text-gray-500"
              >
                {d}
              </span>
            ))}
          </div>

          {/* Day cells */}
          <div className="grid grid-cols-7 gap-y-1 text-center">
            {cells.map((day, i) => (
              <button
                key={i}
                type="button"
                disabled={!day}
                onClick={() => select(day)}
                className={`mx-auto flex h-8 w-8 items-center justify-center rounded-full text-sm transition
                  ${!day ? "invisible" : ""}
                  ${
                    isSelected(day)
                      ? "bg-indigo-600 font-semibold text-white"
                      : isToday(day)
                        ? "border border-indigo-400 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950"
                        : "text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                  }`}
              >
                {day}
              </button>
            ))}
          </div>

          {/* Clear */}
          {value && (
            <button
              type="button"
              onClick={() => {
                onChange("");
                setOpen(false);
              }}
              className="mt-3 w-full rounded-lg py-1.5 text-xs text-gray-400 hover:text-red-500 dark:text-gray-500 dark:hover:text-red-400"
            >
              Clear date
            </button>
          )}
        </Popover.Content>
      </Popover.Portal>
    </Popover.Root>
  );
}
