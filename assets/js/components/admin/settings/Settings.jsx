import React from 'react';
import PropTypes from 'prop-types';

import { getTranslation } from '../../../Utils/TranslationService';

import SettingsBtn from './SettingsBtn';
import Field from './Field';


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
          { userData.firstname != null
            ? `${userData.firstname} ${userData.lastname}`
            : `${userData.username}` }
        </h3>
        <a
          href={`${baseURL}/service/${userData.username}`}
          target="_blank"
          rel="noopener noreferrer"
          className="profile-settings__website"
        >
          <i className="icon-website" />
          { `${window.location.host}/service/${userData.username}` }
        </a>
        <SettingsBtn />
        <div className="profile-settings__fields row">
          <Field label={getTranslation('crm.phone')} output={userData.phoneNumber} icon="phone" />
          <Field label={getTranslation('crm.email')} output={userData.email} icon="envelope" />
          <Field label={getTranslation('crm.address')} output={userData.address} icon="location" />
          <Field label={getTranslation('crm.facebook')} output={userData.facebook} icon="facebook" />
          <Field label={getTranslation('crm.service_title')} output={userData.title} columns="12" />
          <Field label={getTranslation('crm.service_description')} output={userData.description} columns="12" />
        </div>
      </div>
    </div>
  );
};

Settings.propTypes = {
  userData: PropTypes.oneOfType([PropTypes.object]).isRequired,
};

export default Settings;
