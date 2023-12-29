import Login from "../controllers/login";
import Layout from "../views/common/pageLayout";

const LoginPage = () => {
  return (
    <Layout showFooter={false}>
      <Login />
    </Layout>
  );
};

export default LoginPage;
