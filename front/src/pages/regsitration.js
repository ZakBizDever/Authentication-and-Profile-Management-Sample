import Registration from "../controllers/registration";
import Layout from "../views/common/pageLayout";

const RegistrationPage = () => {
  return (
    <Layout
      showFooter={true}
      text={"Back to login"}
      backURL={"/login"}
      iconBackUrl={"←"}
    >
      <Registration />
    </Layout>
  );
};

export default RegistrationPage;
