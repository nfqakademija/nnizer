import React from 'react';
import PropTypes from 'prop-types';

const About = (props) => {
  const { description } = props;

  return (
    <div className="about">
      <h2 className="contractor__heading">About Service</h2>
      <p className="about__text">
        {description}
      </p>
    </div>
  );
};

About.propTypes = {
  description: PropTypes.string,
};

About.defaultProps = {
  description: '',
};

export default About;
