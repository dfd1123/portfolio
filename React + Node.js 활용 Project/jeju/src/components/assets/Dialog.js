import React, { useCallback } from 'react';
import { makeStyles } from '@material-ui/core/styles';
/* Library */

import { useDispatch } from 'react-redux';
import { dialogClose } from '../../store/dialog';
/* Redux */

import { Backdrop, ButtonBase } from '@material-ui/core';
/* Components */

const useStyles = makeStyles((theme) => ({
    backdrop: {
        zIndex: theme.zIndex.drawer + 1,
    },
}));

export default ({ confirm, title, text, handleClick = () => {}, open }) => {
    const classes = useStyles();
    const dispatch = useDispatch();

    const onClose = useCallback(() => dispatch(dialogClose()), [dispatch]);
    const onClick = useCallback(() => {
        handleClick();
        onClose();
    }, [handleClick, onClose]);

    return (
        <>
            <div>
                <div>
                    <div>
                        <h3>{title}</h3>
                        <p>{text}</p>
                    </div>
                    <div>
                        {confirm && (
                            <ButtonBase onClick={onClose}>아니오</ButtonBase>
                        )}
                        <ButtonBase onClick={onClick}>
                            {/* {confirm ? '예' : '확인'} */}
                        </ButtonBase>
                    </div>
                </div>
            </div>
            <Backdrop
                className={classes.backdrop}
                open={open}
                onClick={onClose}
            />
        </>
    );
};
