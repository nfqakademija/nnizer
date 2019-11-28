import React from 'react';
import PropTypes from 'prop-types';

const PanelHeader = (props) => {
  const { isOpen, toggleNav } = props;
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
          placeholder="Type in to search…"
          className="js-search-input"
        />
      </div>
      <div className="header__person">
        <img src="https://avataaars.io/?avatarStyle=Circle&topType=Hat&accessoriesType=Blank&facialHairType=Blank&clotheType=BlazerShirt&eyeType=Default&eyebrowType=Default&mouthType=Default&skinColor=Light" className="person__avatar" alt="users profile avatar" />
        <span className="person__name">Kornelijus Glinskas</span>
      </div>
    </header>
  );
};

PanelHeader.propTypes = {
  toggleNav: PropTypes.func.isRequired,
  isOpen: PropTypes.bool.isRequired,
};


export default PanelHeader;
