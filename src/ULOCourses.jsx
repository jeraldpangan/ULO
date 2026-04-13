import { useState } from "react";

const tabs = ["All", "Currently Enrolled", "Finished"];

const courses = [
  { code: "CS101", name: "Introduction to Computing", units: 3, status: "Finished", grade: "1.25", semester: "2023-2024, 1st Sem" },
  { code: "CS102", name: "Computer Programming 1", units: 3, status: "Finished", grade: "1.50", semester: "2023-2024, 1st Sem" },
  { code: "CS103", name: "Discrete Mathematics", units: 3, status: "Finished", grade: "1.75", semester: "2023-2024, 2nd Sem" },
  { code: "CS201", name: "Data Structures & Algorithms", units: 3, status: "Finished", grade: "1.25", semester: "2024-2025, 1st Sem" },
  { code: "CS202", name: "Object-Oriented Programming", units: 3, status: "Finished", grade: "1.00", semester: "2024-2025, 1st Sem" },
  { code: "CS301", name: "Web Development", units: 3, status: "Currently Enrolled", grade: "", semester: "2025-2026, 2nd Sem" },
  { code: "CS302", name: "Database Management Systems", units: 3, status: "Currently Enrolled", grade: "", semester: "2025-2026, 2nd Sem" },
  { code: "CS303", name: "Automata Theory", units: 3, status: "Currently Enrolled", grade: "", semester: "2025-2026, 2nd Sem" },
  { code: "CS304", name: "Computer Networks", units: 3, status: "Currently Enrolled", grade: "", semester: "2025-2026, 2nd Sem" },
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
        <line x1="8" y1="9" x2="16" y2="9" />
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
    id: "profile",
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
    "Currently Enrolled": { bg: "#ebf8ff", color: "#2b6cb0", border: "#bee3f8" },
    Finished: { bg: "#f0fff4", color: "#38a169", border: "#c6f6d5" },
  };
  const style = colors[status] || { bg: "#f7fafc", color: "#718096", border: "#e2e8f0" };
  return (
    <span
      style={{
        background: style.bg,
        color: style.color,
        border: `1px solid ${style.border}`,
        borderRadius: "20px",
        padding: "2px 10px",
        fontSize: "11px",
        fontWeight: "700",
        whiteSpace: "nowrap",
      }}
    >
      {status}
    </span>
  );
}

function CourseModal({ course, onClose }) {
  if (!course) return null;

  return (
    <div
      style={styles.overlay}
      onClick={onClose}
    >
      <div
        style={styles.modal}
        onClick={(e) => e.stopPropagation()}
      >
        {/* Modal Header */}
        <div style={styles.modalHeader}>
          <span style={styles.modalCode}>{course.code}</span>
          <button style={styles.modalClose} onClick={onClose}>
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" strokeWidth="2.5" strokeLinecap="round">
              <line x1="1" y1="1" x2="13" y2="13" />
              <line x1="13" y1="1" x2="1" y2="13" />
            </svg>
          </button>
        </div>

        {/* Modal Body */}
        <div style={styles.modalBody}>
          <div style={styles.modalRow}>
            <span style={styles.modalLabel}>Course Title</span>
            <span style={styles.modalValue}>{course.name}</span>
          </div>
          <div style={styles.modalRow}>
            <span style={styles.modalLabel}>Semester</span>
            <span style={styles.modalValue}>{course.semester}</span>
          </div>
          <div style={styles.modalRow}>
            <span style={styles.modalLabel}>Units</span>
            <span style={styles.modalValue}>{course.units} units</span>
          </div>
          <div style={styles.modalRow}>
            <span style={styles.modalLabel}>Status</span>
            <span style={styles.modalValue}>
              <StatusBadge status={course.status} />
            </span>
          </div>
          <div style={{ ...styles.modalRow, borderBottom: "none" }}>
            <span style={styles.modalLabel}>Grade</span>
            <span
              style={{
                ...styles.modalValue,
                color: course.grade ? "#38a169" : "#a0aec0",
              }}
            >
              {course.grade || "Not yet available"}
            </span>
          </div>
        </div>
      </div>

      <style>{`
        @keyframes modalPopIn {
          from { transform: scale(0.92); opacity: 0; }
          to   { transform: scale(1);    opacity: 1; }
        }
      `}</style>
    </div>
  );
}

export default function ULOCourses({ onNavigate }) {
  const [activeNav, setActiveNav] = useState("grades");
  const [activeTab, setActiveTab] = useState("All");
  const [selectedCourse, setSelectedCourse] = useState(null);

  const filtered =
    activeTab === "All"
      ? courses
      : courses.filter((c) => c.status === activeTab);

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

      {/* Main */}
      <main style={styles.main}>
        <div style={styles.header}>
          <h1 style={styles.pageTitle}>Courses</h1>
          <div style={styles.headerLine} />
        </div>

        {/* Tabs */}
        <div style={styles.tabRow}>
          {tabs.map((tab) => (
            <button
              key={tab}
              style={{
                ...styles.tab,
                ...(activeTab === tab ? styles.tabActive : {}),
              }}
              onClick={() => setActiveTab(tab)}
            >
              {tab}
            </button>
          ))}
        </div>

        {/* Table Card */}
        <div style={styles.card}>
          <div style={styles.tableWrapper}>
            <table style={styles.table}>
              <thead>
                <tr>
                  <th style={styles.th}>Course Code</th>
                  <th style={styles.th}>Course Name</th>
                  <th style={styles.th}>Units</th>
                  <th style={styles.th}>Semester</th>
                  <th style={styles.th}>Status</th>
                  <th style={styles.th}>Grade</th>
                </tr>
              </thead>
              <tbody>
                {filtered.length === 0 ? (
                  <tr>
                    <td
                      colSpan={6}
                      style={{
                        ...styles.td,
                        textAlign: "center",
                        color: "#a0aec0",
                        padding: "32px",
                      }}
                    >
                      No courses found.
                    </td>
                  </tr>
                ) : (
                  filtered.map((c, i) => (
                    <tr
                      key={i}
                      style={{
                        ...(i % 2 === 0 ? styles.trEven : styles.trOdd),
                        cursor: "pointer",
                      }}
                      onClick={() => setSelectedCourse(c)}
                      onMouseEnter={(e) => {
                        Array.from(e.currentTarget.cells).forEach(
                          (cell) => (cell.style.background = "#fff8e1")
                        );
                      }}
                      onMouseLeave={(e) => {
                        Array.from(e.currentTarget.cells).forEach(
                          (cell) => (cell.style.background = "")
                        );
                      }}
                    >
                      <td style={{ ...styles.td, fontWeight: "700", color: "#1a2056" }}>
                        {c.code}
                      </td>
                      <td style={styles.td}>{c.name}</td>
                      <td style={{ ...styles.td, textAlign: "center" }}>{c.units}</td>
                      <td style={styles.td}>{c.semester}</td>
                      <td style={{ ...styles.td, textAlign: "center" }}>
                        <StatusBadge status={c.status} />
                      </td>
                      <td
                        style={{
                          ...styles.td,
                          textAlign: "center",
                          fontWeight: "700",
                          color: c.grade ? "#38a169" : "#a0aec0",
                        }}
                      >
                        {c.grade || "—"}
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>
        </div>
      </main>

      {/* Course Detail Modal */}
      <CourseModal
        course={selectedCourse}
        onClose={() => setSelectedCourse(null)}
      />

      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        button { cursor: pointer; border: none; background: none; }
      `}</style>
    </div>
  );
}

const styles = {
  app: {
    display: "flex",
    minHeight: "100vh",
    fontFamily: "'Nunito', sans-serif",
    background: "#f7f8fc",
  },
  sidebar: {
    width: "64px",
    background: "#1a2056",
    display: "flex",
    flexDirection: "column",
    alignItems: "center",
    paddingTop: "16px",
    paddingBottom: "16px",
    position: "fixed",
    top: 0,
    left: 0,
    height: "100vh",
    zIndex: 100,
  },
  sidebarTop: { marginBottom: "24px" },
  logoBox: {
    width: "40px",
    height: "40px",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
  },
  nav: {
    display: "flex",
    flexDirection: "column",
    gap: "6px",
    flex: 1,
  },
  navBtn: {
    width: "44px",
    height: "44px",
    borderRadius: "10px",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    color: "#8892c8",
    transition: "all 0.18s",
  },
  navBtnActive: {
    background: "#f5c842",
    color: "#1a2056",
  },
  sidebarBottom: { marginTop: "auto" },
  logoutBtn: {
    width: "44px",
    height: "44px",
    borderRadius: "10px",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    color: "#e53e3e",
    background: "rgba(229,62,62,0.12)",
  },
  main: {
    marginLeft: "64px",
    flex: 1,
    padding: "28px 32px",
    minHeight: "100vh",
  },
  header: {
    display: "flex",
    alignItems: "center",
    gap: "16px",
    marginBottom: "20px",
  },
  pageTitle: {
    fontSize: "26px",
    fontWeight: "800",
    color: "#1a2056",
    whiteSpace: "nowrap",
  },
  headerLine: {
    flex: 1,
    height: "1.5px",
    background: "#e2e8f0",
    borderRadius: "2px",
  },
  tabRow: {
    display: "flex",
    gap: "8px",
    marginBottom: "16px",
  },
  tab: {
    padding: "6px 18px",
    borderRadius: "20px",
    fontSize: "13px",
    fontWeight: "700",
    color: "#4a5568",
    background: "#e2e8f0",
    border: "none",
    cursor: "pointer",
    transition: "all 0.15s",
  },
  tabActive: {
    background: "#f5c842",
    color: "#1a2056",
  },
  card: {
    background: "#fff",
    borderRadius: "12px",
    border: "2px solid #f5c842",
    padding: "0",
    overflow: "hidden",
  },
  tableWrapper: {
    overflowX: "auto",
  },
  table: {
    width: "100%",
    borderCollapse: "collapse",
    fontSize: "13px",
  },
  th: {
    background: "#f7f8fc",
    color: "#4a5568",
    fontWeight: "700",
    padding: "11px 14px",
    textAlign: "left",
    borderBottom: "1.5px solid #e2e8f0",
    whiteSpace: "nowrap",
    fontSize: "12px",
  },
  td: {
    padding: "10px 14px",
    color: "#2d3748",
    fontWeight: "600",
    fontSize: "13px",
    borderBottom: "1px solid #f0f0f0",
  },
  trEven: { background: "#fff" },
  trOdd: { background: "#fafbff" },

  // Modal styles
  overlay: {
    position: "fixed",
    top: 0,
    left: 0,
    width: "100%",
    height: "100%",
    background: "rgba(0,0,0,0.45)",
    zIndex: 200,
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
  },
  modal: {
    background: "#fff",
    borderRadius: "14px",
    border: "2px solid #f5c842",
    width: "340px",
    overflow: "hidden",
    animation: "modalPopIn 0.18s ease",
  },
  modalHeader: {
    background: "#f5c842",
    padding: "12px 18px",
    display: "flex",
    alignItems: "center",
    justifyContent: "space-between",
  },
  modalCode: {
    fontSize: "18px",
    fontWeight: "800",
    color: "#1a2056",
  },
  modalClose: {
    width: "28px",
    height: "28px",
    borderRadius: "50%",
    background: "rgba(26,32,86,0.12)",
    display: "flex",
    alignItems: "center",
    justifyContent: "center",
    cursor: "pointer",
    color: "#1a2056",
    border: "none",
    padding: 0,
  },
  modalBody: {
    padding: "0",
  },
  modalRow: {
    display: "flex",
    alignItems: "center",
    borderBottom: "1px solid #f0f0f0",
    padding: "11px 18px",
    gap: "12px",
  },
  modalLabel: {
    fontSize: "12px",
    color: "#718096",
    fontWeight: "700",
    width: "110px",
    flexShrink: 0,
  },
  modalValue: {
    fontSize: "13px",
    color: "#1a2056",
    fontWeight: "700",
  },
}; 