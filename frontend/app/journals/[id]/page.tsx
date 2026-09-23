"use client";

import Link from "next/link";
import { useParams, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import JournalForm, { type JournalFormValues } from "@/components/JournalForm";
import { apiFetch, getErrorMessage } from "@/lib/api";
import { formatDate } from "@/lib/format";
import { type Journal, journalSchema } from "@/lib/schemas";

export default function JournalDetailPage() {
  const { id } = useParams<{ id: string }>();
  const router = useRouter();
  const [journal, setJournal] = useState<Journal | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [editing, setEditing] = useState(false);
  const [deleting, setDeleting] = useState(false);

  useEffect(() => {
    apiFetch(`/api/journals/${id}`, {}, journalSchema)
      .then(setJournal)
      .catch((err: unknown) => setError(getErrorMessage(err)));
  }, [id]);

  // Called by JournalForm. Errors are thrown back to the form to display.
  async function handleUpdate(values: JournalFormValues) {
    const updated = await apiFetch(
      `/api/journals/${id}`,
      { method: "PUT", body: JSON.stringify(values) },
      journalSchema,
    );
    setJournal(updated);
    setEditing(false);
  }

  async function handleDelete() {
    if (!confirm("Hapus jurnal ini? Tindakan ini tidak bisa dibatalkan.")) {
      return;
    }

    setDeleting(true);

    try {
      await apiFetch(`/api/journals/${id}`, { method: "DELETE" });
      router.push("/journals");
    } catch (err) {
      setError(getErrorMessage(err));
      setDeleting(false);
    }
  }

  return (
    <div>
      <Link href="/journals" className="text-sm text-blue-600 hover:underline">
        &larr; Kembali ke daftar
      </Link>

      {error && (
        <p className="mt-4 rounded bg-red-50 p-3 text-sm text-red-700">
          {error}
        </p>
      )}

      {!error && !journal && <p className="mt-4 text-gray-500">Memuat...</p>}

      {journal && editing && (
        <div className="mt-4">
          <h1 className="mb-4 text-2xl font-bold">Edit Jurnal</h1>
          <JournalForm
            initialValues={{ title: journal.title, content: journal.content }}
            onSubmit={handleUpdate}
            onCancel={() => setEditing(false)}
          />
        </div>
      )}

      {journal && !editing && (
        <article className="mt-4 rounded-lg border border-gray-200 bg-white p-6">
          <h1 className="text-2xl font-bold break-words">{journal.title}</h1>
          <p className="mt-1 text-xs text-gray-500">
            Dibuat {formatDate(journal.created_at)} &middot; Diubah{" "}
            {formatDate(journal.updated_at)}
          </p>
          <p className="mt-4 whitespace-pre-wrap break-words">
            {journal.content}
          </p>
          <div className="mt-6 flex gap-2">
            <button
              type="button"
              onClick={() => setEditing(true)}
              className="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
            >
              Edit
            </button>
            <button
              type="button"
              onClick={handleDelete}
              disabled={deleting}
              className="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50 disabled:opacity-50"
            >
              {deleting ? "Menghapus..." : "Hapus"}
            </button>
          </div>
        </article>
      )}
    </div>
  );
}
