import Home from "../controllers/home";
import Layout from "../views/common/pageLayout";

const HomePage = () => {
  return (
    <Layout showFooter={false}>
      <Home />
    </Layout>
  );
};

export default HomePage;
