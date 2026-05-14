import { createRoot } from "react-dom/client";
import "./index.css";
import { RouterProvider } from "react-router-dom";
import router from "./routes";
import { Providers } from "./app/providers";

createRoot(document.getElementById("root")).render(
  <Providers>
    <RouterProvider router={router} />
  </Providers>,
);
