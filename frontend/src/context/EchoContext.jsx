import { createContext, useContext, useEffect, useRef, useState } from "react";
import { useAuth } from "@/context/AuthContext";
import { createEcho } from "@/lib/echo";

const EchoContext = createContext(null);

/**
 * EchoProvider creates a single shared WebSocket connection when the user is
 * authenticated and disconnects it when they log out.
 *
 * All components share this one connection — subscribing to different channels
 * does not open new connections, it just creates new subscriptions on the same
 * WebSocket.
 */
export function EchoProvider({ children }) {
  const { isAuthenticated } = useAuth();
  const [echo, setEcho] = useState(null);
  const echoRef = useRef(null);

  useEffect(() => {
    if (isAuthenticated) {
      // A misconfigured/missing Reverb env var must not crash the whole app —
      // without this, an uncaught error here unmounts the entire React tree
      // (there's no error boundary), leaving a blank screen after login.
      try {
        const instance = createEcho();
        echoRef.current = instance;
        setEcho(instance);
      } catch (err) {
        console.error("Failed to initialize real-time connection:", err);
      }
    } else {
      // Disconnect and clean up when the user logs out
      if (echoRef.current) {
        echoRef.current.disconnect();
        echoRef.current = null;
        setEcho(null);
      }
    }

    return () => {
      if (echoRef.current) {
        echoRef.current.disconnect();
        echoRef.current = null;
      }
    };
  }, [isAuthenticated]);

  return <EchoContext.Provider value={echo}>{children}</EchoContext.Provider>;
}

/**
 * useEcho() returns the shared Echo instance.
 * Returns null if the user is not authenticated yet.
 */
export function useEcho() {
  return useContext(EchoContext);
}
