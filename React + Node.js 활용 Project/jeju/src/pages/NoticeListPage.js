import React, { useCallback, useEffect, useState } from 'react'
import { getDocumentList } from '../api/NoticeAPI'
import qs from 'qs';

import NoticeListContainer from '../container/NoticeListContainer'
import NoticeViewContainer from '../container/NoticeViewContainer'
import Loading from '../components/assets/Loading';

export default ({ match, location, history }) => {
    const { index } = match.params; // 이걸 view인지 검출하지말고
    const viewId = parseInt(index);
    // const [viewId, setViewId] = useState(0);
    // 이것도 필요가 없지 REST_API 쓸거니까

    const query = qs.parse(location.search, {
        ignoreQueryPrefix: true,
    });
    const page = query.page ? query.page : "1";
    const p = parseInt(page);
    // 현재 페이지 가져오는거(?page= 이 없으면 1페이지)

    const [loading, setLoading] = useState(false);
    const [noticeList, setNoticeList] = useState([]);

    const callNoticeList = useCallback(async () => {
        setLoading(true);
        try {
            const res = await getDocumentList();
            if (res) {
                setNoticeList(res);
            }
        } catch (e) {
            alert('목록을 가져오는데 실패했습니다.');
        }
        setLoading(false);
    }, []);

    useEffect(() => {
        callNoticeList();
    }, [callNoticeList]);

    const getNearFromList = useCallback(id => {
        const idx = noticeList.findIndex(item => item.id === id)
        if (idx !== -1) {
            const prev = idx !== noticeList.length - 1 ? noticeList[idx + 1].id : null;
            const next = idx !== 0 ? noticeList[idx - 1].id : null;
            return { prev, next };
        } else {
            return { prev: null, next: null}; // 에러
        }
    } , [noticeList]);

    return (
        <>
            {!loading && <>
                {!isNaN(viewId) ? <NoticeViewContainer viewId={viewId} near={getNearFromList(viewId)} />
                : <NoticeListContainer page={p} noticeList={noticeList} />}
            </>}
            <Loading open={loading} />
        </>
    )
}