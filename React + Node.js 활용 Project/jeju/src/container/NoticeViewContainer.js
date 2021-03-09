import React, { useCallback, useEffect, useState } from 'react'
import { Link, useHistory } from 'react-router-dom'
import { Paths } from '../paths'

import { getShowDocument } from '../api/NoticeAPI'
import Loading from '../components/assets/Loading'
import { dateToYYYYMMDD } from '../lib/formatter'
import { useSelector } from 'react-redux'

export default ({ viewId, near }) => {
    const history = useHistory();
    const [loading, setLoading] = useState(false);
    const [noticeView, setNoticeView] = useState({})

    const language = useSelector(state => state.language.current);

    const callNoticeView = useCallback(async () => {
        setLoading(true);
        // api 요청할 때는 로딩 중이 필요하다
        try {
            const res = await getShowDocument(viewId);
            if (res) {
                setNoticeView(res);
            }
        } catch (e) {
            // 예외처리 해주고
            alert('삭제되거나 없는 게시물입니다.');
            history.goBack();
        }
        setLoading(false);
    }, [viewId, history]);

    useEffect(() => {
        try {
            callNoticeView();
        } catch (e) {
            alert('서버에 오류가 발생했습니다.');
        }
        return () => setLoading(false); // cleanup function을 이용
    }, [callNoticeView])

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            title: "공지사항",
            list: "목 록",
            prev: "이전글",
            next: "다음글"
        },
        en: {
            css: " language-en",
            title: "Notice",
            list: "List",
            prev: "Prev",
            next: "Next"
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            list: "중국어",
            prev: "중국어",
            next: "중국어"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            list: "일본어",
            prev: "일본어",
            next: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const URL = "http://14.63.174.102:84"
    return (
        <section id="comm_container" className={current_pack.css}>
            <div className={"tab" + current_pack.css}>
                <ul>
                    <li>
                        <Link to={LANGUAGE_PATH + Paths.notice} className={"on" + current_pack.css}>
                            {current_pack.title}
                        </Link>
                    </li>
                </ul>
            </div>
            {!loading && (
                <div className={"noticeview" + current_pack.css}>
                    <div className={"viewhead" + current_pack.css}>
                        <h3>{noticeView.title}</h3>
                        <ul>
                            <li>{current_pack.title}</li>
                            <li>{dateToYYYYMMDD(noticeView.created_at)}</li>
                            {/* <li>조회수 637</li> */}
                        </ul>
                    </div>
                    <div className={"viewcontent" + current_pack.css}>
                        {noticeView.contents}
                        <div className={"file" + current_pack.css}>
                            {noticeView.file_1 && <a href={URL + noticeView.file_1} rel="noopener noreferrer" target="_blank" download>
                                <img
                                    src={`${process.env.PUBLIC_URL}/img/ic_download.png`}
                                    alt="download"
                                />
                            </a>}
                            <span>
                                {noticeView.file_1 && <a href={URL + noticeView.file_1} rel="noopener noreferrer" target="_blank" download>{noticeView.file_1}</a>}
                                {/* <em>334kb</em> */}
                            </span>
                        </div>
                    </div>
                    <div className={"btbox" + current_pack.css}>
                        <Link to={LANGUAGE_PATH + Paths.notice} className={"bk" + current_pack.css}>
                            {current_pack.list}
                        </Link>
                        {near.next && <Link to={LANGUAGE_PATH + Paths.notice + '/' + near.next} className={"wr" + current_pack.css}>
                            {current_pack.next}
                        </Link>}
                        {near.prev && <Link to={LANGUAGE_PATH + Paths.notice + '/' + near.prev} className={"wr" + current_pack.css}>
                            {current_pack.prev}
                        </Link>}
                    </div>
                </div>
            )}
            <Loading open={loading} />
        </section>
    );
}