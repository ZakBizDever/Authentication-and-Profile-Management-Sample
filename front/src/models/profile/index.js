import ApiService from "../../services/apiService";

const profile = async (data) => {
  const result = await ApiService.profile(data);
  if (result.status === "error") {
    console.log("ERROR LOGIN Request", result.message);
    return false;
  }

  return result.data[0];
};

const ProfileModel = { profile };

export default ProfileModel;
