import React from 'react';
import PropTypes from 'prop-types';
import { format, parseISO } from 'date-fns';

import Rater from 'react-rater';

const ReviewCard = (props) => {
  const { name, message, stars, date } = props;
  return (
    <div className="admin-reviews__card">
      <div className="card__info">
        <Rater total={5} rating={stars} interactive={false} />
        <h4 className="card__name">{name}</h4>
      </div>
      <p className="card__text">{message}</p>
      <p className="card__date">
        {
        `📆 ${format(parseISO(date), 'yyyy-MM-dd, HH:mm')}`
        }
      </p>
    </div>
  );
};

ReviewCard.propTypes = {
  name: PropTypes.string.isRequired,
  message: PropTypes.string.isRequired,
  stars: PropTypes.number.isRequired,
  date: PropTypes.string.isRequired,
};

export default ReviewCard;
