import React from 'react';
import PropTypes from 'prop-types';
import ReservationRow from './ReservationRow';


const Reservations = (props) => {
  const { reservations, userKey, fetchData } = props;

  return (
    <div className="panel__content admin-container">
      <h2>Reservations</h2>
      <ul className="reservations">
        <li className="reservations__labels">
          <div className="row">
            <span className="reservations__label col-lg-1">date</span>
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
