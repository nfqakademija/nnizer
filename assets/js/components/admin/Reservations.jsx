import React from 'react';
import ReservationsList from './ReservationsList';

const Reservations = () => {
  return (
    <div className="panel__content admin-container">
      <h2>Reservations</h2>
      <ReservationsList />
    </div>
  );
};

export default Reservations;
