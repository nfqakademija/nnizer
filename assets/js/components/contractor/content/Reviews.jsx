import React, { useState } from 'react';
import PropTypes from 'prop-types';
import uuidv4 from 'uuid/v4';

import Stars from '../../Stars';
import ReviewCard from './ReviewCard';
import { getTranslation } from '../../../TranslationService';

const Reviews = (props) => {
  const { reviews } = props;
  const [selectedStars, setStars] = useState([]);
  const [reviewsNum, setReviewsNum] = useState(3);
  const stars = [5, 4, 3, 2, 1];

  const setFilter = (star) => {
    const currentStars = selectedStars;

    if (currentStars.includes(star)) {
      setStars(
        currentStars.filter((currentStar) => currentStar !== star),
      );
    } else {
      setStars([
        ...currentStars,
        star,
      ]);
    }
  };

  const getFilters = () => stars.map((star) => (
    <li
      key={uuidv4()}
      className={`filter__option ${selectedStars.includes(star) && '-active'}`}
      onClick={() => setFilter(star)}
    >
      {star}
      <i className="filter__star">★</i>
    </li>
  ));

  const getReviews = () => {
    let filteredReviews = reviews;
    if (selectedStars.length !== 0) {
      filteredReviews = filteredReviews.filter((review) => (
        selectedStars.includes(review.stars)
      ));
    }
    return filteredReviews;
  };

  const filteredReviews = getReviews();

  return (
    <div className="reviews">
      <h2 className="contractor__heading">{getTranslation('contractor.reviews.title')}</h2>
      {reviews.length === 0 ? (
        <p className="reviews__message"> {getTranslation('contractor.reviews.none')}😕</p>
      ) : (
        <>
          <Stars reviews={reviews} />
          <div className="reviews__filters">
            <p className="reviews__text">{getTranslation('contractor.reviews.filter.title')}:</p>
            <ul className="reviews__filter">{getFilters()}</ul>
          </div>
          <ul className="reviews__list">
            {filteredReviews.length === 0 ? (
              <>
                <p>{getTranslation('contractor.reviews.filter.none')}</p>
                <button
                  type="button"
                  className="contractor-btn -reset"
                  onClick={() => setStars([])}
                >
                  {getTranslation('contractor.reviews.filter.reset')}
                </button>
              </>
            ) : (
              filteredReviews.slice(0, reviewsNum).map((review) => (
                <li key={uuidv4()} className="reviews__item">
                  <ReviewCard
                    name={`${review.reservation.firstname} ${review.reservation.lastname}`}
                    message={review.description}
                    stars={review.stars}
                  />
                </li>
              ))
            )}
          </ul>
          {reviewsNum < filteredReviews.length && (
            <button
              type="button"
              className="contractor-btn"
              onClick={() => setReviewsNum(reviewsNum + 3)}
            >
              {getTranslation('contractor.reviews.load')}
            </button>
          )}
        </>
      )}
    </div>
  );
};

Reviews.propTypes = {
  reviews: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
};

export default Reviews;
