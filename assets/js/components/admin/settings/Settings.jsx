import React from 'react';
import PropTypes from 'prop-types';

import { getTranslation } from '../../../Utils/TranslationService';

import SettingsBtn from './SettingsBtn';
import Field from './Field';

// TODO: TRANSLATIONS

const Settings = (props) => {
  const { userData } = props;
  const baseURL = `${window.location.protocol}//${window.location.host}`;

  return (
    <div className="panel__content admin-container">
      <h2>{getTranslation('crm.settings')}</h2>
      <div className="profile-settings col-md-12 col-lg-8">
        <img
          src={`${baseURL}/uploads/profile/${userData.profilePhoto.filename}`}
          alt={`${userData.username}'s profile picture`}
          className="profile-settings__avatar"
        />
        <h3 className="profile-settings__name">
          { userData.firstname.length !== 0
            ? `${userData.firstname} ${userData.lastname}`
            : `${userData.username}` }
        </h3>
        <SettingsBtn />
        <div className="profile-settings__fields row">
          <Field label="Phone number" output={userData.phoneNumber} icon="phone" />
          <Field label="Email address" output={userData.email} icon="envelope" />
          <Field label="Address" output={userData.address} icon="location" />
          <Field label="Facebook" output={userData.facebook} icon="facebook" />
          <Field label="Service name" output={userData.title} columns="12" />
          <Field label="Service description" output={userData.description} columns="12" />
        </div>
      </div>
    </div>
  );
};

Settings.propTypes = {
  userData: PropTypes.oneOfType([PropTypes.object]).isRequired,
};

export default Settings;
