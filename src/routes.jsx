import ULOStudentPortal from './ULOStudentPortal'
import ULORegister from './ULORegister'
import ULODashboard from './ULODashboard'
import ULOCourses from './ULOCourses'
import ULOEnrollment from './ULOEnrollment'
import ULOAccount from './ULOAccount'

export const routes = {
  login:      (nav) => <ULOStudentPortal onLogin={() => nav("dashboard")} onRegister={() => nav("register")} />,
  register:   (nav) => <ULORegister onBack={() => nav("login")} />,
  dashboard:  (nav) => <ULODashboard onNavigate={nav} />,
  courses:    (nav) => <ULOCourses onNavigate={nav} />,
  enrollment: (nav) => <ULOEnrollment onNavigate={nav} />,
  account:    (nav) => <ULOAccount onNavigate={nav} />,
}