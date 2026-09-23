import { z } from "zod";

// id uses z.coerce.number() because PHP/MySQL may send numbers as strings.
export const userSchema = z.object({
  id: z.coerce.number(),
  username: z.string(),
});

export type User = z.infer<typeof userSchema>;

export const journalSchema = z.object({
  id: z.coerce.number(),
  title: z.string(),
  content: z.string(),
  created_at: z.string(),
  updated_at: z.string(),
});

export type Journal = z.infer<typeof journalSchema>;

export const journalListSchema = z.array(journalSchema);
