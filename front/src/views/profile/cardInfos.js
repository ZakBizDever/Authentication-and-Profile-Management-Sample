import { Flex, Box, Avatar, Text } from "@chakra-ui/react";

const serverURL = process.env.REACT_APP_API_SERVER;
const CardInfos = ({ fullName, email, avatar, storage }) => {
  if (!fullName) return null;
  const src = storage === "local" ? `${serverURL}${avatar}` : avatar;
  return (
    <Flex mb={10}>
      <Avatar name={fullName} src={src} />
      <Box ml="3">
        <Text fontWeight="bold">{fullName}</Text>
        {email && <Text fontSize="sm">{email}</Text>}
      </Box>
    </Flex>
  );
};

export default CardInfos;
