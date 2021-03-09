import React from 'react';
import { makeStyles } from '@material-ui/core/styles';
/* Library */

import { Backdrop, CircularProgress } from '@material-ui/core';
/* Components */

const useStyles = makeStyles((theme) => ({
    backdrop: {
        zIndex: theme.zIndex.drawer + 1,
        color: '#fff'
    },
}));

export default ({ open }) => {
    const classes = useStyles();
    return (
        <Backdrop className={classes.backdrop} open={open}>
            <CircularProgress color="inherit" />
        </Backdrop>
    );
};
