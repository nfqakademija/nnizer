import React from 'react';
import Rater from 'react-rater';

// import('../../../../node_modules/react-rater/lib/react-rater.scss'); // <----- VEIKIA, bet nepraktiska

const Stars = () => {

  return (
    <div className="rating">
      <span className="rating__score">4.9</span>
      <div className="rating__box">
        <Rater total={5} rating={3.5} interactive={false} />
        <span className="rating__count">21 reviews</span>
      </div>
    </div>
  );
};

export default Stars;
