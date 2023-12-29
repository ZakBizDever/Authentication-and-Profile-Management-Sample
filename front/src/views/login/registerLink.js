import React from "react";
import { Link as ReactRouterLink } from "react-router-dom";
import { Text, Link as ChackraUiLink } from "@chakra-ui/react";

const RegisterLink = () => {
  return (
    <Text align={"center"} mt={5}>
      Don't have an account yet ?
      <ChackraUiLink
        as={ReactRouterLink}
        to={"/register"}
        color="orange"
        ml={3}
      >
        Register
      </ChackraUiLink>
    </Text>
  );
};

export default RegisterLink;
