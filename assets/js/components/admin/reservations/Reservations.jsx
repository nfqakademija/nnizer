import React, { useState } from 'react';
import PropTypes from 'prop-types';
import {
  parseISO,
  isPast,
  isToday,
  isThisWeek,
  format,
} from 'date-fns/esm';

import ReservationRow from './ReservationRow';
import { getTranslation } from '../../../Utils/TranslationService';
import axios from 'axios';

const Reservations = (props) => {
  const { reservations, userName, userKey, fetchData, searchTerm } = props;
  const baseURL = `${window.location.protocol}//${window.location.host}`;

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

  const isAnyFilterSelected = () => (
    isCancelledFilter || isPendingFilter || isConfirmedFilter || isExpiredFilter
  );

  const resetFilters = () => {
    setTodayFilter(false);
    setWeekFilter(false);
    setCancelledFilter(false);
    setPendingFilter(false);
    setConfirmedFilter(false);
    setExpiredFilter(false);
  };

  const removeDublicateReversations = (filteredReservations) => (
    filteredReservations.filter((item, pos) => filteredReservations.indexOf(item) === pos)
  );

  const isIncluded = (text) => (
    text.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const addNewReservation = () => {
    // TODO: finish add new post
    axios({
      method: 'POST',
      url: `${baseURL}/api/contractor/${userKey}/new-client`,
      data: [
        'labas',
        'labas',
        'labas@gmail.com',
        '2019-12-24T17:12:05+00:00',
      ],
    })
      .then((response) => {
        console.log(response);
      })
      .catch((error) => {
      });
};

  const getFilteredReservations = () => {
    let sortedReservations = getSortedReservations(reservations);
    let filteredReservations = [];

    if (searchTerm !== '') {
      sortedReservations = sortedReservations.filter((res) => (
        isIncluded(`${res.firstname} ${res.lastname}`)
        || isIncluded(res.email)
        || isIncluded(res.visitDate)
      ));
    }

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
      <p className="panel__time">
        📆
        { `${getTranslation('crm.time.today_is')} ${format(new Date(), 'yyyy-MM-dd!')} `}
      </p>
      <h2>{getTranslation('crm.reservations')}</h2>
      {/* TODO: do add new btn */}
      {/* <button
        type="button"
        className="btn"
        onClick={addNewReservation}
      >
        add new
      </button> */}
      {reservations.length === 0 ? (
        <>
          <p className="reservations__message">
            { getTranslation('crm.no_reservations_text1') }
            <a
              href={`${baseURL}/service/${userName}`}
              target="_blank"
              rel="noopener noreferrer"
              className="link -underline -contractor"
            >
              { getTranslation('crm.no_reservations_link') }
            </a>
            { getTranslation('crm.no_reservations_text2') }
          </p>
        </>
      ) : (
        <>
          <div className="filters">
            {getFilterBtn(getTranslation('crm.time.today'), isTodayFilter, setTodayFilter)}
            {getFilterBtn(getTranslation('crm.time.this_week'), isWeekFilter, setWeekFilter)}
            {getFilterBtn(getTranslation('crm.pending'), isPendingFilter, setPendingFilter)}
            {getFilterBtn(
              getTranslation('crm.unconfirmed'),
              isConfirmedFilter,
              setConfirmedFilter,
            )}
            {getFilterBtn(getTranslation('crm.cancelled'), isCancelledFilter, setCancelledFilter)}
            {getFilterBtn(getTranslation('crm.time.expired'), isExpiredFilter, setExpiredFilter)}
          </div>
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
            {getFilteredReservations().length === 0 ? (
              <>
                <p className="reservations__text">
                  {getTranslation('crm.no_reservations_matching')}
                  <button
                    type="button"
                    className="panel-btn -cancel"
                    onClick={resetFilters}
                  >
                    {getTranslation('crm.reset_filters')}
                  </button>
                </p>
              </>
            ) : (
              getFilteredReservations().map((reservation) => (
                <ReservationRow
                  key={reservation.id}
                  userKey={userKey}
                  id={reservation.id}
                  date={reservation.visitDate}
                  name={`${reservation.firstname} ${reservation.lastname}`}
                  phoneNumber={reservation.phoneNumber}
                  email={reservation.email}
                  isVerified={reservation.isVerified}
                  isCancelled={reservation.isCancelled}
                  fetchData={fetchData}
                />
              ))
            )}
          </ul>
        </>
      )}
    </div>
  );
};

Reservations.propTypes = {
  reservations: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
  userName: PropTypes.string.isRequired,
  userKey: PropTypes.string.isRequired,
  fetchData: PropTypes.func.isRequired,
  searchTerm: PropTypes.string.isRequired,
};

export default Reservations;
