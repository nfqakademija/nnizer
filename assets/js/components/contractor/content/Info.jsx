import React from 'react';
import PropTypes from 'prop-types';
import { getTranslation } from '../../../TranslationService';

const Info = (props) => {
  const {
    address,
    facebook,
    email,
    phoneNumber,
  } = props;

  const getRow = (icon, title, link, text) => (
    <li className="info__row">
      <i className={`info__icon icon-${icon}`} />
      <div className="info__box">
        <h3 className="info__title">{title}</h3>
        <a href={link} className="link -hover-underline" target="_blank" rel="noopener noreferrer">
          {text}
        </a>
      </div>
    </li>
  );

  return (
    <div className="info">
      <h2 className="contractor__heading">{getTranslation('contractor.useful-info')}</h2>
      <ul className="info__list">
        {address && getRow('location', getTranslation('contractor.location'), `https://maps.google.com/?q=${address}`, address)}
        {email && getRow('envelope', getTranslation('contractor.email'), `mailto:${email}`, email)}
        {facebook && getRow('facebook', getTranslation('contractor.facebook'), `https://facebook.com/${facebook}`, facebook)}
        {phoneNumber && getRow('phone', getTranslation('contractor.phone'), `tel:${phoneNumber}`, phoneNumber)}
      </ul>
    </div>
  );
};

Info.propTypes = {
  address: PropTypes.string,
  facebook: PropTypes.string,
  email: PropTypes.string,
  phoneNumber: PropTypes.string,
};

Info.defaultProps = {
  address: '',
  facebook: '',
  email: '',
  phoneNumber: '',
};

export default Info;
