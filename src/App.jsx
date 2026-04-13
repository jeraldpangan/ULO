import { useState } from "react"
import { routes } from "./routes"

export default function App() {
  const [page, setPage] = useState("login")
  const navigate = (p) => setPage(p)
  return routes[page]?.(navigate) ?? <div>404: unknown page "{page}"</div>
}