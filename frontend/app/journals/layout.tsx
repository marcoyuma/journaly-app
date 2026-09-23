"use client";

import Link from "next/link";
import { useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { ApiError, apiFetch, getErrorMessage } from "@/lib/api";
import { type User, userSchema } from "@/lib/schemas";

// Wraps every /journals page: checks the session first, then shows the header.
export default function JournalsLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    apiFetch("/api/auth/me", {}, userSchema)
      .then(setUser)
      .catch((err: unknown) => {
        if (err instanceof ApiError && err.status === 401) {
          router.replace("/login");
        } else {
          setError(getErrorMessage(err));
        }
      });
  }, [router]);

  async function handleLogout() {
    try {
      await apiFetch("/api/auth/logout", { method: "POST" });
    } catch {
      // Session already gone or server error: go to the login page anyway.
    }
    router.replace("/login");
  }

  if (error) {
    return <p className="p-6 text-center text-red-700">{error}</p>;
  }

  if (!user) {
    return <p className="p-6 text-center text-gray-500">Memuat...</p>;
  }

  return (
    <div className="mx-auto w-full max-w-3xl px-4 py-6">
      <header className="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
        <Link href="/journals" className="text-xl font-bold">
          Jurnal Saya
        </Link>
        <div className="flex items-center gap-3 text-sm">
          <span className="text-gray-600">{user.username}</span>
          <button
            type="button"
            onClick={handleLogout}
            className="rounded border border-gray-300 px-3 py-1 hover:bg-gray-100"
          >
            Keluar
          </button>
        </div>
      </header>
      <main>{children}</main>
    </div>
  );
}
