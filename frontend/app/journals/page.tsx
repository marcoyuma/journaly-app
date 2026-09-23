"use client";

import Link from "next/link";
import { useEffect, useState } from "react";
import { apiFetch, getErrorMessage } from "@/lib/api";
import { formatDate } from "@/lib/format";
import { type Journal, journalListSchema } from "@/lib/schemas";

export default function JournalsPage() {
  const [journals, setJournals] = useState<Journal[] | null>(null);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    apiFetch("/api/journals", {}, journalListSchema)
      .then(setJournals)
      .catch((err: unknown) => setError(getErrorMessage(err)));
  }, []);

  return (
    <div>
      <div className="mb-4 flex items-center justify-between">
        <h1 className="text-2xl font-bold">Daftar Jurnal</h1>
        <Link
          href="/journals/new"
          className="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
        >
          + Jurnal Baru
        </Link>
      </div>

      {error && (
        <p className="rounded bg-red-50 p-3 text-sm text-red-700">{error}</p>
      )}

      {!error && journals === null && <p className="text-gray-500">Memuat...</p>}

      {journals?.length === 0 && (
        <p className="text-gray-500">Belum ada jurnal.</p>
      )}

      <ul className="space-y-3">
        {journals?.map((journal) => (
          <li key={journal.id}>
            <Link
              href={`/journals/${journal.id}`}
              className="block rounded-lg border border-gray-200 bg-white p-4 hover:border-blue-400"
            >
              <h2 className="font-semibold">{journal.title}</h2>
              <p className="mt-1 text-xs text-gray-500">
                Diubah {formatDate(journal.updated_at)}
              </p>
              <p className="mt-2 line-clamp-2 text-sm text-gray-700">
                {journal.content}
              </p>
            </Link>
          </li>
        ))}
      </ul>
    </div>
  );
}
