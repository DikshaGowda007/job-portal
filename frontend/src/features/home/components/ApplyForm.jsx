import { useRef } from "react";
import PropTypes from "prop-types";
import { Paperclip, UploadCloud, Loader2 } from "lucide-react";
import { CURRENCIES } from "@/utils/constants";

export default function ApplyForm({
  form,
  onChange,
  onSubmit,
  onCancel,
  loading,
  error,
  resumeFilename,
  resumeUploading,
  onResumeUpload,
}) {
  const fileInputRef = useRef(null);

  const handleFileChange = (e) => {
    const file = e.target.files?.[0];
    if (file) onResumeUpload(file);
    e.target.value = "";
  };

  return (
    <form onSubmit={onSubmit} className="space-y-3">
      <p className="text-sm font-semibold text-gray-900 dark:text-white">
        Apply for this job
      </p>

      {/* Resume */}
      <div className="flex flex-col gap-1">
        <label className="text-xs text-gray-500 dark:text-gray-400">
          Resume
        </label>
        {resumeUploading ? (
          <div className="flex items-center gap-2 rounded-xl border border-gray-200 px-3 py-2 text-xs text-gray-500 dark:border-gray-700">
            <Loader2 size={13} className="animate-spin" /> Uploading…
          </div>
        ) : resumeFilename ? (
          <div className="flex items-center justify-between rounded-xl border border-green-200 bg-green-50 px-3 py-2 dark:border-green-900 dark:bg-green-950/30">
            <span className="flex items-center gap-1.5 truncate text-xs text-green-700 dark:text-green-400">
              <Paperclip size={12} />
              {resumeFilename}
            </span>
            <button
              type="button"
              onClick={() => fileInputRef.current?.click()}
              className="ml-2 shrink-0 text-xs text-indigo-600 hover:underline dark:text-indigo-400"
            >
              Replace
            </button>
          </div>
        ) : (
          <button
            type="button"
            onClick={() => fileInputRef.current?.click()}
            className="flex items-center gap-2 rounded-xl border border-dashed border-gray-300 px-3 py-2.5 text-xs text-gray-500 hover:border-indigo-400 hover:text-indigo-600 dark:border-gray-700 dark:hover:border-indigo-500"
          >
            <UploadCloud size={14} /> Upload resume (PDF, DOC)
          </button>
        )}
        <input
          ref={fileInputRef}
          type="file"
          accept=".pdf,.doc,.docx"
          onChange={handleFileChange}
          className="hidden"
        />
      </div>

      {/* Cover letter */}
      <div className="flex flex-col gap-1">
        <label className="text-xs text-gray-500 dark:text-gray-400">
          Cover Letter
        </label>
        <textarea
          rows={3}
          value={form.cover_letter}
          onChange={(e) => onChange("cover_letter", e.target.value)}
          placeholder="Why are you a great fit?"
          className="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800"
        />
      </div>

      {/* Expected salary + currency */}
      <div className="flex flex-col gap-1">
        <label className="text-xs text-gray-500 dark:text-gray-400">
          Expected Salary (optional)
        </label>
        <div className="flex gap-2">
          <input
            type="number"
            value={form.expected_salary}
            onChange={(e) => onChange("expected_salary", e.target.value)}
            placeholder="e.g. 500000"
            className="min-w-0 flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800"
          />
          <select
            value={form.expected_salary_currency}
            onChange={(e) =>
              onChange("expected_salary_currency", e.target.value)
            }
            className="rounded-xl border border-gray-300 bg-white px-2 py-2 text-sm outline-none focus:border-indigo-500 dark:border-gray-700 dark:bg-gray-800"
          >
            {CURRENCIES.map((c) => (
              <option key={c}>{c}</option>
            ))}
          </select>
        </div>
      </div>

      {/* Notice period + experience in a row */}
      <div className="grid grid-cols-2 gap-2">
        <div className="flex flex-col gap-1">
          <label className="text-xs text-gray-500 dark:text-gray-400">
            Notice Period
          </label>
          <input
            value={form.notice_period}
            onChange={(e) => onChange("notice_period", e.target.value)}
            placeholder="e.g. 30 days"
            className="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800"
          />
        </div>
        <div className="flex flex-col gap-1">
          <label className="text-xs text-gray-500 dark:text-gray-400">
            Experience (yrs)
          </label>
          <input
            type="number"
            min={0}
            max={50}
            value={form.experience_years}
            onChange={(e) => onChange("experience_years", e.target.value)}
            placeholder="e.g. 2"
            className="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800"
          />
        </div>
      </div>

      {error && (
        <p className="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-600 dark:border-red-900 dark:bg-red-950 dark:text-red-400">
          {error}
        </p>
      )}

      <div className="flex gap-2">
        <button
          type="submit"
          disabled={loading || resumeUploading}
          className="flex-1 rounded-xl bg-indigo-600 py-2 text-sm font-semibold text-white hover:bg-indigo-700 disabled:opacity-60 dark:bg-indigo-500"
        >
          {loading ? "Submitting…" : "Submit Application"}
        </button>
        <button
          type="button"
          onClick={onCancel}
          className="rounded-xl border border-gray-300 px-3 py-2 text-sm hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-800"
        >
          Cancel
        </button>
      </div>
    </form>
  );
}

ApplyForm.propTypes = {
  form: PropTypes.shape({
    cover_letter: PropTypes.string,
    expected_salary: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    expected_salary_currency: PropTypes.string,
    notice_period: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
    experience_years: PropTypes.oneOfType([PropTypes.string, PropTypes.number]),
  }).isRequired,
  onChange: PropTypes.func.isRequired,
  onSubmit: PropTypes.func.isRequired,
  onCancel: PropTypes.func.isRequired,
  loading: PropTypes.bool.isRequired,
  error: PropTypes.string,
  resumeFilename: PropTypes.string,
  resumeUploading: PropTypes.bool.isRequired,
  onResumeUpload: PropTypes.func.isRequired,
};
