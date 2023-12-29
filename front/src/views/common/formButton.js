import { Button } from "@chakra-ui/react";

const FormButton = ({ isLoading, label }) => {
  return (
    <Button
      mt={4}
      colorScheme="teal"
      isLoading={isLoading}
      type="submit"
      width={"100%"}
    >
      {label}
    </Button>
  );
};

export default FormButton;
