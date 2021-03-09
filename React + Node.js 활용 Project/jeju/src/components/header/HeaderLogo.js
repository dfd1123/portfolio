import React from 'react';
import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom';


export default ({ selectLanguage, setDefault }) => {
    const language = useSelector(state => state.language.current);
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
setTimeout(function(){
        var this_href = window.location.href.split('/').pop();
        if(this_href == 'Online-Exhibition'){
            document.getElementsByClassName('header_files')[0].style.display = 'block'
        }else{
            document.getElementsByClassName('header_files')[0].style.display = ''
        }
    },1)
    
    return (
        <>
            <h1>
                <Link to="/" onClick={setDefault} >
                    <img src={`${process.env.PUBLIC_URL}/img/h1_logo.png`} alt="" />
                    <img src={`${process.env.PUBLIC_URL}/img/h1_logo_txt.png`} alt="" />
                </Link>
            </h1>
            <div className={"select" + current_pack.css}>
                <select onChange={selectLanguage} value={language} className={"select-option" + current_pack.css}>
                    <option value="" disabled  >Language</option>
                    <option value="kr">한국어</option>
                    <option value="en">English</option>
                    {/* <option value="cn">china</option>
                    <option value="jp">japan</option> */}
                </select>
                <div className={"select__arrow" + current_pack.css}></div>
                
                <div class="clearfix"></div>
                <div className="header_files">
                <a href="/data/map.pdf" target="_blank">싹쓰리 체험지도</a>
                <a href="/data/company.pdf" target="_blank">기업 카탈로그</a>
                </div>
            </div>
        </>
    );
};
