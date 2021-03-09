import React, { useEffect, useCallback, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { useHistory } from 'react-router-dom';
import qs from 'qs';

import OnlineExhibitionContainer from '../container/OnlineExhibitionContainer';
import OnlineExhibitionListContainer from '../container/OnlineExhibitionListContainer';

import { getDocumentList } from '../api/OnlineExhibitionAPI';
import { getUserAccessCount } from '../api/AccessAPI';


import { firstModalOpen } from '../store/modal';
import { setID } from '../store/exhibition';
import { Paths } from '../paths';
import SwiperContainer from '../container/SwiperContainer';
import { getCookie, setCookie } from '../lib/cookie';



const OnlineExhibitionPage = ({ match, location }) => {
    const { index } = match.params;
    const viewId = parseInt(index);

    const query = qs.parse(location.search, {
        ignoreQueryPrefix: true,
    });
    const type = query.type ? query.type : "0";
    const t = parseInt(type);

    const dispatch = useDispatch();
    const history = useHistory();
    const language = useSelector(state => state.language.current);

    const [items, setItems] = useState([]);
    const [swiper, setSwiper] = useState('');
    const [loading, setLoading] = useState(false);

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    const firstOpen = useCallback((id) => {
        window.scrollTo(0, 0);
        dispatch(setID(id));
        // const TOKEN = localStorage.getItem('token');
        const TOKEN = getCookie('token');
        if (TOKEN) {
            history.push(LANGUAGE_PATH + Paths.exhibition + '/' + id);
        } else {
            // localStorage.setItem('token', true);
            
                    

            setCookie('token', true, 1);
            if(language != "en")
            dispatch(firstModalOpen());
        }
    }, [dispatch, history, LANGUAGE_PATH]);

    const callGetDocumentList = useCallback(async () => {
        setLoading(true);
        try {
            const res = await getDocumentList(0); // default : 0
            res.sort((a, b) => { return (a.title < b.title) ? -1 : (a.title > b.title) ? 1 : 0; });

            const swiperItem = res.filter(item => item.type === 8 || item.type === 9);
            swiperItem.sort((a, b) => { return a.title < b.title ? -1 : a.title > b.title ? 1 : 0; });
            setSwiper(<SwiperContainer dataSet={swiperItem} firstOpen={firstOpen} />);
            setItems(res);
        } catch (e) {
            alert('서버에 오류가 발생했습니다.');
            setSwiper(<SwiperContainer dataSet={"Error"} />)
        }
        setLoading(false);
    }, [firstOpen]);


const callGetAccessLog = useCallback(async () => {
        const res = await getUserAccessCount(0); // default : 0
        document.getElementsByClassName('user_count_text')[0].innerHTML = res
        console.log(res)
    });
    
    
    
    useEffect(() => {
        try {
            callGetDocumentList();
            callGetAccessLog();
        } catch (e) {
            alert('서버에 오류가 발생했습니다.');
        }
    }, [callGetDocumentList]);

    return (
        <>
            {!isNaN(viewId) ? <OnlineExhibitionContainer viewId={viewId} />
                : <OnlineExhibitionListContainer type={t} items={items} loading={loading} swiper={swiper} firstOpen={firstOpen} />}

        </>
    );
};

export default OnlineExhibitionPage;
