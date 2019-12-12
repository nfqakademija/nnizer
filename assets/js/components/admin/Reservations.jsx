import React, { useState } from 'react';
import PropTypes from 'prop-types';
import {
  parseISO,
  isPast,
  isToday,
  isThisWeek,
} from 'date-fns/esm';


import BookBtn from '../contractor/BookBtn';
import ReservationRow from './ReservationRow';

const Reservations = (props) => {
  const { reservations, userKey, fetchData } = props;

  const [isTodayFilter, setTodayFilter] = useState(false);
  const [isWeekFilter, setWeekFilter] = useState(false);
  const [isCancelledFilter, setCancelledFilter] = useState(false);
  const [isPendingFilter, setPendingFilter] = useState(false);
  const [isConfirmedFilter, setConfirmedFilter] = useState(false);
  const [isExpiredFilter, setExpiredFilter] = useState(false);

  const getSortedReservations = (res) => {
    const sortedRes = res.sort((a, b) => {
      const dateA = parseISO(a.visitDate);
      const dateB = parseISO(b.visitDate);

      if (a.isCancelled && !b.isCancelled) return 1;
      if (!a.isCancelled && b.isCancelled) return -1;

      if (isPast(dateA) && !isPast(dateB)) return 1;
      if (!isPast(dateA) && isPast(dateB)) return -1;

      if (a.isVerified && !b.isVerified) return 1;
      if (!a.isVerified && b.isVerified) return -1;

      return parseISO(a.visitDate) > parseISO(b.visitDate) ? 1 : -1;
    });

    return sortedRes;
  };

  const getFilterBtn = (text, filter, filterFun) => (
    <button
      type="button"
      className={`filters__btn ${filter && '-active'}`}
      onClick={() => filterFun(!filter)}
    >
      <i className="icon-cross" />
      {text}
    </button>
  );

  const isAnyFilterSelected = () => {
    if (isCancelledFilter || isPendingFilter || isConfirmedFilter || isExpiredFilter) {
      return true;
    }
    return false;
  };

  const removeDublicateReversations = (filteredReservations) => (
    filteredReservations.filter((item, pos) => filteredReservations.indexOf(item) === pos)
  );

  const getFilteredReservations = () => {
    let sortedReservations = getSortedReservations(reservations);
    let filteredReservations = [];

    if (isTodayFilter && !isWeekFilter) {
      sortedReservations = sortedReservations.filter((res) => isToday(parseISO(res.visitDate)));
    }

    if (isWeekFilter) {
      sortedReservations = sortedReservations.filter(
        (res) => isThisWeek(parseISO(res.visitDate)),
      );
    }

    if (isCancelledFilter) {
      filteredReservations = [
        ...filteredReservations,
        ...sortedReservations.filter((res) => res.isCancelled),
      ];
    }

    if (isPendingFilter) {
      filteredReservations = [
        ...filteredReservations,
        ...sortedReservations.filter(
          (res) => !res.isCancelled && res.isVerified && !isPast(parseISO(res.visitDate)),
        ),
      ];
    }

    if (isConfirmedFilter) {
      filteredReservations = [...filteredReservations, ...sortedReservations.filter(
        (res) => !res.isVerified && !isPast(parseISO(res.visitDate)) && !res.isCancelled,
      )];
    }

    if (isExpiredFilter) {
      filteredReservations = [
        ...filteredReservations,
        ...sortedReservations.filter(
          (res) => isPast(parseISO(res.visitDate)),
        ),
      ];
    }

    filteredReservations = removeDublicateReversations(filteredReservations);

    return filteredReservations.length === 0 && !isAnyFilterSelected()
      ? getSortedReservations(sortedReservations)
      : getSortedReservations(filteredReservations);
  };

  return (
    <div className="panel__content admin-container">
      <h2>Reservations</h2>
      {reservations.length === 0 ? (
        <>
          <p className="reservations__message">
            You don't have any reservations yet! Share this link and get your
            first{' '}
          </p>
        </>
      ) : (
        <>
          <div className="filters">
            {getFilterBtn('Today', isTodayFilter, setTodayFilter)}
            {getFilterBtn('This week', isWeekFilter, setWeekFilter)}
            {getFilterBtn('Pending', isPendingFilter, setPendingFilter)}
            {getFilterBtn(
              'Not confirmed',
              isConfirmedFilter,
              setConfirmedFilter,
            )}
            {getFilterBtn('Cancelled', isCancelledFilter, setCancelledFilter)}
            {getFilterBtn('Expired', isExpiredFilter, setExpiredFilter)}
          </div>
          <ul className="reservations">
            <li className="reservations__labels">
              <div className="row">
                <span className="reservations__label col-lg-1">
                  date
                  <i className="icon-sort reservations__label-sort" />
                </span>
                <span className="reservations__label col-lg-3">name</span>
                <span className="reservations__label col-lg-3">
                  email address
                </span>
                <span className="reservations__label col-lg-2">
                  phone number
                </span>
                <span className="reservations__label col-lg-2">status</span>
                <span className="reservations__label col-lg-1">edit</span>
              </div>
            </li>
            {getFilteredReservations().map((reservation) => (
              <ReservationRow
                key={reservation.id}
                userKey={userKey}
                id={reservation.id}
                date={reservation.visitDate}
                name={`${reservation.firstname} ${reservation.lastname}`}
                email={reservation.email}
                isVerified={reservation.isVerified}
                isCancelled={reservation.isCancelled}
                fetchData={fetchData}
              />
            ))}
          </ul>
        </>
      )}
    </div>
  );
};

Reservations.propTypes = {
  reservations: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
  userKey: PropTypes.string.isRequired,
  fetchData: PropTypes.func.isRequired,
};

export default Reservations;
