export function formatDate(dateStr) {
  if (!dateStr) return "—";
  return new Date(dateStr).toLocaleDateString("en-IN", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
}

export function formatSalary(min, max, currency = "INR", salaryType) {
  if (salaryType === "lpa") {
    const toLakh = (n) => (n / 100000).toFixed(n % 100000 === 0 ? 0 : 1);
    if (min && max) return `${toLakh(min)} – ${toLakh(max)} LPA`;
    if (min) return `From ${toLakh(min)} LPA`;
    if (max) return `Up to ${toLakh(max)} LPA`;
    return "Not disclosed";
  }
  const fmt = (n) =>
    new Intl.NumberFormat("en-IN", {
      style: "currency",
      currency,
      maximumFractionDigits: 0,
    }).format(n);
  const SUFFIX = { monthly: "/ month", yearly: "/ year" };
  const suffix = salaryType
    ? ` ${SUFFIX[salaryType] ?? "/ " + salaryType}`
    : "";
  if (min && max) return `${fmt(min)} – ${fmt(max)}${suffix}`;
  if (min) return `From ${fmt(min)}${suffix}`;
  if (max) return `Up to ${fmt(max)}${suffix}`;
  return "Not disclosed";
}

export function capitalize(str = "") {
  return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}
