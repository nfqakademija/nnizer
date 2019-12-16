import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import axios from 'axios';
import { format, differenceInHours, isSameDay, isPast } from 'date-fns';
import { parseISO, differenceInDays } from 'date-fns/esm';
import { getTranslation } from '../../../TranslationService';

import { showAlert, updateAlert } from '../../../Utils/NotificationUtils';

const ReservationRow = (props) => {
  const [editOpen, editToggle] = useState(false);
  const [isDone, setDone] = useState(false);

  const [isCancelDisabled, setCancel] = useState(false);
  const [isApprovalDisabled, setApproval] = useState(false);
  const [isDeletionDisabled, setDeletion] = useState(false);

  const {
    id,
    userKey,
    date,
    name,
    email,
    phoneNumber,
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
      statusText = getTranslation('crm.cancelled');
      statusClass = 'cancelled';
    } else if (isVerified) {
      statusText = getTranslation('crm.pending');
      statusClass = 'pending';
    } else if (isDone) {
      statusText = getTranslation('crm.done');
      statusClass = 'done';
    } else {
      statusText = getTranslation('crm.unconfirmed');
      statusClass = 'not-confirmed';
    }
  };

  const buttonClicked = (msg, setBtn) => {
    showAlert(msg, '', 10000);
    editToggle(!editOpen);
    setBtn(true);
  };

  const deleteReservation = () => {
    // @Route("/api/contractor/{contractorKey}/delete/{reservationId}", methods="DELETE")
    buttonClicked(`${getTranslation('crm.removal.progress')} 🗑`, setDeletion);
    axios
      .delete(`/api/contractor/${userKey}/delete/${id}`)
      .then((response) => {
        fetchData();
        updateAlert(`${getTranslation('crm.removal.success')} ✅`, 'success', 4000);
      })
      .catch((error) => {
        updateAlert(getTranslation('crm.removal.error'), 'error', 4000);
      });
  };

  const cancelReservation = () => {
    buttonClicked(`${getTranslation('crm.cancellation.progress')} 👨🏼‍💻`, setCancel);
    axios
      .patch(`/api/contractor/${userKey}/cancel/${id}`)
      .then((response) => {
        fetchData();
        updateAlert(`${getTranslation('crm.cancellation.success')} ✅`, 'success', 4000);
      })
      .catch((error) => {
        updateAlert(getTranslation('crm.cancellation.error'), 'error', 4000);
      });
  };

  const approveReservation = () => {
    buttonClicked(`${getTranslation('crm.approval.progress')} 👨🏼‍💻`, setApproval);
    axios
      .patch(`/api/contractor/${userKey}/verify/${id}`)
      .then((response) => {
        fetchData();
        updateAlert(`${getTranslation('crm.approval.success')} ✅`, 'success', 4000);
      })
      .catch((error) => {
        updateAlert(getTranslation('crm.approval.error'), 'error', 4000);
      });
  };

  const getLeftTime = (reservationDate) => {
    let timeLeft = '';
    const currentDate = new Date();

    if (isDone || isCancelled) {
      timeLeft = getTranslation('crm.time.expired');
    } else if (isSameDay(reservationDate, currentDate)) {
      const diffInHours = differenceInHours(reservationDate, currentDate);
      timeLeft = diffInHours + ' ' +(diffInHours === 1 ? getTranslation('crm.time.hour') : getTranslation('crm.time.hours'));
    } else {
      const diffInDays = differenceInDays(reservationDate, currentDate);
      timeLeft = diffInDays + ' '+(diffInDays === 1 ? getTranslation('crm.time.day') : getTranslation('crm.time.days'));
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
      return `${getTranslation('crm.time.today')}, ${format(day, 'HH:mm')}`;
    }
    return format(day, 'yyyy-MM-dd, HH:mm');
  };

  checkStatus();

  useEffect(() => {
    setExpired();
  }, [editOpen]);

  return (
    <li className={`reservations__row ${editOpen ? '-editing' : ''}`}>
      <button
        type="button"
        className="reservations__btn -mobile js-edit"
        onClick={() => editToggle(!editOpen)}
      >
        <i className="icon-edit btn__icon" />
        { !isVerified && !isDone && !isCancelled && <div className="circle -pending">1</div> }
      </button>
      <div className="row">
        <div className="reservations__item col-lg-1">{formatDate()}</div>
        <div className="reservations__item col-lg-3">
          <i className="icon-human item__icon hide-lg" />
          {name}
        </div>
        <div className="reservations__item col-lg-3">
          <i className="icon-envelope item__icon hide-lg" />
          {formatEmail()}
        </div>
        <div className="reservations__item col-lg-2">
          <i className="icon-phone item__icon hide-lg" />
          {phoneNumber ? phoneNumber : getTranslation('crm.missing.phone')}
        </div>
        <div className="reservations__item col-lg-2">
          <div className={`status -full + -${statusClass}`}>{statusText}</div>
        </div>
        <div className="reservations__item col-lg-1">
          <button
            type="button"
            className={`reservations__btn js-edit ${editOpen && '-open'}`}
            onClick={() => editToggle(!editOpen)}
          >
            <i className={`btn__icon icon-${editOpen ? 'cross' : 'edit'}`} />
            { !isVerified && !isDone && !isCancelled && <div className="circle -pending">1</div> }
          </button>
        </div>
      </div>
      <div className="row">
        <div
          className={`reservations__edit js-edit-window ${
            editOpen ? '-open' : ''
          }`}
        >
          <span className="edit__heading">{getTranslation('crm.time_left')}</span>
          <span className="edit__time-left">{getLeftTime(parseISO(date))}</span>
          <div className="edit__actions">
            <button
              type="button"
              className="edit__action panel-btn -delete"
              // eslint-disable-next-line no-alert
              onClick={(e) => window.confirm(getTranslation('crm.removal.approval')) && deleteReservation()}
              disabled={isDeletionDisabled}
            >
              {getTranslation('crm.delete')}
            </button>
            
            {!isCancelled && !isDone && (
              <button
                type="button"
                className="edit__action panel-btn -cancel"
                // eslint-disable-next-line no-alert
                onClick={(e) => window.confirm(getTranslation('crm.cancellation.approval')) && cancelReservation()}
                disabled={isCancelDisabled}
              >
                {getTranslation('crm.cancel')}
              </button>
            )}
            {!isVerified && !isDone && !isCancelled && (
              <button
                type="button"
                className="edit__action panel-btn -success"
                onClick={approveReservation}
                disabled={isApprovalDisabled}
              >
                {getTranslation('crm.approve')}
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
  phoneNumber: PropTypes.string.isRequired,
  isVerified: PropTypes.bool.isRequired,
  isCancelled: PropTypes.bool.isRequired,
  fetchData: PropTypes.func.isRequired,
};

export default ReservationRow;
