import React from 'react';
import PropTypes from 'prop-types';

const Info = (props) => {
  const {
    address,
    facebook,
    email,
    duration,
    phoneNumber
  } = props;

  return (
    <div className="info">
      <h2 className="contractor__heading">Info</h2>
      <ul className="info__list">
        <li className="info__row">
          <i className="info__icon icon-location" />
          <div className="info__box">
            <h3 className="info__title">Location</h3>
            <a href={`https://maps.google.com/?q=${address}`} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
              {address}
            </a>
          </div>
        </li>
        <li className="info__row">
          <i className="info__icon icon-envelope" />
          <div className="info__box">
            <h3 className="info__title">Email Address</h3>
            <a href={`mailto:${email}`} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
              {email}
            </a>
          </div>
        </li>
        <li className="info__row">
          <i className="info__icon icon-facebook" />
          <div className="info__box">
            <h3 className="info__title">Facebook</h3>
            <a href={`https://facebook.com/${facebook}`} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
              {facebook}
            </a>
          </div>
        </li>
        <li className="info__row">
          <i className="info__icon icon-phone" />
          <div className="info__box">
            <h3 className="info__title">Phone Number</h3>
            <a href={`telto:${phoneNumber}`} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
              {phoneNumber}
            </a>
          </div>
        </li>
      </ul>
    </div>
  );
};

Info.propTypes = {
  address: PropTypes.string,
  facebook: PropTypes.string,
  email: PropTypes.string,
  duration: PropTypes.number,
  phoneNumber: PropTypes.string,
};

Info.defaultProps = {
  address: '',
  facebook: '',
  email: '',
  duration: 0,
  phoneNumber: '',
};

export default Info;
