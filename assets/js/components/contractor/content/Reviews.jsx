import React from 'react';
import Stars from '../Stars';
import ReviewCard from './ReviewCard';

const Reviews = () => {
  return (
    <div className="reviews">
      <h2 className="contractor__heading">Reviews</h2>
      <Stars />
      <div className="reviews__filter col-lg-4">
        <p>Filter reviews by rating</p>
      </div>
      <div className="row">
        <ul className="reviews__list col-lg-8">
          <li className="reviews__item">
            <ReviewCard />
          </li>
          <li className="reviews__item">
            <ReviewCard />
          </li>
          <li className="reviews__item">
            <ReviewCard />
          </li>
        </ul>
      </div>
    </div>
  );
};

export default Reviews;
