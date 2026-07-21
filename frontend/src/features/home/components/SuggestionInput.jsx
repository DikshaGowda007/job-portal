import { useEffect, useRef, useState } from "react";
import { useQuery } from "@tanstack/react-query";
import { jobsApi } from "@/api/jobs.api";

export default function SuggestionInput({
  type,
  icon: Icon,
  name,
  placeholder,
  value,
  onChange,
}) {
  const [open, setOpen] = useState(false);
  const [debouncedValue, setDebouncedValue] = useState(value);
  const [highlightedIndex, setHighlightedIndex] = useState(-1);
  const containerRef = useRef(null);

  useEffect(() => {
    const timeout = setTimeout(() => setDebouncedValue(value), 300);
    return () => clearTimeout(timeout);
  }, [value]);

  useEffect(() => {
    const handleClickOutside = (e) => {
      if (containerRef.current && !containerRef.current.contains(e.target)) {
        setOpen(false);
      }
    };
    document.addEventListener("mousedown", handleClickOutside);
    return () => document.removeEventListener("mousedown", handleClickOutside);
  }, []);

  const { data: suggestions = [] } = useQuery({
    queryKey: ["job-suggestions", type, debouncedValue],
    queryFn: () =>
      jobsApi
        .suggestions({ type, query: debouncedValue || undefined })
        .then((r) => r.data?.data?.suggestions ?? []),
    enabled: open,
    staleTime: 60_000,
  });

  const selectSuggestion = (suggestion) => {
    onChange(suggestion);
    setOpen(false);
    setHighlightedIndex(-1);
  };

  const handleKeyDown = (e) => {
    if (!open || suggestions.length === 0) return;

    if (e.key === "ArrowDown") {
      e.preventDefault();
      setHighlightedIndex((i) => (i + 1) % suggestions.length);
    } else if (e.key === "ArrowUp") {
      e.preventDefault();
      setHighlightedIndex((i) => (i <= 0 ? suggestions.length - 1 : i - 1));
    } else if (e.key === "Enter" && highlightedIndex >= 0) {
      e.preventDefault();
      selectSuggestion(suggestions[highlightedIndex]);
    } else if (e.key === "Escape") {
      setOpen(false);
    }
  };

  return (
    <div ref={containerRef} className="relative flex flex-1 items-center gap-2 px-4">
      <Icon size={15} className="shrink-0 text-gray-400" />
      <input
        name={name}
        autoComplete="off"
        placeholder={placeholder}
        value={value}
        onChange={(e) => {
          onChange(e.target.value);
          setHighlightedIndex(-1);
        }}
        onFocus={() => setOpen(true)}
        onKeyDown={handleKeyDown}
        className="h-12 w-full bg-transparent text-sm outline-none placeholder:text-gray-400"
      />

      {open && suggestions.length > 0 && (
        <ul className="absolute left-0 top-full z-50 mt-2 max-h-64 w-full min-w-[16rem] overflow-y-auto rounded-xl border border-gray-200 bg-white py-1 text-left shadow-lg dark:border-gray-700 dark:bg-gray-800">
          {suggestions.map((suggestion, index) => (
            <li key={suggestion}>
              <button
                type="button"
                onClick={() => selectSuggestion(suggestion)}
                onMouseEnter={() => setHighlightedIndex(index)}
                className={`flex w-full items-center gap-2.5 px-4 py-2 text-left text-sm text-gray-700 transition dark:text-gray-200 ${
                  highlightedIndex === index
                    ? "bg-indigo-50 dark:bg-gray-700"
                    : "hover:bg-gray-50 dark:hover:bg-gray-700"
                }`}
              >
                <Icon size={14} className="shrink-0 text-gray-400" />
                {suggestion}
              </button>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
