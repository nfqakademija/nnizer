import React from 'react';
import ContentLoader from 'react-content-loader';

const Loader = () => (
  <ContentLoader
    height={475}
    width={400}
    speed={2}
    primaryColor="#F2EFEA"
    secondaryColor="#ffc600"
  >
    <rect x="0" y="0" rx="0" ry="0" width="400" height="200" style={{ width: '100vw', height: '100vh' }} />
  </ContentLoader>
);

export default Loader;
