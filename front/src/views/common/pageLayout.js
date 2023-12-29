import { SimpleGrid, Box, Card, CardBody } from "@chakra-ui/react";
import React from "react";
import Header from "./header";
import Footer from "./footer";

const Layout = ({ children, text, backURL, showFooter, iconBackUrl }) => {
  return (
    <Box m={20}>
      <Header />
      <SimpleGrid columns={[1, 1, 1, 3]}>
        <Box></Box>
        <Box my={10}>
          <Card bg={"white"} borderRadius={30} py={5}>
            <CardBody>{children}</CardBody>
          </Card>
        </Box>
        <Box></Box>
      </SimpleGrid>
      <Footer
        text={text}
        backURL={backURL}
        show={showFooter}
        iconBackUrl={iconBackUrl}
      />
    </Box>
  );
};

export default Layout;
