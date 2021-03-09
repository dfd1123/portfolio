import React from 'react';
/* Library */

import { useSelector } from 'react-redux';
/* Redux */

import Loading from '../../components/assets/Loading';
/* Components */

export default () => {
    const state = useSelector(state => state.loading);
    return <Loading open={Object.values(state).reduce((prev, cur) => prev || cur, false)} />
};
