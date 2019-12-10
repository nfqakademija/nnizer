import React from 'react';
import PropTypes from 'prop-types';
import { parseISO } from 'date-fns/esm';
import ReservationRow from './ReservationRow';
import { getTranslation } from '../../TranslationService';

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
      <h2>{getTranslation('crm.reservations')}</h2>
      <ul className="reservations">
        <li className="reservations__labels">
          <div className="row">
            <span className="reservations__label col-lg-1">{getTranslation('crm.date')}</span>
            <span className="reservations__label col-lg-3">{getTranslation('crm.name')}</span>
            <span className="reservations__label col-lg-3">{getTranslation('crm.email')}</span>
            <span className="reservations__label col-lg-2">{getTranslation('crm.phone')}</span>
            <span className="reservations__label col-lg-2">{getTranslation('crm.status')}</span>
            <span className="reservations__label col-lg-1">{getTranslation('crm.actions')}</span>
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
