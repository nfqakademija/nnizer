import React from 'react';

const Info = () => {
  return (
    <div className="info">
      <h2 className="contractor__heading">Info</h2>
      <ul className="info__list">
        <li className="info__row">
          <i className="info__icon icon-location" />
          <div className="info__box">
            <h3 className="info__title">Location</h3>
            <a href="" className="link -hover-underline">massageparadise.lt</a>
          </div>
        </li>
      </ul>
    </div>
  );
};

export default Info;
