import Echo from "laravel-echo";
import Pusher from "pusher-js";
import axiosClient from "@/api/axios";

// Pusher must be on window so Laravel Echo can find the protocol driver
window.Pusher = Pusher;

/**
 * Create a new Echo instance connected to Reverb.
 *
 * We use a custom authorizer instead of the default one so that Echo
 * uses our axiosClient (which already injects the JWT Bearer token via
 * its request interceptor) when authenticating private channels.
 */
export function createEcho() {
  return new Echo({
    broadcaster: "reverb",
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    forceTLS: import.meta.env.VITE_REVERB_SCHEME === "https",
    enabledTransports: ["ws", "wss"],

    // Custom authorizer: uses axiosClient so the JWT token is injected
    // automatically via the existing request interceptor.
    authorizer: (channel) => ({
      authorize: (socketId, callback) => {
        axiosClient
          .post("/broadcasting/auth", {
            socket_id: socketId,
            channel_name: channel.name,
          })
          .then((response) => callback(null, response.data))
          .catch((error) => callback(error, null));
      },
    }),
  });
}
