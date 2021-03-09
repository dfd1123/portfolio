import React from 'react';
import { useSelector } from 'react-redux';
import '../static/stylesheets/Error.css';

const ErrorPage = () => {
    const language = useSelector(state => state.language.current);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            title: "에러 페이지",
            content: "페이지를 찾을 수 없습니다."
        },
        en: {
            css: " language-en",
            title: "Error Page",
            content: "Page Not Found."
        },
        cn: {
            css: " language-cn",
            title: "중국어",
            content: "중국어"
        },
        jp: {
            css: " language-jp",
            title: "일본어",
            content: "일본어"
        }
    }
    
    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    return (
        <div className={"ERROR" + current_pack.css}>
            <h1 className={"title" + current_pack.css}>{current_pack.title}</h1>
            <p className={"warning" + current_pack.css}>{current_pack.content}</p>
        </div>
    );
};

export default ErrorPage;