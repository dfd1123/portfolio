import React, { useCallback } from 'react'
import { useSelector } from 'react-redux';
import { useHistory } from 'react-router-dom'
import { Paths } from '../paths';

export default ({ noticeList, currentPage }) => {

    const language = useSelector(state => state.language.current);
    const history = useHistory();
    const listLength = noticeList.length
    const paging = []

    const paginationButton = (listLength) => {
        let leng = undefined
        if (listLength % 10 === 0) leng = Math.floor(listLength / 10)
        else leng = Math.floor(listLength / 10 + 1)

        for (let i = 0; i < leng; i++) {
            paging.push(i + 1)
        }
    }

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    const pageLink = useCallback(page => {
        if (page <= 0) history.push(LANGUAGE_PATH + Paths.notice + '?page=1')
        else if (page > paging.length) history.push(LANGUAGE_PATH + Paths.notice + '?page=' + paging.length)
        else history.push(LANGUAGE_PATH + Paths.notice + '?page=' + page)
    }, [paging, history, LANGUAGE_PATH]);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: ""
        },
        en: {
            css: " language-en"
        },
        cn: {
            css: " language-cn"
        },
        jp: {
            css: " language-jp"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    return (
        <>
            <li><div onClick={() => pageLink(1)} ><img src={`${process.env.PUBLIC_URL}/img/ic_first.png`} alt="" /></div></li>
            <li><div onClick={() => pageLink(parseInt(currentPage) - 1)} ><img src={`${process.env.PUBLIC_URL}/img/ic_prev.png`} alt="" /></div></li>

            {paginationButton(listLength)}
            {
                paging.map(item =>
                    <li key={item} className={item + current_pack.css}><div onClick={() => pageLink(item)} className={item === currentPage ? "on" : "" + current_pack.css} >{item}</div></li>
                )
            }

            <li><div onClick={() => pageLink(parseInt(currentPage) + 1)} ><img src={`${process.env.PUBLIC_URL}/img/ic_next.png`} alt="" /></div></li>
            <li><div onClick={() => pageLink(paging.length)} ><img src={`${process.env.PUBLIC_URL}/img/ic_end.png`} alt="" /></div></li>

        </>
    )
}