import { createBrowserRouter, Navigate } from "react-router-dom";

import { ROUTES } from "@/utils/routePaths";

import PublicLayout from "@/layouts/PublicLayout";

import HomePage from "@/features/home/pages/HomePage";
import LoginPage from "@/features/auth/pages/LoginPage";
import SignupPage from "@/features/auth/pages/SignupPage";
import OtpPage from "@/features/auth/pages/OtpPage";

const router = createBrowserRouter([
  // Public layout
  {
    element: <PublicLayout />,
    children: [
      { path: ROUTES.HOME, element: <HomePage /> },
      { path: ROUTES.PUBLIC_JOB_DETAIL, element: <JobDetailPage /> },
    ],
  },

  { path: ROUTES.SIGNUP, element: <SignupPage /> },
  { path: "/otp", element: <OtpPage /> },
  { path: ROUTES.LOGIN, element: <LoginPage /> },

  { path: "*", element: <Navigate to={ROUTES.HOME} replace /> },
]);

export default router;
