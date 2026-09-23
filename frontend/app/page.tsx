import { redirect } from "next/navigation";

// The home page has no content of its own: always go to the journal list.
export default function HomePage() {
  redirect("/journals");
}
