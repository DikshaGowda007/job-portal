import { ChevronDown } from "lucide-react";

export const inp = {
  className:
    "w-full rounded-xl border border-gray-200 bg-gray-50 px-3.5 py-2.5 text-sm text-gray-900 outline-none transition placeholder:text-gray-400 hover:border-indigo-300 hover:bg-white focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:placeholder:text-gray-500 dark:hover:border-indigo-600 dark:hover:bg-gray-800 dark:focus:border-indigo-500 dark:focus:bg-gray-900 dark:focus:ring-indigo-950/50",
};

export function StyledSelect({
  value,
  onChange,
  required,
  wrapperClassName = "",
  children,
}) {
  return (
    <div className={`relative ${wrapperClassName}`}>
      <select
        value={value}
        onChange={onChange}
        required={required}
        className={`${inp.className} appearance-none pr-9`}
      >
        {children}
      </select>
      <ChevronDown
        size={14}
        className="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
      />
    </div>
  );
}

export function SelectField({
  label,
  value,
  onChange,
  required,
  options,
  placeholder,
}) {
  return (
    <Field label={label}>
      <StyledSelect value={value} onChange={onChange} required={required}>
        {placeholder && <option value="">{placeholder}</option>}
        {options.map((o) => (
          <option key={o.value} value={o.value}>
            {o.label}
          </option>
        ))}
      </StyledSelect>
    </Field>
  );
}

export function Section({
  title,
  icon: Icon,
  iconColor,
  iconBg,
  count,
  children,
}) {
  return (
    <div className="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
      <div className="flex items-center gap-3 border-b border-gray-100 px-6 py-4 dark:border-gray-800">
        {Icon && (
          <div
            className={`flex h-8 w-8 shrink-0 items-center justify-center rounded-lg ${iconBg}`}
          >
            <Icon size={15} className={iconColor} />
          </div>
        )}
        <h2 className="flex-1 text-sm font-semibold text-gray-900 dark:text-white">
          {title}
        </h2>
        {count > 0 && (
          <span className="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-500 dark:bg-gray-800 dark:text-gray-400">
            {count}
          </span>
        )}
      </div>
      <div className="space-y-4 p-6">{children}</div>
    </div>
  );
}

export function GroupLabel({ children }) {
  return (
    <p className="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-gray-500">
      {children}
    </p>
  );
}

export function Field({ label, children }) {
  return (
    <div className="flex flex-col gap-1.5">
      <label className="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
        {label}
      </label>
      {children}
    </div>
  );
}
