import React, { useEffect } from 'react';
import MicroModal from 'micromodal';

const SettingsBtn = () => {
  const initModal = () => {
    MicroModal.init({
      disableScroll: true,
      disableFocus: false,
      awaitOpenAnimation: true,
      awaitCloseAnimation: true,
    });
  };

  useEffect(() => {
    initModal();
  },
  []);

  return (
    <button
      className="contractor-btn"
      type="button"
      data-micromodal-trigger="settings-modal"
    >
      Change settings
    </button>
  );
};

export default SettingsBtn;
