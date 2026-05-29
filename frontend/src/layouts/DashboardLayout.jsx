import { useState } from "react";
import { Outlet } from "react-router-dom";
import Navbar from "@/components/common/Navbar";
import Sidebar from "@/components/common/Sidebar";

const DashboardLayout = ({ navItems = [] }) => {
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <div className="min-h-screen bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-white">
      <Navbar onMenuClick={() => setSidebarOpen((v) => !v)} />

      <div className="flex">
        <Sidebar
          navItems={navItems}
          open={sidebarOpen}
          onClose={() => setSidebarOpen(false)}
        />

        <main className="flex-1 overflow-auto p-4 sm:p-6">
          <Outlet />
        </main>
      </div>
    </div>
  );
};

export default DashboardLayout;
