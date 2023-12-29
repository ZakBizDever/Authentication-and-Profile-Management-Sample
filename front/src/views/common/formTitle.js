import { Heading, Center } from "@chakra-ui/react";

const FormTitle = ({ text }) => {
  return (
    <Center>
      <Heading as="h3" size={"md"}>
        {text.toUpperCase()}
      </Heading>
    </Center>
  );
};

export default FormTitle;
