import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import { format, differenceInHours, isSameDay, isPast } from 'date-fns';
import { parseISO, differenceInDays } from 'date-fns/esm';

const ReservationRow = (props) => {
  const [editOpen, editToggle] = useState(false);
  const [isDone, setDone] = useState(false);
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
  let statusClass = '';
  let statusText = '';

  const setExpired = () => {
    if (isPast(parseISO(date))) {
      setDone(true);
    }
  };

  const checkStatus = () => {
    if (isCancelled) {
      statusText = 'Cancelled';
      statusClass = 'cancelled';
    } else if (isVerified && !isDone) {
      statusText = 'Pending';
      statusClass = 'pending';
    } else if (isDone) {
      statusText = 'Done';
      statusClass = 'done';
    } else {
      statusText = 'Not confirmed';
      statusClass = 'not-confirmed';
    }
  };

  const cancelReservation = () => {
    // TODO add some kind of loading animation while it fetching
    axios.patch(`/api/contractor/${userKey}/cancel/${id}`)
      .then((response) => {
        fetchData();
      })
      .catch((error) => {
        console.log(error); // To Do error handling
      });
  };

  const approveReservation = () => {
    axios.patch(`/api/contractor/${userKey}/verify/${id}`)
      .then((response) => {
        fetchData();
      })
      .catch((error) => {
        console.log(error);
      });
  };

  const getLeftTime = (reservationDate) => {
    let timeLeft = '';
    const currentDate = new Date();

    if (isDone || isCancelled) {
      timeLeft = 'Expired';
    } else if (isSameDay(reservationDate, currentDate)) {
      const diffInHours = differenceInHours(reservationDate, currentDate);
      timeLeft = diffInHours + (diffInHours === 1 ? ' hour' : ' hours');
    } else {
      const diffInDays = differenceInDays(reservationDate, currentDate);
      timeLeft = diffInDays + (diffInDays === 1 ? ' day' : ' days');
    }
    return timeLeft;
  };

  const formatEmail = () => {
    const [username, provider] = email.split(/(?=@)/g);
    return (
      <>
        {username}
        <br className="show-lg" />
        {provider}
      </>
    );
  };

  const formatDate = () => {
    const day = parseISO(date);
    if (isSameDay(new Date(), day)) {
      return `Today, ${format(day, 'HH:mm')}`;
    }
    return format(day, 'yyyy-MM-dd, HH:mm');
  };

  checkStatus();

  useEffect(() => {
    setExpired();
  },
  [editOpen]);

  return (
    <li className={`reservations__row ${editOpen ? '-editing' : ''}`}>
      <button
        type="button"
        className="reservations__btn -mobile js-edit"
        onClick={() => editToggle(!editOpen)}
      >
        <i className="icon-edit btn__icon" />
      </button>
      <div className="row">
        <div className="reservations__item col-lg-1">
          {formatDate()}
        </div>
        <div className="reservations__item col-lg-3">
          <i className="icon-human item__icon hide-lg" />
          { name }
        </div>
        <div className="reservations__item col-lg-3">
          <i className="icon-envelope item__icon hide-lg" />
          {formatEmail()}
        </div>
        <div className="reservations__item col-lg-2">
          <i className="icon-phone item__icon hide-lg" />
          +370 627 93122
        </div>
        <div className="reservations__item col-lg-2">
          <div className={`status -full + -${statusClass}`}>
            { statusText }
          </div>
        </div>
        <div className="reservations__item col-lg-1">
          <button
            type="button"
            className={`reservations__btn js-edit ${editOpen && '-open'}`}
            onClick={() => editToggle(!editOpen)}
          >
            <i className={`btn__icon icon-${editOpen ? 'cross' : 'edit'}`} />
          </button>
        </div>
      </div>
      <div className="row">
        <div className={`reservations__edit js-edit-window ${editOpen ? '-open' : ''}`}>
          <span className="edit__heading">Time left</span>
          <span className="edit__time-left">
            {getLeftTime(parseISO(date))}
          </span>
          <div className="edit__actions">
            {(!isVerified && !isDone)
            && (
            <button
              type="button"
              className="panel-btn -success"
              onClick={approveReservation}
            >
            Approve
            </button>
            )}
            {(!isCancelled && !isDone)
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
