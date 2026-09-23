import { z } from "zod";

// Error thrown by apiFetch when the server answers with a non-2xx status.
export class ApiError extends Error {
  status: number;
  fieldErrors: Record<string, string>;

  constructor(
    status: number,
    message: string,
    fieldErrors: Record<string, string> = {},
  ) {
    super(message);
    this.name = "ApiError";
    this.status = status;
    this.fieldErrors = fieldErrors;
  }
}

// Shape of every error body from the PHP backend.
const errorBodySchema = z.object({
  error: z.string(),
  errors: z.record(z.string(), z.string()).optional(),
});

// Call the backend through the Next.js rewrite (/api/... -> PHP).
// On success, validate { data } with the given Zod schema and return data.
export async function apiFetch<T = void>(
  path: string,
  options: RequestInit = {},
  schema?: z.ZodType<T>,
): Promise<T> {
  const response = await fetch(path, {
    ...options,
    headers: { "Content-Type": "application/json" },
  });

  if (!response.ok) {
    const body: unknown = await response.json().catch(() => null);
    const parsed = errorBodySchema.safeParse(body);

    if (parsed.success) {
      throw new ApiError(
        response.status,
        parsed.data.error,
        parsed.data.errors ?? {},
      );
    }

    throw new ApiError(response.status, "Terjadi kesalahan. Coba lagi.");
  }

  // 204 No Content (logout, delete) or no schema given: nothing to parse.
  if (response.status === 204 || !schema) {
    return undefined as T;
  }

  const body: unknown = await response.json();
  return z.object({ data: schema }).parse(body).data;
}

// Turn any caught error into a message that can be shown to the user.
export function getErrorMessage(error: unknown): string {
  if (error instanceof ApiError) {
    return error.message;
  }

  return "Terjadi kesalahan. Periksa koneksi ke server lalu coba lagi.";
}
