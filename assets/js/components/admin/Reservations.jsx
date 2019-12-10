import React from 'react';
import PropTypes from 'prop-types';
import { parseISO } from 'date-fns/esm';
import ReservationRow from './ReservationRow';
import Translator from 'bazinga-translator';



const Reservations = (props) => {
  const { reservations, userKey, fetchData } = props;

  const sortReservationsASC = () => {
    reservations.sort((a, b) => {
      return parseISO(a.visitDate) > parseISO(b.visitDate) ? 1 : -1;
    });
  };

  sortReservationsASC();

  return (
    <div className="panel__content admin-container">
      <h2>Reservations</h2>
      <ul className="reservations">
        <li className="reservations__labels">
          <div className="row">
            {console.log(Translator)}
            <span className="reservations__label col-lg-1">{Translator.trans('crm.date', {}, 'messages', 'lt')}</span>
            <span className="reservations__label col-lg-3">{Translator.trans('crm.date')}</span>
            <span className="reservations__label col-lg-3">{Translator.trans('crm.date')}</span>
            <span className="reservations__label col-lg-2">{Translator.trans('crm.date')}</span>
            <span className="reservations__label col-lg-2">{Translator.trans('crm.date')}</span>
            <span className="reservations__label col-lg-1">{Translator.trans('crm.date')}</span>
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
