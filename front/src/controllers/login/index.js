import { useForm } from "react-hook-form";
import { useNavigate, useLocation } from "react-router-dom";
import { useEffect, useState } from "react";
import RegisterLink from "../../views/login/registerLink";
import FormField from "../../views/common/formField";
import FormButton from "../../views/common/formButton";
import FormTitle from "../../views/common/formTitle";
import LoginModel from "../../models/login";
import ErrorRequest from "../../views/common/errorRequest";
import {
  saveToLocalStorage,
  getFromLocalStorage,
  removeFromLocalStorage,
} from "../../services/localeStorageUtils";

const Login = () => {
  const [errorLogin, setErrorLogin] = useState("");
  const navigate = useNavigate();
  const location = useLocation();
  const searchParams = new URLSearchParams(location.search);
  const isLogout = searchParams.get("logout");
  const userToken = getFromLocalStorage("userToken");

  useEffect(() => {
    if (userToken && isLogout) {
      removeFromLocalStorage("userToken");
      navigate("/login");
    } else if (userToken) {
      navigate("/profile");
    }
  }, [userToken, isLogout, navigate]);

  const {
    handleSubmit,
    register,
    formState: { errors, isSubmitting },
  } = useForm();

  const onSubmit = async (values) => {
    const loginResult = await LoginModel.login(values);
    if (loginResult) {
      setErrorLogin("");
      saveToLocalStorage("userToken", loginResult);
      navigate("/profile");
    }
    setErrorLogin("Incorrect email or password");
  };

  const emailValidator = register("email", {
    required: "Email is required",
    pattern: {
      value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
      message: "Invalid email format",
    },
  });

  const passwordValidator = register("password", {
    required: "Password is required",
  });

  return (
    <>
      <FormTitle text={"Login"} />
      <form onSubmit={handleSubmit(onSubmit)}>
        <FormField
          error={errors.email}
          id={"email"}
          placeholder={"email"}
          label={"Email"}
          validator={emailValidator}
        />
        <FormField
          error={errors.password}
          id={"password"}
          placeholder={"password"}
          label={"Password"}
          validator={passwordValidator}
          type={"password"}
        />
        <RegisterLink />
        <FormButton isLoading={isSubmitting} label={"Login"} />
        <ErrorRequest message={errorLogin} />
      </form>
    </>
  );
};

export default Login;
