import { QueryClientProvider } from "@tanstack/react-query";
import { Toaster } from "sonner";
import { queryClient } from "./queryClient";
import { AuthProvider } from "@/context/AuthContext";
import { EchoProvider } from "@/context/EchoContext";
import { ThemeProvider } from "@/context/ThemeContext";

export function Providers({ children }) {
  return (
    <ThemeProvider>
      <QueryClientProvider client={queryClient}>
        <AuthProvider>
          {/* EchoProvider must be inside AuthProvider so it can read isAuthenticated */}
          <EchoProvider>{children}</EchoProvider>
        </AuthProvider>
        <Toaster position="bottom-right" richColors />
      </QueryClientProvider>
    </ThemeProvider>
  );
}
