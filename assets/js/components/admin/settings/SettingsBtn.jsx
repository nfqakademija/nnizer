import React from 'react';
import getTranslation from "../../../Utils/TranslationService";

const SettingsBtn = () => (
  <a
    className="btn -no-margin"
    type="button"
    href="/contractor/edit"
  >
    {getTranslation('crm.change_settings')}
  </a>
);


export default SettingsBtn;
