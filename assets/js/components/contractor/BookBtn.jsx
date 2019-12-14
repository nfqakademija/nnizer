import React, { useEffect } from 'react';
import MicroModal from 'micromodal';
import { getTranslation } from '../../TranslationService';

const BookBtn = () => {
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
      data-micromodal-trigger="register-modal"
    >
      {getTranslation('contractor.book')}
    </button>
  );
};

export default BookBtn;
