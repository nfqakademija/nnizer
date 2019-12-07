import React from 'react';
import Rater from 'react-rater';

const Reviews = () => {
  return (
    <div className="reviews__card">
      <div className="card__info">
        <h4 className="card__name">Dominykas Murauskas</h4> 
        <Rater total={5} rating={3.5} interactive={false} />
      </div>
      <p>Amazing price, however quality had some issues. Despite that, Highly recommended!</p>
    </div>
  );
};

export default Reviews;
