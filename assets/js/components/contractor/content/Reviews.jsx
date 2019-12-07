import React from 'react';
import Stars from '../Stars';
import ReviewCard from './ReviewCard';

const Reviews = () => {
  return (
    <div className="reviews">
      <h2 className="contractor__heading">Reviews</h2>
      <Stars />
      <div className="reviews__filters">
        <p className="reviews__text">Filter reviews by rating:</p>
        <ul className="reviews__filter">
          <li className="filter__option">all</li>
          <li className="filter__option">
            5
            <i className="filter__star">★</i>
          </li>
          <li className="filter__option">
            4
            <i className="filter__star">★</i>
          </li>
          <li className="filter__option">
            3
            <i className="filter__star">★</i>
          </li>
          <li className="filter__option">
            2
            <i className="filter__star">★</i>
          </li>
          <li className="filter__option">
            1
            <i className="filter__star">★</i>
          </li>
        </ul>
      </div>
      <ul className="reviews__list">
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
      <button className="contractor-btn">Load More</button>
    </div>
  );
};

export default Reviews;
