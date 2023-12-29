import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const serverURL = process.env.REACT_APP_API_SERVER;
const CarouselPhotos = ({ photos, storage }) => {
  const settings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 3000,
  };

  return (
    <Slider {...settings}>
      {photos.map((imageUrl, index) => {
        const src = storage === "local" ? `${serverURL}${imageUrl}` : imageUrl;
        return (
          <div key={index}>
            <img src={src} alt={`Slide ${index + 1}`} />
          </div>
        );
      })}
    </Slider>
  );
};

export default CarouselPhotos;
