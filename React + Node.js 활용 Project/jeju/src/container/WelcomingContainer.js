import React from 'react'
import { Link } from 'react-router-dom'
import { useSelector } from 'react-redux'

import { Paths } from '../paths/index'

export default () => {

    const language = useSelector(state => state.language.current);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            title: "개회사",
            title2: "축사",
            name: "제주특별자치도지사 원희룡",
            name2: "대통령직속농어업농어촌특별위원회 위원장 정현찬"
        },
        en: {
            css: " language-en",
            title: "Welcome Address",
            title2: "Congratulatory message",
            name: "제주특별자치도지사 원희룡",
            name2: "대통령직속농어업농어촌특별위원회 위원장 정현찬"
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            title2: "중국어",
            name: "제주특별자치도지사 원희룡",
            name2: "대통령직속농어업농어촌특별위원회 위원장 정현찬"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            title2: "일본어",
            name: "제주특별자치도지사 원희룡",
            name2: "대통령직속농어업농어촌특별위원회 위원장 정현찬"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    return (
        <section id="main_container" className={current_pack.css}>
            <div className={"tab" + current_pack.css}>
                <ul>
                    <li><Link to={LANGUAGE_PATH + Paths.session} className={"on" + current_pack.css}>{current_pack.title}</Link></li>
                    <li><Link to={LANGUAGE_PATH + Paths.session + '/congraturation'}>{current_pack.title2}</Link></li>
                </ul>
            </div>
            <div className={"main_content" + current_pack.css}>
                <div className={"speech" + current_pack.css}>
                    {/* <i></i>
                    <img src={`${process.env.PUBLIC_URL}/img/bg_welcoming.png`} alt="" /> */}
                    <iframe
                        title="youtube"
                        width="100%"
                        height="100%"
                        src="https://www.youtube.com/embed/F_3m0ucTsZ0" //비디오 링크가  cms에 추가하는 것이 없음
                        alt=""
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                    <span  className="conference_title_span conference_title_span_open_session"> {current_pack.name} </span>
                </div>
                <div className={"speech" + current_pack.css}>
                    {/* <i></i>
                    <img src={`${process.env.PUBLIC_URL}/img/bg_speech.png`} alt="" /> */}
                    <iframe
                        title="youtube"
                        width="100%"
                        height="100%"
                        src="https://www.youtube.com/embed/35nf0HYGMQw" //비디오 링크가  cms에 추가하는 것이 없음
                        alt=""
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                    <span className="conference_title_span conference_title_span_open_session"> {current_pack.name2} </span>
                </div>
            </div>
        </section>
    )
}