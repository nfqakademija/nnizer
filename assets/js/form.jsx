import React, { useState, useEffect } from 'react';
import { render } from 'react-dom';
import axios from 'axios';
import DatePicker from 'react-datepicker';
import { getDay, addDays, isSameDay } from 'date-fns';
import { setHours, setMinutes } from 'date-fns/esm';
// import { lt } from 'date-fns/locale/lt';
// registerLocale('lt', lt);

import('../../node_modules/react-datepicker/dist/react-datepicker.min.css');

const Form = () => {
  const [startDate, setStartDate] = useState(0);
  const [data, setData] = useState([]);
  const [isFetched, setFetched] = useState(false);
  const [offDays, setOffDays] = useState([]);

  const getOffDays = (days) => Object.keys(days).filter((key) => days[key].isWorkday === false);

  const isWorkDay = (date) => !offDays.includes(getDay(date).toString());

  const getTime = (timeType) => {
    const selectedDay = getDay(startDate);
    const [hours, minutes] = data.days[selectedDay][timeType].split(':');
    return setHours(setMinutes(new Date(), minutes), hours);
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

    axios.get(`/api/profile/${contractorName}/working-hours/`)
      .then((response) => {
        setData(response.data);
        setOffDays(getOffDays(response.data.days));
        setFetched(true);
      })
      .catch((error) => {
        console.log('error'); // TODO handle error display in UI
      });
  };

  useEffect(() => {
    fetchData();
  },
  [startDate]);

  return (
    <DatePicker
      selected={startDate}
      onChange={(date) => setStartDate(date)}
      excludeOutOfBoundsTimes
      showTimeSelect
      excludeTimes={isFetched && getTakenDates()}
      filterDate={isWorkDay}
      minDate={new Date()}
      maxDate={addDays(new Date(), 90)}
      minTime={isFetched && getTime('startTime')}
      maxTime={isFetched && getTime('endTime')}
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
  <Form />,
  document.querySelector('#datepicker'),
);
