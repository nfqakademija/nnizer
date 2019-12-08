import React from 'react';
import PropTypes from 'prop-types';
import uuidv4 from 'uuid/v4';


const WorkHours = (props) => {
  const { days } = props;

  const weekDays = {
    0: 'Monday',
    1: 'Tuesday',
    2: 'Wednesday',
    3: 'Thursday',
    4: 'Friday',
    5: 'Saturday',
    6: 'Sunday',
  };

  const getWorkHours = () => (
    days.map((day, dayNum) => (
      <li key={uuidv4()} className="work-days__row">
        <div className="work-days__day">
          <div className={`status -circle ${day.isWorkday ? '-open' : '-closed'}`} />
          {weekDays[dayNum]}
        </div>
        <time className="work-days__time">
          {day.isWorkday ? `${day.startTime} - ${day.endTime}` : 'CLOSED' }
        </time>
      </li>
    ))
  );

  return (
    <div className="work-days">
      <h2 className="contractor__heading">
        Work hours
      </h2>
      <ul className="work-days__list">
        {getWorkHours()}
      </ul>
    </div>

  );
};

WorkHours.propTypes = {
  days: PropTypes.arrayOf(PropTypes.shape({})).isRequired,
};

export default WorkHours;
