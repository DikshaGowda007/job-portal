import { Outlet } from "react-router-dom";
import Navbar from "@/components/common/Navbar";

export default function MinimalLayout() {
  return (
    <div className="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-white">
      <Navbar />
      <main className="mx-auto max-w-5xl px-6 py-8">
        <Outlet />
      </main>
    </div>
  );
}
