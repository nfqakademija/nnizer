import React, { useState } from 'react';
import { render } from 'react-dom';
import DatePicker from 'react-datepicker';

// import('../../node_modules/react-datepicker/dist/react-datepicker-cssmodules.css');
import('../../node_modules/react-datepicker/dist/react-datepicker.css');

const Form = () => {
  const [startDate, setStartDate] = useState(new Date());
  return (
    <DatePicker
      selected={startDate}
      onChange={date => setStartDate(date)}
      showTimeSelect
      timeFormat="HH:mm"
      timeIntervals={15}
      timeCaption="time"
      dateFormat="yyyy-MM-dd HH:mm"
      name="visitDate"
    />
  );
};

render(
  <Form />,
  document.querySelector('#datepicker'),
);
