import React from 'react';
import { toast, ToastContainer } from 'react-toastify';

let toastId = null;

export const showAlert = (message, status, closeTime) => {
  toastId = toast(message, {
    className: `notification -${status}`,
    progressClassName: 'notification__bar',
    autoClose: closeTime,
  });
};

export const updateAlert = (message, status, closeTime) => {
  toast.update(toastId, {
    render: message,
    className: `notification -${status}`,
    autoClose: closeTime,
    type: toast.TYPE.INFO,
  });
};

export const Alert = () => {
  const CloseButton = (closeToast) => (
    <i className="icon-cross notification__close" onClick={() => closeToast} />
  );

  return (
    <ToastContainer closeButton={<CloseButton />} />
  );
};
