import React from 'react';
import Stars from './Stars';
import BookBtn from './BookBtn';

const Hero = () => {
  return (
    <section className="hero">
      <div className="container">
        <div className="hero__left col-12 col-md-8">
          <h1 className="hero__title">Massage Paradise</h1>
          <address className="hero__address">
            <i className="icon-location" />
            Savanorių pr. 32, Kaunas, 08106
          </address>
        </div>
        <div className="hero__right col-12 col-md-4">
          <Stars />
          <BookBtn />
        </div>
        {/* <button
          type="button"
          data-micromodal-trigger="register-modal"
        >
          Open
        </button> */}
      </div>

    </section>
  );
};

export default Hero;
