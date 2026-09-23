"use client";

import { useState } from "react";
import { ApiError, getErrorMessage } from "@/lib/api";

export type JournalFormValues = {
  title: string;
  content: string;
};

type JournalFormProps = {
  initialValues?: JournalFormValues;
  onSubmit: (values: JournalFormValues) => Promise<void>;
  onCancel: () => void;
};

// Form used by both "new journal" and "edit journal".
// The parent decides what happens on submit (POST or PUT).
export default function JournalForm({
  initialValues = { title: "", content: "" },
  onSubmit,
  onCancel,
}: JournalFormProps) {
  const [title, setTitle] = useState(initialValues.title);
  const [content, setContent] = useState(initialValues.content);
  const [error, setError] = useState<string | null>(null);
  const [fieldErrors, setFieldErrors] = useState<Record<string, string>>({});
  const [submitting, setSubmitting] = useState(false);

  async function handleSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    setError(null);
    setFieldErrors({});
    setSubmitting(true);

    try {
      await onSubmit({ title, content });
    } catch (err) {
      if (err instanceof ApiError && err.status === 422) {
        setFieldErrors(err.fieldErrors);
      } else {
        setError(getErrorMessage(err));
      }
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      {error && (
        <p className="rounded bg-red-50 p-3 text-sm text-red-700">{error}</p>
      )}

      <div className="space-y-1">
        <label htmlFor="title" className="block text-sm font-medium">
          Judul
        </label>
        <input
          id="title"
          type="text"
          value={title}
          onChange={(event) => setTitle(event.target.value)}
          className="w-full rounded border border-gray-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none"
        />
        {fieldErrors.title && (
          <p className="text-sm text-red-600">{fieldErrors.title}</p>
        )}
      </div>

      <div className="space-y-1">
        <label htmlFor="content" className="block text-sm font-medium">
          Isi
        </label>
        <textarea
          id="content"
          rows={12}
          value={content}
          onChange={(event) => setContent(event.target.value)}
          className="w-full rounded border border-gray-300 bg-white px-3 py-2 focus:border-blue-500 focus:outline-none"
        />
        {fieldErrors.content && (
          <p className="text-sm text-red-600">{fieldErrors.content}</p>
        )}
      </div>

      <div className="flex gap-2">
        <button
          type="submit"
          disabled={submitting}
          className="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50"
        >
          {submitting ? "Menyimpan..." : "Simpan"}
        </button>
        <button
          type="button"
          onClick={onCancel}
          className="rounded border border-gray-300 px-4 py-2 text-sm hover:bg-gray-100"
        >
          Batal
        </button>
      </div>
    </form>
  );
}
