import React from 'react';
import PropTypes from 'prop-types';

import About from './content/About';
import WorkHours from './content/WorkHours';
import Reviews from './content/Reviews';
import Info from './content/Info';

/*
** dummy data
*/
// const reviews = [
//   {
//     stars: 5,
//     reservation: {
//       firstname: 'klientas',
//       lastname: 'velnias',
//       visitDate: '2019-12-07T16:50:52+00:00',
//     },
//     description: 'Nu gerai  pavare tikrai rekomenduoju ;D :D ',
//   },
//   {
//     stars: 4,
//     reservation: {
//       firstname: 'Adovakatas',
//       lastname: 'Pironickas',
//       visitDate: '2019-12-08T16:50:52+00:00',
//     },
//     description: 'Išbandyk mano paslaugas, kurios yra pigios.',
//   },
//   {
//     stars: 5,
//     reservation: {
//       firstname: 'Lopas',
//       lastname: 'Lopauskas',
//       visitDate: '2019-12-09T16:50:52+00:00',
//     },
//     description: 'Nu geras asmeninis vairuotojas rekomendacijos daugiau nieko maziau irgi',
//   },
// ];

const Content = (props) => {
  const { userData } = props;

  // userData.reviews = reviews;

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
          <Reviews reviews={userData.reviews} />
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
