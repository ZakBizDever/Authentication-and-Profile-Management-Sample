import { useState, useEffect } from "react";
import { Box, Link as ChackraUiLink } from "@chakra-ui/react";
import CarouselPhotos from "../../views/profile/carouselPhotos";
import CardInfos from "../../views/profile/cardInfos";
import ProfileModel from "../../models/profile";
import { getFromLocalStorage } from "../../services/localeStorageUtils";
import { Link as ReactRouterLink } from "react-router-dom";

const Profile = () => {
  const token = getFromLocalStorage("userToken");
  const [profileData, setProfileData] = useState({});

  useEffect(() => {
    async function fetchData() {
      try {
        const data = await ProfileModel.profile({ token });
        setProfileData(data);
      } catch (error) {
        console.log("Error fetching profile data");
        setProfileData(false);
      }
    }
    fetchData();
  }, [token]);

  if (!profileData)
    return (
      <ChackraUiLink as={ReactRouterLink} to={"/login"} ml={3}>
        {`An error occured! Retry login`}
      </ChackraUiLink>
    );
  if (!profileData?.fullName) return "Loading ...";
  return (
    <Box p={5}>
      <CardInfos
        fullName={profileData.fullName}
        email={profileData.email}
        avatar={profileData.avatar}
        storage={profileData.storage}
      />
      <CarouselPhotos
        photos={profileData.photos}
        storage={profileData.storage}
      />
    </Box>
  );
};

export default Profile;
