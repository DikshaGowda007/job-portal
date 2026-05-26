import { Input } from "@/components/ui/input";
import { Eye, EyeOff } from "lucide-react";

export default function PasswordInput({
  show,
  onToggle,
  className = "",
  ...props
}) {
  return (
    <div className="relative">
      <Input
        type={show ? "text" : "password"}
        placeholder="••••••••"
        className={`pr-10 focus-visible:border-indigo-500 focus-visible:ring-indigo-200 dark:focus-visible:ring-indigo-800 ${className}`}
        {...props}
      />
      <button
        type="button"
        onClick={() => onToggle((v) => !v)}
        tabIndex={-1}
        className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
      >
        {show ? <EyeOff size={15} /> : <Eye size={15} />}
      </button>
    </div>
  );
}
