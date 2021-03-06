import React, { useLocation } from 'react';
import PropTypes from 'prop-types';
import Translator from 'bazinga-translator';

import { getTranslation } from '../../Utils/TranslationService';

const flags = {
  lt:  require('../../../images/LT-flag.svg'),
  en: require('../../../images/EN-flag.svg'),
};

const PanelHeader = (props) => {
  const {
    isOpen,
    toggleNav,
    avatar,
    name,
    setSearchTerm,
    searchTerm,
  } = props;
  const searchInput = React.createRef();

  const baseURL = `${window.location.protocol}//${window.location.host}`;
  let { locale } = Translator;
  locale = locale === 'lt' ? 'en' : 'lt';

  const handleSearch = () => {
    searchInput.current.focus();
  };

  return (
    <header className="panel__header admin-container">
      <button
        type="button"
        className="header__toggler js-toggler"
        onClick={() => toggleNav(!isOpen)}
      >
        <i className="icon-menu" />
      </button>
      <div
        role="button"
        aria-label="toggle search"
        tabIndex="0"
        className="search header__search"
        onClick={handleSearch}
        onKeyPress={handleSearch}
      >
        <i className="icon-search" />
        <input
          type="search"
          ref={searchInput}
          placeholder={getTranslation('crm.search')}
          onChange={(e) => setSearchTerm(e.target.value)}
          value={searchTerm}
          className="js-search-input"
        />
      </div>
      <div className="header__person">
        <img
          src={avatar}
          className="person__avatar"
          alt="users profile avatar"
        />
        <span className="person__name">{name}</span>
      </div>
      <a
        className="footer__lang-switcher"
        href={`/lang/${locale}`}
      >
        <img
          src={flags[locale]}
          alt={`${locale} flag`}
        />
      </a>
    </header>
  );
};

PanelHeader.propTypes = {
  toggleNav: PropTypes.func.isRequired,
  isOpen: PropTypes.bool.isRequired,
  setSearchTerm: PropTypes.func.isRequired,
  avatar: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  searchTerm: PropTypes.string.isRequired,
};

export default PanelHeader;
