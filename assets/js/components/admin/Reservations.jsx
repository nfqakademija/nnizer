import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { parseISO, isPast } from 'date-fns/esm';


import BookBtn from '../contractor/BookBtn';
import ReservationRow from './ReservationRow';

const Reservations = (props) => {
  const { reservations, userKey, fetchData } = props;
  const [filters, setFilters] = useState({});

  const sortReservations = () => {
    reservations.sort((a, b) => {
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
  };

  sortReservations();

  return (
    <div className="panel__content admin-container">
      <h2>Reservations</h2>
      <BookBtn />
      <ul className="reservations__filters">
        <button type="button">Today</button>
      </ul>
      <ul className="reservations">
        <li className="reservations__labels">
          <div className="row">
            <span className="reservations__label col-lg-1">
              date
              <i className="icon-sort" />
            </span>
            <span className="reservations__label col-lg-3">name</span>
            <span className="reservations__label col-lg-3">email address</span>
            <span className="reservations__label col-lg-2">phone number</span>
            <span className="reservations__label col-lg-2">status</span>
            <span className="reservations__label col-lg-1">edit</span>
          </div>
        </li>
        {reservations.map((reservation) => (
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
    </div>
  );
};

Reservations.propTypes = {
  reservations: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
  userKey: PropTypes.string.isRequired,
  fetchData: PropTypes.func.isRequired,
};

export default Reservations;
