import { toast } from 'react-toastify';

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
