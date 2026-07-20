import { formatDate } from "@/utils/formatters";
import { FileText, Clock, CalendarClock, MapPin } from "lucide-react";

export default function ApplicationTimeline({ timeline = [] }) {
  if (timeline.length === 0) return null;

  return (
    <div className="space-y-3">
      {timeline.map((entry) => {
        const isSubmission = !entry.previous_status;
        return (
          <div key={entry.id} className="flex gap-3">
            <div
              className={`mt-0.5 flex size-8 shrink-0 items-center justify-center rounded-lg ${
                isSubmission ? "bg-indigo-50 dark:bg-indigo-900/30" : "bg-gray-100 dark:bg-gray-800"
              }`}
            >
              {isSubmission ? (
                <FileText size={13} className="text-indigo-500 dark:text-indigo-400" />
              ) : (
                <Clock size={13} className="text-gray-500 dark:text-gray-400" />
              )}
            </div>
            <div className="flex-1">
              <p className="text-sm text-gray-900 dark:text-white">
                {isSubmission ? (
                  <span className="font-medium">Application submitted</span>
                ) : (
                  <>
                    <span>{entry.previous_status.charAt(0) + entry.previous_status.slice(1).toLowerCase()}</span>
                    <span className="mx-1.5 text-gray-400">→</span>
                    <span className="font-medium">{entry.new_status.charAt(0) + entry.new_status.slice(1).toLowerCase()}</span>
                  </>
                )}
              </p>
              <div className="mt-0.5 flex flex-wrap gap-2 text-xs text-gray-400 dark:text-gray-500">
                <span className="flex items-center gap-1">
                  <Clock size={10} /> {formatDate(entry.created_at)}
                </span>
                {entry.changed_by?.name && <span>by {entry.changed_by.name}</span>}
                {entry.notes && <span className="italic">{entry.notes}</span>}
              </div>
              {entry.interview_scheduled_at && (
                <div className="mt-2 flex flex-wrap items-center gap-3 rounded-lg bg-purple-50 px-3 py-2 text-xs text-purple-700 dark:bg-purple-900/20 dark:text-purple-300">
                  <span className="flex items-center gap-1 font-medium">
                    <CalendarClock size={12} /> {formatDate(entry.interview_scheduled_at)}
                  </span>
                  {entry.interview_location && (
                    <span className="flex items-center gap-1">
                      <MapPin size={12} /> {entry.interview_location}
                    </span>
                  )}
                </div>
              )}
            </div>
          </div>
        );
      })}
    </div>
  );
}
