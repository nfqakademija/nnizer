import React, { forwardRef, useRef, useImperativeHandle } from 'react';
import { ToastContainer, toast } from 'react-toastify';

const Notification = forwardRef((props, ref) => {
  useImperativeHandle(ref, () => ({
    getAlert() {
      toast.success('Wow so easy !', {
        className: 'notification',
      });
    },
  }));

  return <ToastContainer />;
});

export default Notification;
