import { Text, Center } from "@chakra-ui/react";

const ErrorRequest = ({ message }) => {
  return (
    <Center mt={3}>
      <Text color={"red"}>{message}</Text>
    </Center>
  );
};

export default ErrorRequest;
