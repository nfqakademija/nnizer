import React, { useLocation } from 'react';
import PropTypes from 'prop-types';
import { getTranslation } from '../../TranslationService';

const PanelHeader = (props) => {
  const { isOpen, toggleNav, avatar, name } = props;
  const searchInput = React.createRef();

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
    </header>
  );
};

PanelHeader.propTypes = {
    toggleNav: PropTypes.func.isRequired,
    isOpen: PropTypes.bool.isRequired,
    avatar: PropTypes.string.isRequired,
    name: PropTypes.string.isRequired,
};

export default PanelHeader;
