export default function FilterGroup({ title, items, selected, onToggle, format = (v) => v }) {
  return (
    <div className="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <h3 className="mb-3 text-sm font-semibold text-gray-900 dark:text-white">{title}</h3>
      <div className="space-y-2">
        {console.log(items)}
        {items.map((item) => (
          <label
            key={item}
            className="flex cursor-pointer items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
          >
            <input
              type="checkbox"
              checked={selected.includes(item)}
              onChange={() => onToggle(item)}
              className="h-4 w-4 rounded border-gray-300 accent-indigo-600"
            />
            {format(item)}
          </label>
        ))}
      </div>
    </div>
  );
}
