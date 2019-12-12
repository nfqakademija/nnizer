import React, { useEffect } from 'react';
import { NavLink } from 'react-router-dom';
import PropTypes from 'prop-types';
import { disableBodyScroll, enableBodyScroll } from 'body-scroll-lock';
import { getTranslation } from '../../TranslationService';

const logoPath = require('../../../images/nnizer-logo.svg');

const Sidenav = (props) => {
  const { isOpen, toggleNav } = props;
  const sidenav = document.querySelector('.js-sidenav');

  useEffect(() => {
    if (isOpen) {
      disableBodyScroll(sidenav);
    } else {
      enableBodyScroll(sidenav);
    }
  });

  return (
    <nav className={`sidenav js-sidenav ${isOpen ? '-open' : ''}`}>
      <i
        role="button" 
        aria-label="close menu"
        tabIndex="0"
        className="icon-cross sidenav__close"
        onClick={() => toggleNav(!isOpen)}
        onKeyPress={() => toggleNav(!isOpen)}
      />
      <a href="/">
        <img
          src={logoPath}
          alt="nnizer typo logotype"
          className="sidenav__logo"
        />
      </a>
      <ul className="sidenav__links">
        <li className="sidenav__link -active">
          <NavLink to="/contractor/reservations" onClick={() => toggleNav(false)}>
            <i className="icon-people" />
            {getTranslation('crm.reservations')}
          </NavLink>
        </li>
        <li className="sidenav__link">
          <NavLink to="/contractor/reviews" onClick={() => toggleNav(false)}>
            <i className="icon-reviews" />
            {getTranslation('crm.reviews')}
          </NavLink>
        </li>
        <li className="sidenav__link">
          <NavLink to="/contractor/settings" onClick={() => toggleNav(false)}>
            <i className="icon-settings" />
            {getTranslation('crm.settings')}
          </NavLink>
        </li>
        <li className="sidenav__link">
          <a href="/logout" onClick={() => toggleNav(false)}>
            <i className="icon-logout" />
            {getTranslation('crm.logout')}
          </a>
        </li>
      </ul>
    </nav>
  );
};

Sidenav.propTypes = {
  isOpen: PropTypes.bool.isRequired,
  toggleNav: PropTypes.func.isRequired,
};

export default Sidenav;
