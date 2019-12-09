import React from 'react';
import PropTypes from 'prop-types';

import BookBtn from './BookBtn';
import Stars from './Stars';

const Hero = (props) => {
  const {
    coverPath,
    title,
    address,
    reviews,
  } = props;

  const url = `${window.location.protocol}//${window.location.host}`;

  const heroImage = {
    background: `
      linear-gradient(180deg, rgba(255, 255, 255, 0) 40%, rgba(255, 255, 255, 1) 100%),
      url('${url}/uploads/cover/${coverPath}')
    `,
    backgroundPosition: 'center center',
    backgroundRepeat: 'no-repeat',
    backgroundSize: 'cover',
  };

  return (
    <section
      className="hero"
      style={heroImage}
    >
      <div className="container">
        <div className="hero__left col-12 col-md-8">
          <h1 className="hero__title">{title}</h1>
          <address className="hero__address">
            <i className="icon-location" />
            {address}
          </address>
        </div>
        <div className="hero__right col-12 col-md-4">
          <Stars reviews={reviews} />
          <BookBtn />
        </div>
      </div>
    </section>
  );
};

Hero.propTypes = {
  coverPath: PropTypes.string,
  title: PropTypes.string,
  address: PropTypes.string,
  reviews: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
};

Hero.defaultProps = {
  coverPath: '',
  title: '',
  address: '',
};

export default Hero;
