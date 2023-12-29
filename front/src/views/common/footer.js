import { Center, Text, Link as ChackraUiLink } from "@chakra-ui/react";
import { Link as ReactRouterLink } from "react-router-dom";

const Footer = ({ text, backURL, show, iconBackUrl }) => {
  if (!show) return null;
  const to = text === "Logout" ? `${backURL}?logout=true` : backURL;

  return (
    <Center>
      <Text>
        <ChackraUiLink as={ReactRouterLink} to={to} ml={3}>
          {`${iconBackUrl}    ${text}`}
        </ChackraUiLink>
      </Text>
    </Center>
  );
};

export default Footer;
