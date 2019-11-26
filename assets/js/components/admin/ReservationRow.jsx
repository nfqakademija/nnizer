import React, { useState } from 'react';
import PropTypes from 'prop-types';

const ReservationRow = (props) => {
  const [editOpen, editToggle] = useState(false);
  const {
    date,
    name,
    email,
    isVerified,
    isCancelled,
  } = props;

  return (
    <li className="reservations__row">
      <button
        type="button"
        className="reservations__btn -mobile js-edit"
        onClick={() => editToggle(!editOpen)}
      >
        <i className="icon-edit btn__icon" />
      </button>
      <div className="row">
        <div className="reservations__item col-lg-1">{date}</div>
        <div className="reservations__item col-lg-3">
          <i className="icon-human item__icon hide-lg" />
          { name }
        </div>
        <div className="reservations__item col-lg-3">
          <i className="icon-email item__icon  hide-lg" />
          { email }
          {/* <br className="show-lg" />
          @gmail.com */}
        </div>
        <div className="reservations__item col-lg-2">
          <i className="icon-phone item__icon hide-lg" />
          +370 627 93122
        </div>
        <div className="reservations__item col-lg-2">
          <div className="status -full -done">
              Done
          </div>
        </div>
        <div className="reservations__item col-lg-1">
          <button
            type="button"
            className="reservations__btn js-edit"
            onClick={() => editToggle(!editOpen)}
          >
            <i className="icon-edit btn__icon" />
          </button>
        </div>
      </div>
      <div className="row">
        <div className={`reservations__edit js-edit-window ${editOpen ? '-open' : ''}`}>
          <span className="edit__heading">Time left</span>
          <span className="edit__time-left">approx. 5 hours</span>
          <div className="edit__actions">
            <button type="button" className="panel-btn -success">Approve</button>
            <button type="button" className="panel-btn -cancel">Cancel</button>
          </div>
        </div>
      </div>
    </li>
  );
};

ReservationRow.propTypes = {
  date: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  email: PropTypes.string.isRequired,
  isVerified: PropTypes.bool.isRequired,
  isCancelled: PropTypes.bool.isRequired,
};

export default ReservationRow;
