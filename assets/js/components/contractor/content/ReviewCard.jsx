import React from 'react';
import PropTypes from 'prop-types';

import Rater from 'react-rater';

const ReviewCard = (props) => {
  const { name, message, stars } = props;
  return (
    <div className="reviews__card">
      <div className="card__info">
        <h4 className="card__name">{name}</h4>
        <Rater total={5} rating={stars} interactive={false} />
      </div>
      <p>{message}</p>
    </div>
  );
};

ReviewCard.propTypes = {
  name: PropTypes.string.isRequired,
  message: PropTypes.string.isRequired,
  stars: PropTypes.number.isRequired,
};

export default ReviewCard;
