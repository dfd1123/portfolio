import React, { useCallback, useEffect, useState } from 'react';
import { useHistory } from 'react-router-dom';
import { makeStyles } from '@material-ui/core/styles';
import { useSelector } from 'react-redux';

//styles

// import cn from 'classnames/bind';
//components
import Dialog from '@material-ui/core/Dialog';
import DialogContent from '@material-ui/core/DialogContent';
import Header from '../header/Header';
import AddressList from '../address/AddressList';

import Slide from '@material-ui/core/Slide';
import styles from './AddressModal.module.scss';

//lib
import { isEmpty } from '../../lib/formatChecker';

//api
import { requestGetLikeParkingList } from '../../api/place';
import { Paths } from '../../paths';
import Notice from '../../static/asset/svg/Notice';

const useStyles = makeStyles((theme) => ({
    appBar: {
        position: 'relative',
        textAlign: 'center',
        backgroundColor: 'white',
        color: 'black',
        boxShadow: 'none',
        borderBottom: 'solid 1px #aaa',
        fontSize: 10,
    },
    title: {
        textAlign: 'center',
        width: '100%',
        fontSize: 16,
    },
    toolbar: {
        display: 'flex',
        flexDirection: 'row',
        alignItems: 'center',
        justifyContent: 'center',
    },
    content: {
        minHeight: '100vh',
        zIndex: 3000,
        padding: 0,
        paddingTop: 70,
        paddingLeft: 24,
        paddingRight: 24,
        paddingBottom: 60,
        flex: '0 0 auto',
    },
    close: {
        position: 'absolute',
        width: '40px',
        height: '40px',
        left: 14,
        zIndex: 2100,
    },
}));

const Transition = React.forwardRef(function Transition(props, ref) {
    return <Slide direction="up" ref={ref} {...props} />;
});

const BookmarkModal = (props) => {
    const classes = useStyles();
    const user = useSelector((state) => state.user);
    const history = useHistory();
    const [bookmark, setBookmark] = useState([]);

    const getCallBookmarkApi = useCallback(async () => {
        if (!isEmpty(user)) {
            const JWT_TOKEN = localStorage.getItem('user_id');
            if (JWT_TOKEN) {
                try {
                    const res = await requestGetLikeParkingList(JWT_TOKEN);
                    if (res.data.msg === 'success') {
                        const { places } = res.data;
                        setBookmark(places);
                    }
                } catch (e) {
                    console.error(e);
                }
            }
        }
    }, [user]);

    const onClickBookmarkItem = useCallback(
        (place_id) => history.push(Paths.main.detail + '?place_id=' + place_id),
        [history],
    );

    useEffect(getCallBookmarkApi, [getCallBookmarkApi]);

    return (
        <Dialog
            fullScreen
            open={props.open}
            onClose={props.handleClose}
            TransitionComponent={Transition}
            className={classes.dialog}
        >
            <Header title={'즐겨찾는 주차장'} />
            {bookmark.length ? (
                <DialogContent className={classes.content}>
                    <div className={styles['container']}>
                        <div className={styles['item-list']}>
                            <AddressList
                                addr_list={bookmark}
                                type={2}
                                onClick={onClickBookmarkItem}
                            />
                        </div>
                    </div>
                </DialogContent>
            ) : (
                <div className={styles['non-qna']}>
                    <div className={styles['non-container']}>
                        <Notice />
                        <div className={styles['explain']}>
                            {!isEmpty(user)
                                ? '즐겨찾는 주차공간이 없습니다.'
                                : '로그인 후 이용해 주세요.'}
                        </div>
                    </div>
                </div>
            )}
        </Dialog>
    );
};

export default BookmarkModal;
