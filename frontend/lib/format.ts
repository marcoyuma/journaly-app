// Format an ISO 8601 timestamp from the API as an Indonesian date in WIB.
const dateFormatter = new Intl.DateTimeFormat("id-ID", {
  dateStyle: "medium",
  timeStyle: "short",
  timeZone: "Asia/Jakarta",
});

export function formatDate(iso: string): string {
  return dateFormatter.format(new Date(iso));
}
