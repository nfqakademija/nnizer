import React, { useState } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';

const ReservationRow = (props) => {
  const [editOpen, editToggle] = useState(false);
  const {
    id,
    userKey,
    date,
    name,
    email,
    isVerified,
    isCancelled,
    fetchData,
  } = props;
  let status = '';
  let statusText = '';

  const checkStatus = () => {
    if (isCancelled) {
      statusText = 'Cancelled';
      status = 'cancelled';
    } else if (isVerified) {
      statusText = 'Pending';
      status = 'pending';
    } else {
      statusText = 'Not confirmed';
      status = 'not-confirmed';
    }
  };

  checkStatus();

  const cancelReservation = () => {
    // TODO change GET to PATCH after back-end changes
    // TODO add some kind of loading animation while it fetching
    axios.get(`/api/contractor/${userKey}/cancel/${id}`)
      .then((response) => {
        fetchData();
      })
      .catch((error) => {
        console.log(error);
      });
  };

  const approveReservation = () => {
    // TODO change GET to PATCH after back-end changes
    axios.get(`/api/contractor/${userKey}/verify/${id}`)
      .then((response) => {
        fetchData();
      })
      .catch((error) => {
        console.log(error);
      });
  };

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
          <i className="icon-email item__icon hide-lg" />
          { email }
          {/* <br className="show-lg" />
          @gmail.com */}
        </div>
        <div className="reservations__item col-lg-2">
          <i className="icon-phone item__icon hide-lg" />
          +370 627 93122
        </div>
        <div className="reservations__item col-lg-2">
          <div className={`status -full + -${status}`}>
            { statusText }
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
            {!isVerified
            && (
            <button
              type="button" 
              className="panel-btn -success"
              onClick={approveReservation}
            >
            Approve
            </button>
            )}
            {!isCancelled
            && (
            <button
              type="button"
              className="panel-btn -cancel"
              onClick={cancelReservation}
            >
            Cancel
            </button>
            )}
          </div>
        </div>
      </div>
    </li>
  );
};

ReservationRow.propTypes = {
  id: PropTypes.number.isRequired,
  userKey: PropTypes.string.isRequired,
  date: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  email: PropTypes.string.isRequired,
  isVerified: PropTypes.bool.isRequired,
  isCancelled: PropTypes.bool.isRequired,
  fetchData: PropTypes.func.isRequired,
};

export default ReservationRow;
