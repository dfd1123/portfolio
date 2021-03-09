import React from 'react'

import HeaderNavItem from './HeaderNavItem'
import { useSelector } from 'react-redux'

export default ({ Language, navList, selected }) => {
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

    return (
        <div id="lnb" className={current_pack.css}>
            <span>
                <a href="https://www.youtube.com/channel/UCrVe4zDkzenuAjrH-6ukEjw" target="_blank" rel="noopener noreferrer" ><img src={`${process.env.PUBLIC_URL}/img/ic_youtube.png`} alt="" /></a>
                <a href="https://www.facebook.com/6%EC%B0%A8%EC%82%B0%EC%97%85%EC%A0%9C%EC%A3%BC%EA%B5%AD%EC%A0%9C%EB%B0%95%EB%9E%8C%ED%9A%8C-112819440075222" target="_blank" rel="noopener noreferrer" ><img src={`${process.env.PUBLIC_URL}/img/ic_facebook.png`} alt="" /></a>
                <a href="https://www.instagram.com/farmingplus_jeju/" target="_blank" rel="noopener noreferrer" ><img src={`${process.env.PUBLIC_URL}/img/ic_instagram.png`} alt="" /></a>
            </span>
            <ul>
                {navList.map(item => (
                    <HeaderNavItem key={item.id} onClick={selected} item={item} Language={Language} />
                ))}
            </ul>
        </div>
    )
}