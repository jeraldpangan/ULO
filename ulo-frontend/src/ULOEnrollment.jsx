import { useState } from "react";

const profileData = {
  studentNumber: "2023-00123",
  name: "Juan Dela Cruz",
  program: "Bachelor of Science in Computer Science",
  year: "3rd Year",
  campus: "Main Campus",
  college: "College of Information and Communications Technology",
};

const tabs = ["Profile", "Category 1", "Category 2", "Category 3"];

const profileFields = [
  { label: "Student Number", value: profileData.studentNumber },
  { label: "Full Name", value: profileData.name },
  { label: "Program", value: profileData.program },
  { label: "Year Level", value: profileData.year },
  { label: "Campus", value: profileData.campus },
  { label: "College", value: profileData.college },
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
    id: "account",
    icon: (
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
      </svg>
    ),
  },
];

export default function ULOEnrollment({ onNavigate }) {
  const [activeNav, setActiveNav] = useState("enrollment");
  const [activeTab, setActiveTab] = useState("Profile");

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
            onClick={() => {
                const confirmLogout = window.confirm("Are you sure you want to logout?");
                if (confirmLogout) {
                    onNavigate("login");
                }
                }}
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
          <h1 style={styles.pageTitle}>Enrollment</h1>
          <div style={styles.headerLine} />
        </div>

        <div style={styles.card}>
          {/* Tabs */}
          <div style={styles.tabBar}>
            {tabs.map((tab) => (
              <button
                key={tab}
                style={{
                  ...styles.tabBtn,
                  ...(activeTab === tab ? styles.tabBtnActive : {}),
                }}
                onClick={() => setActiveTab(tab)}
              >
                {tab}
              </button>
            ))}
          </div>

          {/* Tab Content */}
          <div style={styles.tabContent}>
            {activeTab === "Profile" && (
              <div>
                <div style={styles.sectionHeader}>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f5c842" strokeWidth="2" style={{ flexShrink: 0 }}>
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                  </svg>
                  <span style={styles.sectionTitle}>Student Information</span>
                </div>
                <div style={styles.divider} />
                <div style={styles.profileGrid}>
                  {profileFields.map((field) => (
                    <div key={field.label} style={styles.profileField}>
                      <span style={styles.fieldLabel}>{field.label}</span>
                      <span style={styles.fieldValue}>{field.value}</span>
                    </div>
                  ))}
                </div>

                <div style={{ ...styles.sectionHeader, marginTop: "24px" }}>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f5c842" strokeWidth="2" style={{ flexShrink: 0 }}>
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                  </svg>
                  <span style={styles.sectionTitle}>Current Enrollment Status</span>
                </div>
                <div style={styles.divider} />
                <div style={styles.statusBox}>
                  <div style={styles.statusRow}>
                    <span style={styles.fieldLabel}>Academic Year / Semester</span>
                    <span style={styles.fieldValue}>2025–2026, 2nd Semester</span>
                  </div>
                  <div style={styles.statusRow}>
                    <span style={styles.fieldLabel}>Enrollment Status</span>
                    <span style={styles.enrolledBadge}>Officially Enrolled</span>
                  </div>
                  <div style={styles.statusRow}>
                    <span style={styles.fieldLabel}>Date of Enrollment</span>
                    <span style={styles.fieldValue}>January 19, 2026</span>
                  </div>
                </div>
              </div>
            )}

            {activeTab !== "Profile" && (
              <div style={styles.emptyState}>
                <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="#cbd5e0" strokeWidth="1.5">
                  <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                  <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
                <p style={styles.emptyText}>No data available for {activeTab}</p>
              </div>
            )}
          </div>
        </div>
      </main>

      <style>{`
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');
        * { box-sizing: border-box; margin: 0; padding: 0; }
        button { cursor: pointer; border: none; background: none; }
        button:hover { opacity: 0.85; }
      `}</style>
    </div>
  );
}

const styles = {
  app:           { display: "flex", minHeight: "100vh", fontFamily: "'Nunito', sans-serif", background: "#f7f8fc" },
  sidebar:       { width: "64px", background: "#1a2056", display: "flex", flexDirection: "column", alignItems: "center", paddingTop: "16px", paddingBottom: "16px", position: "fixed", top: 0, left: 0, height: "100vh", zIndex: 100 },
  sidebarTop:    { marginBottom: "24px" },
  logoBox:       { width: "40px", height: "40px", display: "flex", alignItems: "center", justifyContent: "center" },
  nav:           { display: "flex", flexDirection: "column", gap: "6px", flex: 1 },
  navBtn:        { width: "44px", height: "44px", borderRadius: "10px", display: "flex", alignItems: "center", justifyContent: "center", color: "#8892c8", transition: "all 0.18s" },
  navBtnActive:  { background: "#f5c842", color: "#1a2056" },
  sidebarBottom: { marginTop: "auto" },
  logoutBtn:     { width: "44px", height: "44px", borderRadius: "10px", display: "flex", alignItems: "center", justifyContent: "center", color: "#e53e3e", background: "rgba(229,62,62,0.12)" },
  main:          { marginLeft: "64px", flex: 1, padding: "28px 32px", minHeight: "100vh" },
  header:        { display: "flex", alignItems: "center", gap: "16px", marginBottom: "24px" },
  pageTitle:     { fontSize: "26px", fontWeight: "800", color: "#1a2056", whiteSpace: "nowrap" },
  headerLine:    { flex: 1, height: "1.5px", background: "#e2e8f0", borderRadius: "2px" },

  card:          { background: "#fff", borderRadius: "12px", border: "2px solid #f5c842", padding: "0", overflow: "hidden" },

  tabBar:        { display: "flex", gap: "0", borderBottom: "1.5px solid #e2e8f0", padding: "0 18px", background: "#fafbff" },
  tabBtn:        { padding: "12px 18px", fontSize: "13px", fontWeight: "700", color: "#a0aec0", background: "none", border: "none", borderBottom: "2.5px solid transparent", cursor: "pointer", transition: "all 0.15s", marginBottom: "-1.5px" },
  tabBtnActive:  { color: "#1a2056", borderBottom: "2.5px solid #f5c842" },

  tabContent:    { padding: "20px 22px 24px" },

  sectionHeader: { display: "flex", alignItems: "center", gap: "8px", marginBottom: "8px" },
  sectionTitle:  { fontSize: "13px", fontWeight: "800", color: "#f5c842", letterSpacing: "0.3px", textTransform: "uppercase" },
  divider:       { height: "1.5px", background: "#e2e8f0", borderRadius: "2px", marginBottom: "14px" },

  profileGrid:   { display: "grid", gridTemplateColumns: "1fr 1fr", gap: "12px 24px" },
  profileField:  { display: "flex", flexDirection: "column", gap: "2px" },
  fieldLabel:    { fontSize: "11px", fontWeight: "700", color: "#a0aec0", textTransform: "uppercase", letterSpacing: "0.4px" },
  fieldValue:    { fontSize: "13px", fontWeight: "700", color: "#2d3748" },

  statusBox:     { display: "flex", flexDirection: "column", gap: "12px", background: "#fafbff", borderRadius: "10px", border: "1px solid #e2e8f0", padding: "14px 18px" },
  statusRow:     { display: "flex", alignItems: "center", justifyContent: "space-between", gap: "12px" },
  enrolledBadge: { background: "#f0fff4", color: "#38a169", border: "1px solid #c6f6d5", borderRadius: "20px", padding: "2px 12px", fontSize: "12px", fontWeight: "700", whiteSpace: "nowrap" },

  emptyState:    { display: "flex", flexDirection: "column", alignItems: "center", justifyContent: "center", padding: "48px 0", gap: "10px" },
  emptyText:     { color: "#a0aec0", fontSize: "14px", fontWeight: "600" },
};

function LogoutModal({ isOpen, onConfirm, onCancel }) {
  if (!isOpen) return null;

  return (
    <div style={styles.overlay} onClick={onCancel}>
      <div style={styles.modal} onClick={(e) => e.stopPropagation()}>
        
        {/* Header */}
        <div style={styles.modalHeader}>
          <span style={styles.modalTitle}>Confirm Logout</span>
        </div>

        {/* Body */}
        <div style={styles.modalBody}>
          <p style={styles.modalText}>
            Are you sure you want to logout?
          </p>

          <div style={styles.modalActions}>
            <button style={styles.cancelBtn} onClick={onCancel}>
              Cancel
            </button>
            <button style={styles.confirmBtn} onClick={onConfirm}>
              Logout
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}