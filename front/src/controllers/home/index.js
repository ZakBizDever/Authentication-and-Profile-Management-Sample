import React from "react";
import { useNavigate } from "react-router-dom";
import { useEffect } from "react";
import { Center, CircularProgress } from "@chakra-ui/react";

const Home = () => {
  const navigate = useNavigate();
  useEffect(() => {
    setTimeout(() => navigate("/login"), 300);
  });

  return (
    <Center>
      <CircularProgress isIndeterminate color="orange" />
    </Center>
  );
};

export default Home;
