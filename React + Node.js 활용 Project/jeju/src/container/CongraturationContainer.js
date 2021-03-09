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
            name: "제주특별자치도의회 좌남수 의원",
            name2: "더불어민주당 위성곤 의원"
        },
        en: {
            css: " language-en",
            title: "Welcome Address",
            title2: "Congratulatory message",
            name: "제주특별자치도의회 좌남수 의원",
            name2: "더불어민주당 위성곤 의원"
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            title2: "중국어",
            name: "중국어",
            name2: "중국어"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            title2: "일본어",
            name: "일본어",
            name2: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';
    return (
        <section id="main_container" className={current_pack.css}>
            <div className={"tab" + current_pack.css}>
                <ul>
                    <li><Link to={LANGUAGE_PATH + Paths.session}>{current_pack.title}</Link></li>
                    <li><Link to={LANGUAGE_PATH + Paths.session + '/congraturation'} className={"on" + current_pack.css}>{current_pack.title2}</Link></li>
                </ul>
            </div>
            <div className={"main_content" + current_pack.css}>
                <div className={"speech" + current_pack.css}>
                    {/* <i></i>
                    <img src={`${process.env.PUBLIC_URL}/img/bg_speech.png`} alt="" /> */}
                    <iframe
                        title="youtube"
                        width="100%"
                        height="100%"
                        src="https://www.youtube.com/embed/D6B7JTYF3fQ" //비디오 링크가  cms에 추가하는 것이 없음
                        alt=""
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                    <span  class="conference_title_span conference_title_span_open_session"> {current_pack.name} </span>
                </div>

                <div className={"speech" + current_pack.css}>
                    {/* <i></i>
                    <img src={`${process.env.PUBLIC_URL}/img/bg_speech.png`} alt="" /> */}
                    <iframe
                        title="youtube"
                        width="100%"
                        height="100%"
                        src="https://www.youtube.com/embed/ygVM4k7lZck" //비디오 링크가  cms에 추가하는 것이 없음
                        alt=""
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                    <span class="conference_title_span conference_title_span_open_session"> {current_pack.name2} </span>
                </div>
            </div>
        </section>
    )
}