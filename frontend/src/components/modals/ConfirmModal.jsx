import { AlertTriangle } from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";

export default function ConfirmModal({
  open,
  onClose,
  onConfirm,
  title,
  description,
  confirmText = "Confirm",
  loading = false,
  danger = false,
}) {
  return (
    <Dialog open={open} onOpenChange={(o) => !o && onClose()}>
      <DialogContent className="max-w-sm rounded-2xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-800 dark:bg-gray-900">
        <DialogHeader>
          <div className="flex flex-col items-center gap-3 text-center">
            {danger && (
              <div className="flex size-12 items-center justify-center rounded-full bg-red-50 dark:bg-red-950/30">
                <AlertTriangle size={22} className="text-red-500" />
              </div>
            )}
            <DialogTitle className="text-lg font-bold text-gray-900 dark:text-white">
              {title}
            </DialogTitle>
            {description && (
              <p className="text-sm text-gray-500 dark:text-gray-400">
                {description}
              </p>
            )}
          </div>
        </DialogHeader>

        <div className="mt-2 flex gap-3">
          <button
            onClick={onClose}
            disabled={loading}
            className="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-50 disabled:opacity-60 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-800"
          >
            Cancel
          </button>
          <button
            onClick={onConfirm}
            disabled={loading}
            className={`flex-1 rounded-xl py-2.5 text-sm font-semibold text-white transition disabled:opacity-60 ${
              danger
                ? "bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-500"
                : "bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400"
            }`}
          >
            {loading ? "Please wait..." : confirmText}
          </button>
        </div>
      </DialogContent>
    </Dialog>
  );
}
