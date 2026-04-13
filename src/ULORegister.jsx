import { useState } from "react";
import circleImg  from "./assets/images/circle1.png";
import circleImg2 from "./assets/images/circle2.png";
import circleImg3 from "./assets/images/circle3.png";

export default function ULORegister({ onBack }) {
  const [form, setForm] = useState({
    firstName: "",
    middleName: "",
    lastName: "",
    studentId: "",
    password: "",
  });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = () => {
    const { firstName, middleName, lastName, studentId, password } = form;
    if (!firstName || !middleName || !lastName || !studentId || !password) {
      alert("Please fill in all fields.");
      return;
    }
    alert(`Registered successfully! Welcome, ${firstName} ${lastName}.`);
    onBack();
  };

  return (
    <div className="reg-page">

      <img src={circleImg}  className="reg-c1" />
      <img src={circleImg2} className="reg-c2" />
      <img src={circleImg3} className="reg-c3" />

      {/* LEFT SIDE */}
      <div className="reg-left">
        <h1 className="reg-school">Unibersidad ng Lungsod ng Olongapo</h1>
      </div>

      {/* RIGHT SIDE */}
      <div className="reg-right">
        <div className="reg-card">
          <h2 className="reg-title">Register</h2>

          <input className="reg-input" name="firstName"  placeholder="First Name*"  value={form.firstName}  onChange={handleChange} />
          <input className="reg-input" name="middleName" placeholder="Middle Name*" value={form.middleName} onChange={handleChange} />
          <input className="reg-input" name="lastName"   placeholder="Last Name*"   value={form.lastName}   onChange={handleChange} />
          <input className="reg-input" name="studentId"  placeholder="Student ID*"  value={form.studentId}  onChange={handleChange} />
          <input className="reg-input" name="password" type="password" placeholder="Password*" value={form.password} onChange={handleChange} />

          <button className="reg-btn" onClick={handleSubmit}>Sign up</button>

          <p className="reg-back" onClick={onBack}>Already have an account?</p>
        </div>
      </div>

      <style>{`
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body, #root { height: 100%; width: 100%; }

        .reg-page {
          width: 100%;
          min-height: 100vh;
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 40px 80px;
          overflow: hidden;
          position: relative;
          background: #fff;
        }

        .reg-c1 {
          position: fixed; top: -100px; left: -60px;
          width: 340px; z-index: 0; pointer-events: none;
        }
        .reg-c2 {
          position: fixed; bottom: -120px; left: -80px;
          width: 480px; z-index: 0; pointer-events: none;
        }
        .reg-c3 {
          position: fixed; bottom: -120px; left: 180px;
          width: 480px; z-index: 0; pointer-events: none;
        }

        .reg-left {
          flex: 1;
          position: relative;
          display: flex;
          align-items: center;
          z-index: 1;
        }

        .reg-school {
          font-size: 36px;
          font-weight: bold;
          color: #1e2d7d;
          max-width: 420px;
          line-height: 1.3;
        }

        .reg-right {
          flex: 1;
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
          z-index: 3;
        }

        .reg-card {
          width: 100%;
          max-width: 360px;
          padding: 35px;
          border-radius: 12px;
          border: 2px solid #2d2a7f;
          background: #f5f5f5;
          display: flex;
          flex-direction: column;
          gap: 14px;
        }

        .reg-title {
          text-align: center;
          font-weight: bold;
          margin-bottom: 4px;
          color: #1a1a2e;
        }

        .reg-input {
          width: 100%;
          padding: 11px 14px;
          border-radius: 6px;
          border: 1px solid #bfbfbf;
          background: #eeeeee;
          font-size: 14px;
        }
        .reg-input:focus { outline: none; border-color: #2d2a7f; background: #ffffff; }

        .reg-btn {
          padding: 12px;
          background: #2d2a7f;
          color: white;
          border: none;
          border-radius: 6px;
          font-weight: bold;
          cursor: pointer;
          margin-top: 4px;
        }
        .reg-btn:hover { background: #3d3db0; }

        .reg-back {
          text-align: center;
          font-size: 12px;
          color: #555;
          cursor: pointer;
          text-decoration: underline;
        }

        @media (max-width: 900px) {
          .reg-page { flex-direction: column; padding: 30px; }
          .reg-left { justify-content: center; }
          .reg-school { font-size: 22px; text-align: center; }
          .reg-right { width: 100%; }
        }
        @media (max-width: 500px) {
          .reg-card { padding: 20px; }
          .reg-school { font-size: 18px; }
        }
      `}</style>
    </div>
  );
}