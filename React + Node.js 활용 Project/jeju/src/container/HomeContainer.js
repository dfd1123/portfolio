import React from 'react'
import { Link } from 'react-router-dom'
import { useSelector } from 'react-redux'

import { Paths } from '../paths/index'

import { postUserAccess } from '../api/AccessAPI';



export default () => {
    postUserAccess();                                                           // firing access API 
    const language = useSelector(state => state.language.current);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            movie_name: "<제 2회 6차산업제주국제박람회>",
            online_go: "온라인전시관",
            conference_go: "컨퍼런스",
            go: "바로가기"
        },
        en: {
            css: " language-en",
            movie_name: "<title>",
            online_go: "Online-Exhibition",
            conference_go: "Conference",
            go: "Go"
        },
        cn: {
            css: " language-cn",
            movie_name: "중국어",
            online_go: "중국어",
            conference_go: "중국어",
            go: "중국어"
        },
        jp: {
            css: " language-jp",
            movie_name: "일본어",
            online_go: "일본어",
            conference_go: "일본어",
            go: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    return (
        <section id="main_container" className={current_pack.css}>
            <div className={"main_content" + current_pack.css}>
                <div className={"movie" + current_pack.css}>
                    {/* <i></i>
                    <img src={`${process.env.PUBLIC_URL}/img/bg_movie.png`} alt="" /> */}
                    <iframe
                        title="youtube"
                        width="100%"
                        height="100%"
                        src="https://www.youtube.com/embed/RIweUEaE2xw" //비디오 링크가  cms에 추가하는 것이 없음
                        alt=""
                        frameBorder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowFullScreen
                    ></iframe>
                    {/* <span> {current_pack.movie_name} </span> */}
                </div>
                <div className={"right" + current_pack.css}>
                    <Link to={LANGUAGE_PATH + Paths.exhibition} >
                        <div className={"onlinego" + current_pack.css}>
                            <i></i>
                            <img src={`${process.env.PUBLIC_URL}/img/bg_onlinego.png`} alt="" />
                            <h3>{current_pack.online_go}</h3>
                            <span>{current_pack.go}  {'>'}</span>
                        </div>
                    </Link>
                    <Link to={LANGUAGE_PATH + Paths.conference} >
                        <div className={"confgo" + current_pack.css}>
                            <i></i>
                            <img src={`${process.env.PUBLIC_URL}/img/bg_confgo.png`} alt="" />
                            <h3>{current_pack.conference_go}</h3>
                            <span>{current_pack.go}  {'>'}</span>
                        </div>
                    </Link>
                </div>
            </div>
        </section>
    )
}