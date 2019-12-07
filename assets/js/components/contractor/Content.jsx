import React from 'react';

import About from './content/About';
import WorkHours from './content/WorkHours';
import Reviews from './content/Reviews';
import Info from './content/Info';

const Content = () => {
  return (
    <div className="contractor__content container">
      <div className="row">
        <div className="col-md-7">
          <About />
        </div>
        <div className="col-md-5">
          <WorkHours />
        </div>
      </div>
      <div className="row">
        <div className="col-md-7">
          <Reviews />
        </div>
        <div className="col-md-5">
          <Info />
        </div>
      </div>
    </div>
  );
};

export default Content;
