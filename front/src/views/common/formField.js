import { useState } from "react";
import {
  FormControl,
  FormLabel,
  FormErrorMessage,
  Input,
  InputGroup,
  InputRightElement,
  Button,
} from "@chakra-ui/react";

const FormField = ({
  error,
  validator,
  id,
  label,
  placeholder,
  type,
  multiple,
}) => {
  const [show, setShow] = useState(type === "password" ? false : true);
  const handleClick = () => setShow(!show);
  return (
    <FormControl isInvalid={error} mb={5}>
      <FormLabel htmlFor={id}>{label}</FormLabel>
      <InputGroup size="md">
        <Input
          id={id}
          type={
            type === "password" && !show
              ? "password"
              : type === "file"
              ? "file"
              : "text"
          }
          multiple={type === "file" ? multiple : ""}
          placeholder={placeholder}
          accept={type === "file" ? "image/*" : undefined}
          {...validator}
        />
        {type === "password" && (
          <InputRightElement width="4.5rem">
            <Button h="1.75rem" size="sm" onClick={handleClick}>
              {show ? "Hide" : "Show"}
            </Button>
          </InputRightElement>
        )}
      </InputGroup>
      <FormErrorMessage>{error && error.message}</FormErrorMessage>
    </FormControl>
  );
};

export default FormField;
