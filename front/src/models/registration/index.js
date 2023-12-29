import ApiService from "../../services/apiService";

const registration = async (data) => {
  const { firstName, lastName, email, password, avatar, photos } = data;
  const formData = new FormData();
  formData.append("firstName", firstName);
  formData.append("lastName", lastName);
  formData.append("email", email);
  formData.append("password", password);
  if (avatar.length) formData.append("avatar", avatar[0]);
  for (let i = 0; i < photos.length; i++) {
    formData.append("photos[]", photos[i]);
  }
  const result = await ApiService.registration(formData);

  return result;
};

const RegistrationModel = { registration };

export default RegistrationModel;
