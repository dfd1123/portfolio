import React from 'react'
import { useSelector } from 'react-redux'
import { Link } from 'react-router-dom'

import { Paths } from '../paths/index'


import Pagination from './Pagination'
import PaginationButton from './PaginationButton'

export default ({ page, noticeList }) => {
    const language = useSelector(state => state.language.current);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            title: "공지사항",
            amount: "총",
            amount2: "건의 글이 있습니다.",
            date: "작성일자",
            subject: "제목",
            go: "보기"
        },
        en: {
            css: " language-en",
            title: "Notice",
            amount: "There are a total",
            amount2: "articles",
            date: "Date",
            subject: "Contents",
            go: "View"
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            amount: "중국어",
            amount2: "중국어",
            date: "중국어",
            subject: "중국어",
            go: "중국어"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            amount: "일본어",
            amount2: "일본어",
            date: "일본어",
            subject: "일본어",
            go: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------
 
    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    return (
        <section id="comm_container" className={current_pack.css}>
            <div className={"tab" + current_pack.css}>
                <ul>
                    <li><Link to={LANGUAGE_PATH + Paths.notice} className={"on" + current_pack.css}>{current_pack.title}</Link></li>
                </ul>
            </div>
            <div className={"noticebox" + current_pack.css}>
                <span>{current_pack.amount} <strong>{noticeList.length}</strong> {current_pack.amount2}</span>
                <table>
                    <caption>공지사항 내용을 보여주는 표</caption>
                    <colgroup>
                        <col style={{ width: "15%" }} />
                        <col />
                        <col style={{ width: "15%" }} />
                    </colgroup>
                    <thead>
                        <tr>
                            <th scope="col">{current_pack.date}</th>
                            <th scope="col">{current_pack.subject}</th>
                            <th scope="col">{current_pack.go}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <Pagination noticeList={noticeList} currentPage={page} />
                    </tbody>
                </table>
                <div className={"tnavi" + current_pack.css}>
                    <ul>
                        <PaginationButton noticeList={noticeList} currentPage={page} />
                    </ul>
                </div>
            </div>
        </section>
    )
}