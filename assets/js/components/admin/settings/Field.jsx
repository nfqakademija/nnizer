import React from 'react';
import PropTypes from 'prop-types';
import {getTranslation} from "../../../Utils/TranslationService";

const Settings = (props) => {
  const {
    label,
    output,
    icon,
    columns,
  } = props;

  return (
    <div className={`col-md-${columns}`}>
      <span className="field__label">{label}</span>
      <div className="field__output">
        { icon !== ''
          && <i className={`field__icon icon-${icon}`} /> }
        { output === null
          ? getTranslation('crm.no_details')
          : output }
      </div>
    </div>
  );
};

Settings.propTypes = {
  label: PropTypes.string.isRequired,
  output: PropTypes.string,
  icon: PropTypes.string,
  columns: PropTypes.string,
};

Settings.defaultProps = {
  output: '',
  icon: '',
  columns: '6',
};

export default Settings;
