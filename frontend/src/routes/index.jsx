import { createBrowserRouter, Navigate } from "react-router-dom";

import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";

import PublicLayout from "@/layouts/PublicLayout";
import SeekerLayout from "@/layouts/SeekerLayout";
import AdminLayout from "@/layouts/AdminLayout";

import ProtectedRoute from "@/routes/ProtectedRoute";
import RoleRoute from "@/routes/RoleRoute";
import GuestRoute from "@/routes/GuestRoute";

import HomePage from "@/features/home/pages/HomePage";
import JobDetailPage from "@/features/home/pages/JobDetailPage";

import SignupPage from "@/features/auth/pages/SignupPage";

import SeekerApplicationsPage from "@/features/seeker/pages/SeekerApplicationsPage";
import SeekerApplicationDetailPage from "@/features/seeker/pages/SeekerApplicationDetailPage";
import SeekerSavedJobsPage from "@/features/seeker/pages/SeekerSavedJobsPage";
import SeekerProfilePage from "@/features/seeker/pages/SeekerProfilePage";
import AdminDashboard from "@/features/admin/pages/AdminDashboard";
import AdminJobsPage from "@/features/admin/pages/AdminJobsPage";
import AdminApplicationsPage from "@/features/admin/pages/AdminApplicationsPage";
import AdminUsersPage from "@/features/admin/pages/AdminUsersPage";
import AdminSubAdminsPage from "@/features/admin/pages/AdminSubAdminsPage";
import AdminCategoriesPage from "@/features/admin/pages/AdminCategoriesPage";

const router = createBrowserRouter([
  // Public layout
  {
    element: <PublicLayout />,
    children: [
      { path: ROUTES.HOME, element: <HomePage /> },
      { path: ROUTES.PUBLIC_JOB_DETAIL, element: <JobDetailPage /> },
    ],
  },
  
  {
    element: <GuestRoute />,
    children: [
      { path: ROUTES.SIGNUP, element: <SignupPage /> },
    ],
  },

  // Protected routes
  {
    element: <ProtectedRoute />,
    children: [
      // Seeker
      {
        element: <RoleRoute allowedRoles={[ROLES.JOB_SEEKER]} />,
        children: [
          {
            element: <SeekerLayout />,
            children: [
              { path: ROUTES.SEEKER_APPLICATIONS, element: <SeekerApplicationsPage /> },
              { path: ROUTES.SEEKER_APPLICATION_DETAIL, element: <SeekerApplicationDetailPage /> },
              { path: ROUTES.SEEKER_SAVED_JOBS,         element: <SeekerSavedJobsPage /> },
              { path: ROUTES.SEEKER_PROFILE,            element: <SeekerProfilePage /> },
            ],
          },
        ],
      },

      // Admin + Sub-Admin
      {
        element: <RoleRoute allowedRoles={[ROLES.ADMIN, ROLES.SUB_ADMIN]} />,
        children: [
          {
            element: <AdminLayout />,
            children: [
              { path: ROUTES.ADMIN_DASHBOARD,    element: <AdminDashboard /> },
              { path: ROUTES.ADMIN_JOBS,         element: <AdminJobsPage /> },
              { path: ROUTES.ADMIN_APPLICATIONS, element: <AdminApplicationsPage /> },
              { path: ROUTES.ADMIN_CATEGORIES,   element: <AdminCategoriesPage /> },
              { path: ROUTES.ADMIN_USERS,        element: <AdminUsersPage /> },
            ],
          },
        ],
      },

      // Admin only
      {
        element: <RoleRoute allowedRoles={[ROLES.ADMIN]} />,
        children: [
          {
            element: <AdminLayout />,
            children: [
              { path: ROUTES.ADMIN_SUB_ADMINS, element: <AdminSubAdminsPage /> },
            ],
          },
        ],
      },
    ],
  },

  { path: "*", element: <Navigate to={ROUTES.HOME} replace /> },
]);

export default router;
