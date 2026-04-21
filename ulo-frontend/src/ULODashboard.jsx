import { useState } from "react";

const enrollmentHistory = [
  { semester: "2023-2024, 1st Semester", status: "Officially Enrolled", date: "August 8, 2023" },
  { semester: "2023-2024, 2nd Semester", status: "Officially Enrolled", date: "January 20, 2024" },
  { semester: "2023-2024, Midyear",      status: "Not Enlisted",        date: "" },
  { semester: "2024-2025, 1st Semester", status: "Officially Enrolled", date: "July 30, 2024" },
  { semester: "2024-2025, 2nd Semester", status: "Officially Enrolled", date: "January 19, 2025" },
  { semester: "2024-2025, Midyear",      status: "Officially Enrolled", date: "June 15, 2025" },
  { semester: "2025-2026, 1st Semester", status: "Officially Enrolled", date: "August 10, 2025" },
  { semester: "2025-2026, 2nd Semester", status: "Officially Enrolled", date: "January 19, 2026" },
  { semester: "2025-2026, Midyear",      status: "", date: "" },
  { semester: "2026-2027, 1st Semester", status: "", date: "" },
  { semester: "2026-2027, 2nd Semester", status: "", date: "" },
];

const statusLegend = [
  { status: "Not Enlisted",      color: "#e53e3e", description: "You are not done with the Enlistment Process." },
  { status: "Will Not Enroll",   color: "#e53e3e", description: "You marked yourself as will not enroll for the semester OR you've been automatically marked as WILL NOT ENROLL because you have not done the enlistment process." },
  { status: "Officially Enrolled", color: "#38a169", description: "Your enrollment has been approved by the registrar." },
];

const navIcons = [
  {
    id: "dashboard",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
        <rect x="3" y="3" width="8" height="8" rx="1.5" />
        <rect x="13" y="3" width="8" height="8" rx="1.5" />
        <rect x="3" y="13" width="8" height="8" rx="1.5" />
        <rect x="13" y="13" width="8" height="8" rx="1.5" />
      </svg>
    ),
  },
  {
    id: "grades",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <rect x="3" y="3" width="18" height="18" rx="2" />
        <line x1="8" y1="9"  x2="16" y2="9"  />
        <line x1="8" y1="13" x2="16" y2="13" />
        <line x1="8" y1="17" x2="12" y2="17" />
      </svg>
    ),
  },
  {
    id: "enrollment",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
      </svg>
    ),
  },
  {
    id: "account",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
      </svg>
    ),
  },
];

function StatusBadge({ status }) {
  if (!status) return null;
  const colors = {
    "Officially Enrolled": { bg: "#f0fff4", color: "#38a169", border: "#c6f6d5" },
    "Not Enlisted":        { bg: "#fff5f5", color: "#e53e3e", border: "#fed7d7" },
    "Will Not Enroll":     { bg: "#fff5f5", color: "#e53e3e", border: "#fed7d7" },
  };
  const style = colors[status] || { bg: "#f7fafc", color: "#718096", border: "#e2e8f0" };
  return (
    <span style={{
      background: style.bg, color: style.color,
      border: `1px solid ${style.border}`,
      borderRadius: "20px", padding: "2px 10px",
      fontSize: "11px", fontWeight: "700", whiteSpace: "nowrap",
    }}>
      {status}
    </span>
  );
}

export default function ULODashboard({ onNavigate }) {
  const [activeNav, setActiveNav] = useState("dashboard");

  return (
    <div style={styles.app}>
      {/* Sidebar */}
      <aside style={styles.sidebar}>
        <div style={styles.sidebarTop}>
          <div style={styles.logoBox}>
            <svg width="28" height="28" viewBox="0 0 90 90" fill="none">
              <circle cx="45" cy="45" r="42" fill="#f5c842" />
              <path d="M45 3 A42 42 0 0 1 87 45" stroke="#1e2d7d" strokeWidth="14" fill="none" strokeLinecap="round" />
              <path d="M87 45 A42 42 0 0 1 45 87" stroke="#1e2d7d" strokeWidth="14" fill="none" strokeLinecap="round" />
              <circle cx="45" cy="45" r="20" fill="#f5c842" />
            </svg>
          </div>
        </div>

        <nav style={styles.nav}>
          {navIcons.map((item) => (
            <button
              key={item.id}
              style={{
                ...styles.navBtn,
                ...(activeNav === item.id ? styles.navBtnActive : {}),
              }}
              onClick={() => {
  if (item.id === "grades") {
    onNavigate("courses");
  } else if (item.id === "enrollment") {
    onNavigate("enrollment");
  } else if (item.id === "account") {
    onNavigate("account");
  } else if (item.id === "dashboard") {
    onNavigate("dashboard");
  } else {
    setActiveNav(item.id);
  }
}}
              title={item.id}
            >
              {item.icon}
            </button>
          ))}
        </nav>

        <div style={styles.sidebarBottom}>
          <button
            style={styles.logoutBtn}
            title="Logout"
            onClick={() => onNavigate("login")}
          >
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
              <polyline points="16 17 21 12 16 7" />
              <line x1="21" y1="12" x2="9" y2="12" />
            </svg>
          </button>
        </div>
      </aside>

      {/* Main Content */}
      <main style={styles.main}>
        <div style={styles.header}>
          <h1 style={styles.pageTitle}>Dashboard</h1>
          <div style={styles.headerLine} />
        </div>

        <div style={styles.grid}>
          {/* Left Column */}
          <div style={styles.leftCol}>
            <div style={styles.card}>
              <h2 style={styles.cardTitle}>Announcement</h2>
              <div style={styles.divider} />
              <div style={styles.emptyState}>
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" strokeWidth="1.5">
                  <path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3z" />
                  <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                <p style={styles.emptyText}>No announcements yet</p>
              </div>
            </div>

            <div style={{ ...styles.card, flex: 1 }}>
              <h2 style={styles.cardTitle}>Announcement</h2>
              <div style={styles.divider} />
            </div>
          </div>

          {/* Right Column */}
          <div style={styles.rightCol}>
            <div style={styles.card}>
              <h2 style={styles.cardTitle}>Enrollment History</h2>
              <div style={styles.tableWrapper}>
                <table style={styles.table}>
                  <thead>
                    <tr>
                      <th style={styles.th}>Academic Year/Semester</th>
                      <th style={styles.th}>Enrollment Status</th>
                      <th style={styles.th}>Date of Enrollment</th>
                    </tr>
                  </thead>
                  <tbody>
                    {enrollmentHistory.map((row, i) => (
                      <tr key={i} style={i % 2 === 0 ? styles.trEven : styles.trOdd}>
                        <td style={styles.td}>{row.semester}</td>
                        <td style={{ ...styles.td, textAlign: "center" }}>
                          <StatusBadge status={row.status} />
                        </td>
                        <td style={{ ...styles.td, textAlign: "center", color: "#4a5568", fontWeight: "600" }}>
                          {row.date}
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>

            <div style={styles.card}>
              <table style={styles.table}>
                <thead>
                  <tr>
                    <th style={styles.th}>Enrollment Status</th>
                    <th style={styles.th}>Description</th>
                  </tr>
                </thead>
                <tbody>
                  {statusLegend.map((row, i) => (
                    <tr key={i} style={i % 2 === 0 ? styles.trEven : styles.trOdd}>
                      <td style={{ ...styles.td, textAlign: "center" }}>
                        <StatusBadge status={row.status} />
                      </td>
                      <td style={{ ...styles.td, color: "#4a5568", fontSize: "12px" }}>
                        {row.description}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>

      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        button { cursor: pointer; border: none; background: none; }
        button:hover { opacity: 0.8; }
      `}</style>
    </div>
  );
}

const styles = {
  app:          { display: "flex", minHeight: "100vh", fontFamily: "'Nunito', sans-serif", background: "#f7f8fc" },
  sidebar:      { width: "64px", background: "#1a2056", display: "flex", flexDirection: "column", alignItems: "center", paddingTop: "16px", paddingBottom: "16px", position: "fixed", top: 0, left: 0, height: "100vh", zIndex: 100 },
  sidebarTop:   { marginBottom: "24px" },
  logoBox:      { width: "40px", height: "40px", display: "flex", alignItems: "center", justifyContent: "center" },
  nav:          { display: "flex", flexDirection: "column", gap: "6px", flex: 1 },
  navBtn:       { width: "44px", height: "44px", borderRadius: "10px", display: "flex", alignItems: "center", justifyContent: "center", color: "#8892c8", transition: "all 0.18s" },
  navBtnActive: { background: "#f5c842", color: "#1a2056" },
  sidebarBottom:{ marginTop: "auto" },
  logoutBtn:    { width: "44px", height: "44px", borderRadius: "10px", display: "flex", alignItems: "center", justifyContent: "center", color: "#e53e3e", background: "rgba(229,62,62,0.12)" },
  main:         { marginLeft: "64px", flex: 1, padding: "28px 32px", minHeight: "100vh" },
  header:       { display: "flex", alignItems: "center", gap: "16px", marginBottom: "24px" },
  pageTitle:    { fontSize: "26px", fontWeight: "800", color: "#1a2056", whiteSpace: "nowrap" },
  headerLine:   { flex: 1, height: "1.5px", background: "#e2e8f0", borderRadius: "2px" },
  grid:         { display: "grid", gridTemplateColumns: "1fr 1.5fr", gap: "20px", alignItems: "start" },
  leftCol:      { display: "flex", flexDirection: "column", gap: "20px", height: "100%" },
  rightCol:     { display: "flex", flexDirection: "column", gap: "20px" },
  card:         { background: "#fff", borderRadius: "12px", border: "2px solid #f5c842", padding: "18px 18px 14px", display: "flex", flexDirection: "column", gap: "10px" },
  cardTitle:    { fontSize: "16px", fontWeight: "800", color: "#f5c842", letterSpacing: "-0.2px" },
  divider:      { height: "1.5px", background: "#e2e8f0", borderRadius: "2px", marginBottom: "4px" },
  emptyState:   { display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", padding: "32px 0", gap: "10px" },
  emptyText:    { color: "#a0aec0", fontSize: "14px", fontWeight: "600" },
  tableWrapper: { overflowX: "auto", borderRadius: "8px", border: "1px solid #e2e8f0" },
  table:        { width: "100%", borderCollapse: "collapse", fontSize: "12px" },
  th:           { background: "#f7f8fc", color: "#4a5568", fontWeight: "700", padding: "9px 12px", textAlign: "left", borderBottom: "1.5px solid #e2e8f0", whiteSpace: "nowrap", fontSize: "12px" },
  td:           { padding: "8px 12px", color: "#2d3748", fontWeight: "600", fontSize: "12px", borderBottom: "1px solid #f0f0f0" },
  trEven:       { background: "#fff" },
  trOdd:        { background: "#fafbff" },
};