import ApiService from "../../services/apiService";

const login = async (data) => {
  const { email, password } = data;
  const formData = new FormData();
  formData.append("email", email);
  formData.append("password", password);
  const result = await ApiService.login(formData);
  if (result.status === "error") {
    console.log("ERROR LOGIN Request", result.message);
    return false;
  }
  const { token } = result.data;
  return token;
};

const LoginModel = { login };

export default LoginModel;
