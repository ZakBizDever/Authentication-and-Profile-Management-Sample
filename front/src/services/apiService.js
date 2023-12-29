import axios from "axios";
const API_URL = process.env.REACT_APP_BASE_API_URL;

const loginRequest = async (data) => {
  try {
    const body = data;
    const headers = {
      "Content-Type": "application/form-data",
    };
    const response = await axios.post(`${API_URL}/login`, body, {
      headers,
    });
    return response;
  } catch (error) {
    return { status: "error", message: error.message };
  }
};

const registerRequest = async (data) => {
  try {
    const body = data;
    const headers = {
      "Content-Type": "multipart/form-data",
    };
    const response = await axios.post(`${API_URL}/register`, body, {
      headers,
    });
    return response;
  } catch (error) {
    return { status: "error", message: error.message };
  }
};

const profileRequest = async (data) => {
  try {
    const { token } = data;
    const headers = {
      Authorization: `Bearer ${token}`,
      "Content-Type": "application/json",
    };
    const response = await axios.get(`${API_URL}/me`, {
      headers,
    });
    return response;
  } catch (error) {
    return { status: "error", message: error.message };
  }
};

const ApiService = {
  login: loginRequest,
  registration: registerRequest,
  profile: profileRequest,
};

export default ApiService;
