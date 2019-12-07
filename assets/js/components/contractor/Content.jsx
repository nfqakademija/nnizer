import React from 'react';
import PropTypes from 'prop-types';

import About from './content/About';
import WorkHours from './content/WorkHours';
import Reviews from './content/Reviews';
import Info from './content/Info';

const Content = (props) => {
  const { userData } = props;

  return (
    <div className="contractor__content container">
      <div className="row">
        <div className="col-md-7">
          <About description={userData.description} />
        </div>
        <div className="col-md-5">
          <WorkHours days={userData.days} />
        </div>
      </div>
      <div className="row">
        <div className="col-md-7">
          <Reviews />
        </div>
        <div className="col-md-5">
          <Info
            address={userData.address}
            facebook={userData.facebook}
            email={userData.email}
            duration={userData.visitduration}
            phoneNumber={userData.phoneNumber}
          />
        </div>
      </div>
    </div>
  );
};

Content.propTypes = {
  userData: PropTypes.oneOfType([PropTypes.object]).isRequired,
};

export default Content;
