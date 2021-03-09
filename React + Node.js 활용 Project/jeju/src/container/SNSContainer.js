import React from 'react'
import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom'

import { Paths } from '../paths/index'

export default () => {
    const language = useSelector(state => state.language.current);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            title: "SNS",
            youtube: "유튜브 바로가기",
            facebook: "페이스북 바로가기",
            instagram: "인스타그램 바로가기"
        },
        en: {
            css: " language-en",
            title: "SNS",
            youtube: "You Tube Link",
            facebook: "Facebook Link",
            instagram: "Instagram Link"
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            youtube: "중국어",
            facebook: "중국어",
            instagram: "중국어"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            youtube: "일본어",
            facebook: "일본어",
            instagram: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';
    return (
        <section id="main_container" className={current_pack.css}>
            <div className={"tab" + current_pack.css}>
                <ul>
                    <li><Link to={LANGUAGE_PATH + Paths.sns} className={"on" + current_pack.css}>{current_pack.title}</Link></li>
                </ul>
            </div>
            <div className={"snsbox" + current_pack.css}>
                <ul>
                    <li><a rel="noopener noreferrer" href="https://www.youtube.com/channel/UCrVe4zDkzenuAjrH-6ukEjw" target="_blank"><i><img src={`${process.env.PUBLIC_URL}/img/img_youtube.png`} alt="youtube" /></i>{current_pack.youtube}</a></li>
                    <li><a rel="noopener noreferrer" href="https://www.facebook.com/6%EC%B0%A8%EC%82%B0%EC%97%85%EC%A0%9C%EC%A3%BC%EA%B5%AD%EC%A0%9C%EB%B0%95%EB%9E%8C%ED%9A%8C-112819440075222" target="_blank"><i><img src={`${process.env.PUBLIC_URL}/img/img_facebook.png`} alt="facebook" /></i>{current_pack.facebook}</a></li>
                    <li><a rel="noopener noreferrer" href="https://www.instagram.com/farmingplus_jeju/" target="_blank"><i><img src={`${process.env.PUBLIC_URL}/img/img_instagram.png`} alt="instagram" /></i>{current_pack.instagram}</a></li>
                </ul>
            </div>
        </section>
    );
};