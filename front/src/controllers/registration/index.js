import { useForm } from "react-hook-form";
import { useNavigate } from "react-router-dom";
import { useState, useEffect } from "react";
import FormField from "../../views/common/formField";
import FormButton from "../../views/common/formButton";
import FormTitle from "../../views/common/formTitle";
import RegistrationModel from "../../models/registration";
import ErrorRequest from "../../views/common/errorRequest";
import { getFromLocalStorage } from "../../services/localeStorageUtils";
import SuccessRegistration from "../../views/registration/successPage";

const Registration = () => {
  const [errorRegister, setErrorRegister] = useState("");
  const navigate = useNavigate();
  const userToken = getFromLocalStorage("userToken");

  useEffect(() => {
    if (userToken) {
      navigate("/profile");
    }
  }, [userToken]);

  const {
    handleSubmit,
    register,
    formState: { errors, isSubmitting },
    watch,
  } = useForm();

  const onSubmit = async (values) => {
    let data = {
      firstName: values.firstName,
      lastName: values.lastName,
      email: values.email,
      password: values.password,
      avatar: values.avatar,
      photos: values.images,
    };

    const resultRegistration = await RegistrationModel.registration(data);
    if (resultRegistration.data.success) {
      setErrorRegister("");
      navigate("/success");
    }
    setErrorRegister(resultRegistration.error);
  };

  const firstNameValidator = register("firstName", {
    required: "Firstname is required",
    minLength: { value: 2, message: "Minimum length should be 2" },
    maxLength: { value: 25, message: "Maximum length should be 25" },
  });

  const lastNameValidator = register("lastName", {
    required: "Lastname is required",
    minLength: { value: 2, message: "Minimum length should be 2" },
    maxLength: { value: 25, message: "Maximum length should be 25" },
  });

  const emailValidator = register("email", {
    required: "Email is required",
    pattern: {
      value: /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i,
      message: "Invalid email format",
    },
  });

  const confirmPasswordValidator = register("confirmPassword", {
    required: "Password confirmation is required",
    validate: (val) => {
      if (watch("password") !== val) {
        return "Password & Password confirmation do no match";
      }
    },
  });

  const passwordValidator = register("password", {
    required: "Password is required",
    minLength: { value: 6, message: "Minimum length should be 6" },
    maxLength: { value: 50, message: "Maximum length should be 50" },
    pattern: {
      value: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,50}$/i,
      message: "The password should at least have 1 number",
    },
  });

  const imagesValidator = register("images", {
    required: "Photos are required",
    validate: (files) => {
      if (files.length < 4) return "You should select at least 4 photos";
    },
  });
  const avatarValidator = register("avatar");

  return (
    <>
      <FormTitle text={"Registration"} />
      <form onSubmit={handleSubmit(onSubmit)}>
        <FormField
          error={errors.firstName}
          id={"firstName"}
          placeholder={"Enter your firstName"}
          label={"FirstName"}
          validator={firstNameValidator}
        />
        <FormField
          error={errors.lastName}
          id={"lastName"}
          placeholder={"Enter your lastName"}
          label={"LastName"}
          validator={lastNameValidator}
        />
        <FormField
          error={errors.email}
          id={"email"}
          placeholder={"Enter your email"}
          label={"Email"}
          validator={emailValidator}
        />
        <FormField
          error={errors.password}
          id={"password"}
          placeholder={"Enter your password"}
          label={"Password"}
          validator={passwordValidator}
          type={"password"}
        />
        <FormField
          error={errors.confirmPassword}
          id={"confirmPassword"}
          placeholder={"Confirm your password"}
          label={"Password confirmation"}
          validator={confirmPasswordValidator}
          type={"password"}
        />
        <FormField
          id={"avatar"}
          placeholder={"Choose your avatar"}
          label={"Avatar"}
          validator={avatarValidator}
          type={"file"}
          multiple={false}
        />
        <FormField
          error={errors.images}
          id={"images"}
          placeholder={"Choose photos"}
          label={"Photos"}
          validator={imagesValidator}
          type={"file"}
          multiple={true}
        />
        <FormButton isLoading={isSubmitting} label={"Regsiter"} />
        <ErrorRequest message={errorRegister} />
      </form>
    </>
  );
};

export default Registration;
