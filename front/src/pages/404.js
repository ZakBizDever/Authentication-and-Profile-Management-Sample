import Layout from "../views/common/pageLayout";
import { Box, Heading } from "@chakra-ui/react";

const NotFoundPage = () => {
  return (
    <Layout showFooter={false}>
      <Box px={5}>
        <Heading size={"lg"}>404 Error</Heading>
        <br />
        <Heading size="sm">Page Not Found</Heading>
      </Box>
    </Layout>
  );
};

export default NotFoundPage;
