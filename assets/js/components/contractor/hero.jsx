import React from 'react';

const Hero = () => {
  return (
    <section className="hero">
      <div className="container">
        <div className="hero__info">
          <h1 className="hero__title">Massage Paradise</h1>
          <span>Savanorių pr. 32, Kaunas, 08106</span>
        </div>
        <button
          type="button"
          data-micromodal-trigger="register-modal"
        >
          Open
        </button>
      </div>
    </section>
  );
};

export default Hero;
