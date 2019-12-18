import React from 'react';
import PropTypes from 'prop-types';
import Rater from 'react-rater';

import getTranslation from '../../../Utils/TranslationService';


const ServiceCard = (props) => {
  const { service, selectedService } = props;
  const baseURL = `${window.location.protocol}//${window.location.host}`;

  return (
    <div className="col-12 col-md-6 col-lg-4">
      <div className="service-card">
        <img
          src={`${baseURL}/uploads/cover/${selectedService.toLowerCase()}.jpg`}
          alt=""
          className="service-card__image"
        />
        <div className="service-card__info">
          <div className="service-card__stars">
            {service.reviews.average}
            <Rater total={5} rating={service.reviews.average} interactive={false} />
            <span className="service-card__reviews">
              {`(${service.reviews.totalReviews} reviews)`}
            </span>
          </div>
          <h3 className="service-card__name">
            { service.title }
          </h3>
          <p className="service-card__location">
            <i className="icon-location" />
            { service.address }
          </p>
          <p className="service-card__provider">
            <i className="icon-human" />
            { `${service.firstname} ${service.lastname}` }
          </p>
          <a
            href={`${baseURL}/service/${service.username}`}
            className="service-card__link btn -full"
          >
            {getTranslation('services.book')}
          </a>
        </div>
      </div>
    </div>
  )
};

ServiceCard.propTypes = {
  service: PropTypes.oneOfType([PropTypes.object]).isRequired,
  selectedService: PropTypes.string.isRequired,
};

export default ServiceCard;
