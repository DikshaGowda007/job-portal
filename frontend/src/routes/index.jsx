import { createBrowserRouter, Navigate } from "react-router-dom";

import { ROUTES } from "@/utils/routePaths";

import PublicLayout from "@/layouts/PublicLayout";

import HomePage from "@/features/home/pages/HomePage";

const router = createBrowserRouter([
  // Public layout
  {
    element: <PublicLayout />,
    children: [{ path: ROUTES.HOME, element: <HomePage /> }],
  },

  { path: "*", element: <Navigate to={ROUTES.HOME} replace /> },
]);

export default router;
