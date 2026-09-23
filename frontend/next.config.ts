import type { NextConfig } from "next";

const phpApiUrl = process.env.PHP_API_URL ?? "http://localhost:8000";

const nextConfig: NextConfig = {
  // Forward /api/* to the PHP server, so the browser only talks to one origin
  // (no CORS needed, and the PHPSESSID cookie works normally).
  async rewrites() {
    return [
      {
        source: "/api/:path*",
        destination: `${phpApiUrl}/:path*`,
      },
    ];
  },
};

export default nextConfig;
