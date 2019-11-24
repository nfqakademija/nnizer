import React, { useState } from 'react';

const ReservationsList = () => {
  const [editOpen, editToggle] = useState(false);

  return (
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
      <li className="reservations__row">
        <button
          type="button"
          className="reservations__btn -mobile js-edit"
          onClick={() => editToggle(!editOpen)}
        >
          <i className="icon-edit btn__icon" />
        </button>
        <div className="row">
          <div className="reservations__item col-lg-1">NOV 21 13:30</div>
          <div className="reservations__item col-lg-3">
            <i className="icon-human item__icon hide-lg" />
            Dominykas Murauskas
          </div>
          <div className="reservations__item col-lg-3">
            <i className="icon-email item__icon  hide-lg" />
            dominykasmurauskas
            <br className="show-lg" />
            @gmail.com
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
            <button type="button" className="reservations__btn js-edit">
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
    </ul>
  );
};

export default ReservationsList;
