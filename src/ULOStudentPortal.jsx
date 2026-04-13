import { useState } from "react";
import buildingImg from "./assets/images/building.png";
import circleImg from "./assets/images/circle1.png";
import circleImg2 from "./assets/images/circle2.png";
import circleImg3 from "./assets/images/circle3.png";
import logo from "./assets/images/logo.png";

export default function ULOStudentPortal({ onLogin, onRegister }) {
  const [showPassword, setShowPassword] = useState(false);

  return (
    <div className="page">

      <img src={circleImg}  className="bg-c1" />
      <img src={circleImg2} className="bg-c2" />
      <img src={circleImg3} className="bg-c3" />

      {/* LEFT SIDE */}
      <div className="leftPanel">
        <img src={logo} className="logo" />
        <h1 className="schoolName">Unibersidad ng Lungsod ng Olongapo</h1>
        <img src={buildingImg} className="buildingImg" />
      </div>

      {/* RIGHT SIDE */}
      <div className="rightPanel">
        <div className="card">
          <h2 className="title">Student Portal</h2>

          <div className="inputGroup">
            <span className="inputIcon">👤</span>
            <input className="input" type="text" placeholder="Student ID" />
          </div>

          <div className="inputGroup">
            <span className="inputIcon">🔒</span>
            <input
              className="input"
              type={showPassword ? "text" : "password"}
              placeholder="Password"
            />
            <span className="eyeIcon" onClick={() => setShowPassword(!showPassword)}>
              👁️
            </span>
          </div>

          <button className="loginBtn" onClick={onLogin}>Log in</button>

          <p className="register" onClick={onRegister}>
            Not yet registered?
          </p>
        </div>
      </div>

      <style>{`
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html, body, #root { height: 100%; width: 100%; }

        .page {
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

        .bg-c1 {
          position: fixed;
          top: -100px;
          left: -60px;
          width: 340px;
          z-index: 0;
          pointer-events: none;
        }
        .bg-c2 {
          position: fixed;
          bottom: -120px;
          left: -80px;
          width: 420px;
          z-index: 0;
          pointer-events: none;
        }
        .bg-c3 {
          position: fixed;
          bottom: -120px;
          right: -80px;
          width: 420px;
          z-index: 0;
          pointer-events: none;
        }

        .leftPanel {
          flex: 1;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: flex-start;
          gap: 16px;
          position: relative;
          z-index: 1;
        }

        .logo { width: 90px; }

        .schoolName {
          font-size: 22px;
          font-weight: bold;
          color: #1e2d7d;
          max-width: 380px;
          line-height: 1.3;
        }

        .buildingImg {
          width: 100%;
          max-width: 480px;
          position: relative;
          z-index: 1;
        }

        .rightPanel {
          flex: 1;
          display: flex;
          justify-content: center;
          align-items: center;
          position: relative;
          z-index: 2;
        }

        .card {
          width: 100%;
          max-width: 340px;
          padding: 35px;
          border-radius: 12px;
          border: 2px solid #2d2a7f;
          background: #f5f5f5;
          display: flex;
          flex-direction: column;
          gap: 18px;
        }

        .title { text-align: center; font-weight: bold; color: #1a1a2e; }

        .inputGroup { position: relative; width: 100%; }

        .input {
          width: 100%;
          padding: 12px 40px;
          border-radius: 6px;
          border: 1px solid #bfbfbf;
          background: #eeeeee;
          font-size: 14px;
        }
        .input:focus { outline: none; border-color: #2d2a7f; background: #ffffff; }

        .inputIcon {
          position: absolute;
          left: 12px;
          top: 50%;
          transform: translateY(-50%);
          opacity: 0.6;
          font-size: 14px;
        }

        .eyeIcon {
          position: absolute;
          right: 12px;
          top: 50%;
          transform: translateY(-50%);
          cursor: pointer;
          opacity: 0.6;
          font-size: 14px;
        }

        .loginBtn {
          padding: 12px;
          background: #2d2a7f;
          color: white;
          border: none;
          border-radius: 6px;
          font-weight: bold;
          cursor: pointer;
          font-size: 15px;
        }
        .loginBtn:hover { background: #3d3db0; }

        .register {
          text-align: center;
          font-size: 12px;
          color: #555;
          cursor: pointer;
          text-decoration: underline;
        }

        @media (max-width: 900px) {
          .page { flex-direction: column; padding: 30px; gap: 30px; }
          .leftPanel { align-items: center; width: 100%; }
          .schoolName { text-align: center; }
          .buildingImg { max-width: 100%; }
          .rightPanel { width: 100%; }
        }
        @media (max-width: 500px) {
          .card { padding: 20px; }
          .schoolName { font-size: 16px; }
          .logo { width: 60px; }
        }
      `}</style>
    </div>
  );
}