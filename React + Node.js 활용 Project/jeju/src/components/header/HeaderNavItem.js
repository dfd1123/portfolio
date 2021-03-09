import React from 'react';
import { useSelector } from 'react-redux';
import { Link } from 'react-router-dom';

export default ({ onClick, item }) => {
    const selectEffect = item.checked ? 'on' : '';
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
    
    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';
    return (
        <li>
            <Link to={LANGUAGE_PATH + item.path} className={selectEffect + current_pack.css} onClick={() => onClick(item.id)}>
                {language === 'en'
                    ? item.en
                    : language === 'cn'
                    ? item.cn
                    : language === 'jp'
                    ? item.jp
                    : item.kr}
            </Link>
        </li>
    );
};
