import { useState, useEffect } from "react";
import { Popover } from "radix-ui";
import { ChevronLeft, ChevronRight, CalendarIcon } from "lucide-react";

const DECADE_SIZE = 12;

function decadeStart(year) {
  return Math.floor(year / DECADE_SIZE) * DECADE_SIZE;
}

export default function YearPicker({
  value,
  onChange,
  placeholder = "Pick a year",
  disabled = false,
  minYear = 1970,
  maxYear = new Date().getFullYear() + 5,
}) {
  const currentYear = new Date().getFullYear();
  const parsed = value ? Number(value) : null;

  const [anchor, setAnchor] = useState(decadeStart(parsed ?? currentYear));
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (value) setAnchor(decadeStart(Number(value)));
  }, [value]);

  const years = Array.from(
    { length: DECADE_SIZE },
    (_, i) => anchor + i,
  ).filter((y) => y >= minYear && y <= maxYear);

  const prev = () => setAnchor((a) => a - DECADE_SIZE);
  const next = () => setAnchor((a) => a + DECADE_SIZE);

  const canPrev = anchor - DECADE_SIZE >= decadeStart(minYear);
  const canNext = anchor + DECADE_SIZE <= maxYear;

  const select = (year) => {
    onChange(String(year));
    setOpen(false);
  };

  return (
    <Popover.Root open={open} onOpenChange={disabled ? undefined : setOpen}>
      <Popover.Trigger asChild>
        <button
          type="button"
          disabled={disabled}
          className={`flex w-full items-center justify-between rounded-xl border px-3 py-2.5 text-sm outline-none transition
            ${
              disabled
                ? "cursor-not-allowed border-gray-200 bg-gray-50 text-gray-400 dark:border-gray-800 dark:bg-gray-800/40 dark:text-gray-600"
                : "border-gray-300 bg-white text-gray-900 hover:border-indigo-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:focus:border-indigo-500"
            }`}
        >
          <span className={value ? "" : "text-gray-400 dark:text-gray-500"}>
            {value || placeholder}
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
          className="z-50 w-64 rounded-2xl border border-gray-200 bg-white p-4 shadow-xl dark:border-gray-700 dark:bg-gray-900"
        >
          {/* Header */}
          <div className="mb-3 flex items-center justify-between">
            <button
              type="button"
              onClick={prev}
              disabled={!canPrev}
              className="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 disabled:opacity-30 dark:text-gray-400 dark:hover:bg-gray-800"
            >
              <ChevronLeft size={16} />
            </button>
            <span className="text-sm font-semibold text-gray-900 dark:text-white">
              {anchor} – {Math.min(anchor + DECADE_SIZE - 1, maxYear)}
            </span>
            <button
              type="button"
              onClick={next}
              disabled={!canNext}
              className="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 disabled:opacity-30 dark:text-gray-400 dark:hover:bg-gray-800"
            >
              <ChevronRight size={16} />
            </button>
          </div>

          {/* Year grid */}
          <div className="grid grid-cols-3 gap-1.5">
            {years.map((y) => (
              <button
                key={y}
                type="button"
                onClick={() => select(y)}
                className={`rounded-xl py-2 text-sm font-medium transition
                  ${
                    parsed === y
                      ? "bg-indigo-600 text-white"
                      : y === currentYear
                        ? "border border-indigo-400 text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950"
                        : "text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                  }`}
              >
                {y}
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
              Clear year
            </button>
          )}
        </Popover.Content>
      </Popover.Portal>
    </Popover.Root>
  );
}
