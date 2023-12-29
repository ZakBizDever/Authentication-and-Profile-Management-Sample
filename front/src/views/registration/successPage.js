import { Box, Heading, Link as ChackraUiLink } from "@chakra-ui/react";
import { Link as ReactRouterLink } from "react-router-dom";
import Layout from "../common/pageLayout";

const SuccessRegistration = () => {
  return (
    <Layout showFooter={false}>
      <Box px={5} textAlign={"center"}>
        <Heading size={"lg"}>{"User successfully registrated!"}</Heading>
        <br />
        <ChackraUiLink as={ReactRouterLink} to={"/login"} ml={3}>
          Log in!
        </ChackraUiLink>
      </Box>
    </Layout>
  );
};

export default SuccessRegistration;
