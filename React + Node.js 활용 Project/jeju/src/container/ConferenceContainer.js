import React from 'react'

import { useSelector }
from 'react-redux'

import { Link }
from 'react-router-dom'

import { Paths }
from '../paths/index'

export default () => {

const language = useSelector(state => state.language.current);

//--------------------------------------------------------------------------------------
const LANGUAGE_PACK = {
    kr: {
        css: "",
        title: "컨퍼런스"
    },
    en: {
        css: " language-en",
        title: "Conference"
    },
    cn: {
        css: " language-cn",
        title: "중국어"
    },
    jp: {
                        css: " language-jp",
                        title: "일본어"
                    }
                }

                const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
                //--------------------------------------------------------------------------------------

                const LANGUAGE_PATH = language !== '' ? `/${language}` : '';
                return (
<section id="main_container" className={current_pack.css}>
    <div className={"tab" + current_pack.css}>
        <ul>
            <li><Link to={LANGUAGE_PATH + Paths.conference} className={"on" + current_pack.css}>{current_pack.title}</Link></li>
        </ul>
    </div>
    <div className={"main_content" + current_pack.css}>
        <div className={"speech speech2" + current_pack.css}>
            {/* <i></i> */}

            {/* <img src={`${process.env.PUBLIC_URL}/img/bg_speech.png`} alt="" /> */}
            <iframe 
                className="confirence_iframe"
                title="youtube"
                width="100%"/*100%에서 수정 ***************** */
                height="551px"  
                src="https://www.youtube.com/embed/5gFjvJ-6Qo4" //비디오 링크가  cms에 추가하는 것이 없음 //추가 수정됨
                alt=""
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
                ></iframe>
            <span className="conference_title_span">* 세션1 [학술세미나] 주제발표 및 종합토론</span>


        </div>   
        <div className={"speech speech2" + current_pack.css } >
            <iframe 
                className="confirence_iframe"
                title="youtube"
                width="100%"/*100%에서 수정 ***************** */
                height="551px"  
                src="https://www.youtube.com/embed/hdGXX92-bHg" //비디오 링크가  cms에 추가하는 것이 없음 //추가 수정됨
                alt=""
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
                ></iframe>
            <span className="conference_title_span">* 세션2 [마케팅세미나] 주제발표</span>
        </div>  
        <div className={"speech speech2" + current_pack.css}>
            <iframe 
                className="confirence_iframe"
                title="youtube"
                width="100%"/*100%에서 수정 ***************** */
                height="551px"  
                src="https://www.youtube.com/embed/ntRdFyGeBtU" //비디오 링크가  cms에 추가하는 것이 없음 //추가 수정됨
                alt=""
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
                ></iframe>
            <span className="conference_title_span">* 세션3 [JDC 농업전략세션] 주제발표 및 종합토론</span>

            {/* <span> {'<'}이름{'('}소속{')>'} </span> */}




        </div>



    </div>
    <div className="conference_bottom_button">
        <a href="/data/Conference.pdf" className="down_conference"
           target="_blank"
           >{language === 'en' ? "Conference Material" : "컨퍼런스 자료집"}</a>
    </div>
</section>
                )
}