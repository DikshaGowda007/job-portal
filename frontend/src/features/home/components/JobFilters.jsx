import { JOB_TYPES, WORK_MODES, EXP_LEVELS } from "@/utils/constants";
import FilterGroup from "./FilterGroup";

export default function JobFilters({ filters, onToggle }) {
  return (
    <aside className="hidden w-60 shrink-0 lg:block">
      <div className="sticky top-24">
      <div className="space-y-4">
        <FilterGroup
          title="Job Type"
          items={JOB_TYPES}
          selected={filters.job_type}
          onToggle={(v) => onToggle("job_type", v)}
          format={(v) => v.replace(/_/g, " ")}
        />
        <FilterGroup
          title="Work Mode"
          items={WORK_MODES}
          selected={filters.work_mode}
          onToggle={(v) => onToggle("work_mode", v)}
          format={(v) => v.charAt(0).toUpperCase() + v.slice(1)}
        />
        <FilterGroup
          title="Experience"
          items={EXP_LEVELS}
          selected={filters.experience_level}
          onToggle={(v) => onToggle("experience_level", v)}
        />
      </div>
      </div>
    </aside>
  );
}
