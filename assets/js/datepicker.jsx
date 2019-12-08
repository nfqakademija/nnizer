import React, { useState, useEffect } from 'react';
import { render } from 'react-dom';
import axios from 'axios';
import DatePicker, { registerLocale, setDefaultLocale } from 'react-datepicker';
import {
  getDay,
  addDays,
  isSameDay,
  subMinutes,
} from 'date-fns';
import { setHours, setMinutes } from 'date-fns/esm';
import en from 'date-fns/locale/en-GB';

registerLocale('en', en);
setDefaultLocale(en);

const Datepicker = () => {
  const [startDate, setStartDate] = useState(new Date());
  const [data, setData] = useState([]);
  const [isFetched, setFetched] = useState(false);
  const [offDays, setOffDays] = useState([]);

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
    if (data.days[selectedDay][timeType] !== null) {
      const [hours, minutes] = data.days[selectedDay][timeType].split(':');
      return setHours(setMinutes(startDate, minutes), hours);
    }
  };

  const getStartTime = () => {
    const selectedDay = getDayNumber();
    const { startTime } = data.days[selectedDay];
    if (startTime !== null) {
      const [hours, minutes] = startTime.split(':');
      return setHours(setMinutes(startDate, minutes), hours);
    }
    return null;
  };

  const getEndTime = () => {
    const selectedDay = getDayNumber();
    const { endTime } = data.days[selectedDay];
    if (endTime !== null) {
      const [hours, minutes] = endTime.split(':');
      return setHours(setMinutes(new Date(), minutes), hours);
    }
    return null;
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
      url: `/api/profile/${contractorName}/working-hours`,
    }).then((response) => {
      console.log(response.data);
      setData(response.data);
      setOffDays(getOffDays(response.data.days));
      setAvailableDay(response.data.workingDays);
      setFetched(true);
    })
      .catch((error) => {
        console.log(error); // TODO - error handling
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
      // selected={startDate}
      selected={startDate}
      onChange={(date) => setStartDate(date)}
      excludeOutOfBoundsTimes
      showTimeSelect
      excludeTimes={isFetched && getTakenDates()}
      filterDate={isWorkDay}
      minDate={new Date()}
      maxDate={addDays(new Date(), 90)}
      minTime={isFetched && getStartTime()}
      maxTime={isFetched && subMinutes(getEndTime(), data.visitDuration)}
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
