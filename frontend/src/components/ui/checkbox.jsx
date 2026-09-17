import * as CheckboxPrimitive from "@radix-ui/react-checkbox";
import { Check } from "lucide-react";
import { cn } from "@/lib/utils";

function Checkbox({ className, ...props }) {
  return (
    <CheckboxPrimitive.Root
      data-slot="checkbox"
      className={cn(
        "flex h-4 w-4 shrink-0 items-center justify-center rounded-md border border-gray-300 bg-white transition outline-none hover:border-indigo-400 focus-visible:ring-4 focus-visible:ring-indigo-50 data-[state=checked]:border-indigo-600 data-[state=checked]:bg-indigo-600 disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-600 dark:bg-gray-800 dark:hover:border-indigo-500 dark:data-[state=checked]:border-indigo-500 dark:data-[state=checked]:bg-indigo-500 dark:focus-visible:ring-indigo-950/50",
        className,
      )}
      {...props}
    >
      <CheckboxPrimitive.Indicator className="flex items-center justify-center text-white">
        <Check size={11} strokeWidth={3} />
      </CheckboxPrimitive.Indicator>
    </CheckboxPrimitive.Root>
  );
}

export { Checkbox };
