import { createRoot } from "react-dom/client";
import "./index.css";
import { RouterProvider } from "react-router-dom";
import router from "./routes";
import { Providers } from "./app/providers";
import ErrorBoundary from "./components/common/ErrorBoundary";

createRoot(document.getElementById("root")).render(
  <ErrorBoundary>
    <Providers>
      <RouterProvider router={router} />
    </Providers>
  </ErrorBoundary>,
);
