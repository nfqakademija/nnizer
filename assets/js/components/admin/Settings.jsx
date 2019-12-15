import React from 'react';
import { getTranslation } from '../../TranslationService';

const Settings = (props) => {
  return (
    <div className="panel__content admin-container">
      <h2>{getTranslation('crm.settings')}</h2>
    </div>
  );
};


export default Settings;
