import React from 'react';
import PropTypes from 'prop-types';
import Rater from 'react-rater';

import getTranslation from '../../../Utils/TranslationService';


const ServiceCard = (props) => {
  const { service } = props;
  const baseURL = `${window.location.protocol}//${window.location.host}`;

  const getReviewsTranslation = () => {
    const reviewsCount = service.reviews.totalReviews;
    if (reviewsCount > 1 && reviewsCount < 10) {
      return getTranslation('services.reviews_plurar2');
    }

    if (reviewsCount >= 10) {
      return getTranslation('services.reviews_plurar');
    }

    return getTranslation('services.review_singular');
  };

  return (
    <div className="col-12 col-md-6 col-lg-4">
      <div className="service-card">
        <a href={`${baseURL}/service/${service.username}`}>
          <img
            src={`${baseURL}/uploads/cover/${service.coverPhoto.filename}`}
            alt=""
            className="service-card__image"
          />
        </a>
        <div className="service-card__info">
          <div className="service-card__stars">
            {service.reviews.average}
            <Rater total={5} rating={service.reviews.average} interactive={false} />
            <span className="service-card__reviews">
              { `(${service.reviews.totalReviews} ${getReviewsTranslation()})` }
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
