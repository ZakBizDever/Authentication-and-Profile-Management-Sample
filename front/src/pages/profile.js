import Profile from "../controllers/profile";
import Layout from "../views/common/pageLayout";

const ProfilePage = () => {
  return (
    <Layout
      showFooter={true}
      text={"Logout"}
      backURL={"/login"}
      iconBackUrl={"⎋"}
    >
      <Profile />
    </Layout>
  );
};

export default ProfilePage;
