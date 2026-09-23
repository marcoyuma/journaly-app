"use client";

import { useRouter } from "next/navigation";
import JournalForm, { type JournalFormValues } from "@/components/JournalForm";
import { apiFetch } from "@/lib/api";
import { journalSchema } from "@/lib/schemas";

export default function NewJournalPage() {
  const router = useRouter();

  // Called by JournalForm. Errors are thrown back to the form to display.
  async function handleCreate(values: JournalFormValues) {
    const journal = await apiFetch(
      "/api/journals",
      { method: "POST", body: JSON.stringify(values) },
      journalSchema,
    );
    router.push(`/journals/${journal.id}`);
  }

  return (
    <div>
      <h1 className="mb-4 text-2xl font-bold">Jurnal Baru</h1>
      <JournalForm
        onSubmit={handleCreate}
        onCancel={() => router.push("/journals")}
      />
    </div>
  );
}
