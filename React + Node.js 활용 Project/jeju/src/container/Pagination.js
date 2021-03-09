import React from 'react'
import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom'
import { dateToYYYYMMDD } from '../lib/formatter';
import { Paths } from '../paths';

const PAGE_PER_VIEW = 10; // 한 페이지에 10개 보여주겠다.

export default ({ noticeList, currentPage }) => {

    const language = useSelector(state => state.language.current);
    // const currentList = noticeList.slice(currentPage.page * 10 - 10, currentPage.page * 10 - 1)
    // 이것도 수식을 이렇게 하면 중복 숫자 10 제거..
    const currentList = noticeList.slice((currentPage - 1) * PAGE_PER_VIEW, currentPage * PAGE_PER_VIEW);
    
    
    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            go: "바로가기"
        },
        en: {
            css: " language-en",
            go: "Link"
        },
        cn: {
            css: " language-cn",
            go: "중국어"
        },
        jp: {
            css: " language-jp",
            go: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    return (
        <>
            {currentList.map(item =>
                <tr key={item.id}>
                    <td>{dateToYYYYMMDD(item.created_at)}</td>
                    <td><Link to={LANGUAGE_PATH + Paths.notice + '/' + item.id} >{item.title}</Link></td>
                    <td><Link to={LANGUAGE_PATH + Paths.notice + '/' + item.id} className={"go" + current_pack.css} >{current_pack.go}</Link></td>
                </tr>
            )}
        </>
    );
};