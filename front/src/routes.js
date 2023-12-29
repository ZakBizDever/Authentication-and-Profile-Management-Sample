import React from "react";
import { Routes, Route } from "react-router-dom";
import HomePage from "./pages/home";
import LoginPage from "./pages/login";
import RegistrationPage from "./pages/regsitration";
import ProfilePage from "./pages/profile";
import NotFoundPage from "./pages/404";

const MainRoutes = () => {
  return (
    <Routes>
      <Route exact path="/" element={<HomePage />} />
      <Route exact path="/login" element={<LoginPage />} />
      <Route exact path="/register" element={<RegistrationPage />} />
      <Route exact path="/profile" element={<ProfilePage />} />
      <Route path="*" element={<NotFoundPage />} />
    </Routes>
  );
};

export default MainRoutes;
