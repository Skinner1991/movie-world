import React from 'react';
import classes from './LoaderOverlay.module.css';

export function LoaderOverlay() {
  return (
    <div className={classes.overlay}>
      <div className={classes.spinner} />
    </div>
  );
}
