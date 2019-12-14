import React, { useState, useEffect } from 'react';
import { render } from 'react-dom';
import axios from 'axios';
import DatePicker, { registerLocale, setDefaultLocale } from 'react-datepicker';
import {
  getDay,
  addDays,
  isSameDay,
  subMinutes,
  isPast,
} from 'date-fns';
import { setHours, setMinutes } from 'date-fns/esm';
import en from 'date-fns/locale/en-GB';

import { showAlert } from './Utils/NotificationUtils';

registerLocale('en', en);
setDefaultLocale(en);

const Datepicker = () => {
  const [startDate, setStartDate] = useState(new Date());
  const [data, setData] = useState([]);
  const [isFetched, setFetched] = useState(false);
  const [offDays, setOffDays] = useState([]);
  const [excludedDates, setExcludedDates] = useState([]);

  const handleDateChangeRaw = (e) => e.preventDefault();

  const setAvailableDay = (workDays) => {
    if (!workDays) return;
    const currentDay = getDay(startDate) === 0 ? 6 : getDay(startDate) - 1;
    if (!workDays.includes(currentDay)) {
      setStartDate(addDays(startDate, 1));
    }
  };

  const getOffDays = (days) => {
    const DaysArr = Object.keys(days).filter((key) => days[key].isWorkday === false);
    return DaysArr.map((day) => {
      // 6 equals to sunday at api, so if it is 6 this changes to zero instead.
      if (day === '6') {
        return 0;
      }
      return Number(day) + 1;
    });
  };

  const isWorkDay = (date) => !offDays.includes(getDay(date));

  const getDayNumber = () => {
    const currentDay = getDay(startDate);
    if (currentDay === 0) {
      return 6;
    }
    return currentDay - 1;
  };

  const getTime = (timeType) => {
    const selectedDay = getDayNumber();
    const time = data.days[selectedDay][timeType];
    if (time === null) return 0;
    const [hours, minutes] = time.split(':');
    return {
      hours,
      minutes,
    };
  };

  const getStartTime = () => {
    const { hours, minutes } = getTime('startTime');
    const startTime = (setHours(setMinutes(startDate, minutes), hours));
    return isPast(startTime) ? new Date() : startTime;
  };

  const getEndTime = () => {
    const { hours, minutes } = getTime('endTime');
    const endTime = setHours(setMinutes(startDate, minutes), hours);
    if (isPast(endTime)) {
      setExcludedDates([...excludedDates, startDate]);
      setStartDate(addDays(startDate, 1));
    }
    return isPast(endTime) ? new Date() : subMinutes(endTime, data.visitDuration);
  };

  const getTakenDates = () => {
    const takenDates = [];
    data.takenDates.forEach((takenDate) => {
      const [date, time] = takenDate.split(' ');
      if (isSameDay(startDate, new Date(date))) {
        const [hours, minutes] = time.split(':');
        takenDates.push(
          setHours(setMinutes(new Date(date), minutes), hours),
        );
      }
    });
    return takenDates;
  };

  const fetchData = () => {
    const contractorName = window.location.href.split('/').pop();
    axios({
      method: 'get',
      baseURL: `${window.location.protocol}//${window.location.host}`,
      url: `/api/profile/${contractorName}`,
    }).then((response) => {
      setData(response.data);
      setOffDays(getOffDays(response.data.days));
      setAvailableDay(response.data.workingDays);
      setFetched(true);
    })
      .catch((error) => {
        showAlert('Can\'t get available dates. Try again or contact us!', 'error', 4000); // TODO translation
      });
  };

  useEffect(() => {
    fetchData();
    setAvailableDay(data.workingDays);
  },
  [startDate]);

  return (
    <DatePicker
      locale="en"
      selected={startDate}
      onChange={(date) => setStartDate(date)}
      onChangeRaw={handleDateChangeRaw}
      excludeOutOfBoundsTimes
      showTimeSelect
      excludeTimes={isFetched && getTakenDates()}
      excludeDates={excludedDates}
      filterDate={isWorkDay}
      minDate={new Date()}
      maxDate={addDays(new Date(), 90)}
      minTime={isFetched && getStartTime()}
      maxTime={isFetched && getEndTime()}
      timeFormat="HH:mm"
      timeIntervals={data.visitDuration}
      timeCaption="time"
      dateFormat="yyyy-MM-dd HH:mm"
      name="visitDate"
      disabled={!isFetched}
      placeholderText={isFetched ? 'Select Date' : 'Loading...'}
    />
  );
};

render(
  <Datepicker />,
  document.querySelector('#datepicker'),
);
