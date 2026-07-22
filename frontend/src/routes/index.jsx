import { createBrowserRouter, Navigate } from "react-router-dom";

import { ROUTES } from "@/utils/routePaths";
import { ROLES } from "@/utils/roles";

import PublicLayout from "@/layouts/PublicLayout";
import SeekerLayout from "@/layouts/SeekerLayout";
import RecruiterLayout from "@/layouts/RecruiterLayout";
import AdminLayout from "@/layouts/AdminLayout";

import ProtectedRoute from "@/routes/ProtectedRoute";
import RoleRoute from "@/routes/RoleRoute";
import GuestRoute from "@/routes/GuestRoute";

import HomePage from "@/features/home/pages/HomePage";
import JobDetailPage from "@/features/home/pages/JobDetailPage";

import LoginPage from "@/features/auth/pages/LoginPage";
import SignupPage from "@/features/auth/pages/SignupPage";
import OtpPage from "@/features/auth/pages/OtpPage";

import SeekerJobsPage from "@/features/seeker/pages/SeekerJobsPage";
import SeekerApplicationsPage from "@/features/seeker/pages/SeekerApplicationsPage";
import SeekerApplicationDetailPage from "@/features/seeker/pages/SeekerApplicationDetailPage";
import SeekerSavedJobsPage from "@/features/seeker/pages/SeekerSavedJobsPage";
import SeekerJobAlertsPage from "@/features/seeker/pages/SeekerJobAlertsPage";
import SeekerProfilePage from "@/features/seeker/pages/SeekerProfilePage";
import SeekerMessagesPage from "@/features/seeker/pages/SeekerMessagesPage";
import SeekerJobDetailPage from "@/features/seeker/pages/SeekerJobDetailPage";

import RecruiterDashboard from "@/features/recruiter/pages/RecruiterDashboard";
import RecruiterJobsPage from "@/features/recruiter/pages/RecruiterJobsPage";
import RecruiterJobFormPage from "@/features/recruiter/pages/RecruiterJobFormPage";
import RecruiterApplicationsPage from "@/features/recruiter/pages/RecruiterApplicationsPage";
import RecruiterCandidatesPage from "@/features/recruiter/pages/RecruiterCandidatesPage";
import RecruiterMessagesPage from "@/features/recruiter/pages/RecruiterMessagesPage";
import RecruiterCompanyProfilePage from "@/features/recruiter/pages/RecruiterCompanyProfilePage";

import AdminDashboard from "@/features/admin/pages/AdminDashboard";
import AdminJobsPage from "@/features/admin/pages/AdminJobsPage";
import AdminApplicationsPage from "@/features/admin/pages/AdminApplicationsPage";
import AdminUsersPage from "@/features/admin/pages/AdminUsersPage";
import AdminSubAdminsPage from "@/features/admin/pages/AdminSubAdminsPage";
import AdminCategoriesPage from "@/features/admin/pages/AdminCategoriesPage";
import AdminAccessRightsPage from "@/features/admin/pages/AdminAccessRightsPage";

import UserProfilePage from "@/features/user/pages/UserProfilePage";
import ForgotPasswordPage from "@/features/auth/pages/ForgotPasswordPage";
import ResetPasswordPage from "@/features/auth/pages/ResetPasswordPage";
import ChangePasswordPage from "@/features/auth/pages/ChangePasswordPage";
import MinimalLayout from "@/layouts/MinimalLayout";

const router = createBrowserRouter([
  // Public layout
  {
    element: <PublicLayout />,
    children: [
      { path: ROUTES.HOME, element: <HomePage /> },
      { path: ROUTES.PUBLIC_JOB_DETAIL, element: <JobDetailPage /> },
    ],
  },

  // Auth pages — redirect logged-in users to their dashboard
  {
    element: <GuestRoute />,
    children: [
      { path: ROUTES.LOGIN, element: <LoginPage /> },
      { path: ROUTES.SIGNUP, element: <SignupPage /> },
    ],
  },
  { path: ROUTES.OTP, element: <OtpPage /> },
  { path: ROUTES.FORGOT_PASSWORD, element: <ForgotPasswordPage /> },
  { path: ROUTES.RESET_PASSWORD, element: <ResetPasswordPage /> },

  // Protected routes
  {
    element: <ProtectedRoute />,
    children: [
      {
        element: <MinimalLayout />,
        children: [
          { path: ROUTES.CHANGE_PASSWORD, element: <ChangePasswordPage /> },
        ],
      },

      // Seeker
      {
        element: <RoleRoute allowedRoles={[ROLES.JOB_SEEKER]} />,
        children: [
          {
            element: <SeekerLayout />,
            children: [
              { path: ROUTES.SEEKER_JOBS,               element: <SeekerJobsPage /> },
              { path: ROUTES.SEEKER_JOB_DETAIL,         element: <SeekerJobDetailPage /> },
              { path: ROUTES.SEEKER_APPLICATIONS,       element: <SeekerApplicationsPage /> },
              { path: ROUTES.SEEKER_APPLICATION_DETAIL, element: <SeekerApplicationDetailPage /> },
              { path: ROUTES.SEEKER_SAVED_JOBS,         element: <SeekerSavedJobsPage /> },
              { path: ROUTES.SEEKER_JOB_ALERTS,         element: <SeekerJobAlertsPage /> },
              { path: ROUTES.SEEKER_PROFILE,            element: <SeekerProfilePage /> },
              { path: ROUTES.SEEKER_MESSAGES,           element: <SeekerMessagesPage /> },
            ],
          },
        ],
      },

      // Recruiter
      {
        element: <RoleRoute allowedRoles={[ROLES.RECRUITER]} />,
        children: [
          {
            element: <RecruiterLayout />,
            children: [
              { path: ROUTES.RECRUITER_DASHBOARD, element: <RecruiterDashboard /> },
              { path: ROUTES.RECRUITER_JOBS, element: <RecruiterJobsPage /> },
              { path: ROUTES.RECRUITER_JOB_ADD, element: <RecruiterJobFormPage /> },
              { path: ROUTES.RECRUITER_JOB_PUBLISH, element: <RecruiterJobFormPage /> },
              { path: ROUTES.RECRUITER_JOB_EDIT, element: <RecruiterJobFormPage /> },
              { path: ROUTES.RECRUITER_APPLICATIONS, element: <RecruiterApplicationsPage /> },
              { path: ROUTES.RECRUITER_CANDIDATES, element: <RecruiterCandidatesPage /> },
              { path: ROUTES.RECRUITER_MESSAGES,     element: <RecruiterMessagesPage /> },
              { path: ROUTES.RECRUITER_PROFILE,      element: <UserProfilePage /> },
              { path: ROUTES.RECRUITER_COMPANY_PROFILE, element: <RecruiterCompanyProfilePage /> },
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
              { path: ROUTES.ADMIN_CATEGORIES,    element: <AdminCategoriesPage /> },
              { path: ROUTES.ADMIN_USERS,         element: <AdminUsersPage /> },
              { path: ROUTES.ADMIN_ACCESS_RIGHTS, element: <AdminAccessRightsPage /> },
              { path: ROUTES.ADMIN_PROFILE,       element: <UserProfilePage /> },
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
