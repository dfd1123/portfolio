import React, { useCallback, useEffect, useState } from 'react';
import { useSelector } from 'react-redux';

import Loading from '../components/assets/Loading'

import { getShowDocument } from '../api/OnlineExhibitionAPI'
import { useHistory } from 'react-router-dom';

const OnlineExhibitionContainer = ({ viewId }) => {

    const URL = "http://14.63.174.102:84";
    const history = useHistory()

    const [booth, setBooth] = useState({});

    const language = useSelector(state => state.language.current);

    const [loading, setLoading] = useState(false);

    const showingDocument = useCallback(async () => {
        setLoading(true);
        try {
            const res = await getShowDocument(viewId);
            setBooth(res);
        } catch (e) {
            alert('찾으시는 부스가 존재하지 않습니다.')
            history.goBack()
        }
        setLoading(false);
    }, [viewId, history])

    useEffect(() => {
        try {
            showingDocument();
        } catch (e) {
            alert('서버에 오류가 발생했습니다.');
        }
    }, [showingDocument]);

    const type = [];
    if (booth.type === 0) {
        type.push('온라인 전시')
        type.push('Online-Exhibition')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 1) {
        type.push('음료,차류')
        type.push('Beverages/Tea')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 2) {
        type.push('전통식품')
        type.push('Traditional Foods')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 3) {
        type.push('가공식품')
        type.push('Processed Foods')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 4) {
        type.push('건강식품')
        type.push('Healthy Foods & supplements')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 5) {
        type.push('주류')
        type.push('Alcoholic drinks')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 6) {
        type.push('간식')
        type.push('Snacks')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 7) {
        type.push('화장품')
        type.push('Cosmetics')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 8) {
        type.push('천연염색')
        type.push('Dyed products')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 9) {
        type.push('마을공동체')
        type.push('Local community')
        type.push('중국어')
        type.push('일본어')
    }
    else if (booth.type === 10) {
        type.push('유제품')
        type.push('Dairy products')
        type.push('중국어')
        type.push('일본어')
    }

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            download: "카탈로그 보기"
        },
        en: {
            css: " language-en",
            download: "View Catalog"
        },
        cn: {
            css: " language-cn",
            download: ""
        },
        jp: {
            css: " language-jp",
            download: ""
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    const videoType = (link) => {
        const LINK = String(link)
        if (LINK.lastIndexOf("embed") !== -1) {    //유튜브 embed링크로 넘어오는 경우
            return <iframe
                title="youtube"
                width="660"
                height="376"
                src={link} //비디오 링크가  cms에 추가하는 것이 없음
                alt=""
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
            />
        }
        else if (LINK.length !== 0) {  //유튜브 링크로 넘어오는 경우
            const lastSlash = LINK.lastIndexOf('/')
            const videoID = LINK.slice(lastSlash, LINK.length)
            return <iframe
                title="youtube"
                width="660"
                height="376"
                src={"https://www.youtube.com/embed" + videoID}
                alt=""
                frameBorder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowFullScreen
            />
        }
        else {  //파일, 링크 두 가지 다 없는 경우 => 
            return <img src={`${process.env.PUBLIC_URL}/img/ic_check_on.png`} alt="" width="660" height="376" />    //에러 이미지
        }
    }

    return (
        <section id="ex_container" className={current_pack.css}>
            {!loading &&
                <>
                    <h2>{language === 'en' ? type[1]
                        : language === 'cn' ? type[2]
                            : language === 'jp' ? type[3]
                                : type[0]}ㅣ{language === 'en' ? booth.contents_en
                                    : booth.contents}</h2>
                    <div className={"people" + current_pack.css}>
                        <span>
                            <img src={`${process.env.PUBLIC_URL}/img/img_peo_left.png`} alt="" />
                        </span>
                        <span>
                            <img src={`${process.env.PUBLIC_URL}/img/img_peo_right.png`} alt="" />
                        </span>
                    </div>
                    <div className={"left" + current_pack.css}>
                        {booth.photo_4 && <img src={URL + booth.photo_4} alt="" />}

                    </div>
                    <div className={"right" + current_pack.css}>
                        {booth.photo_4 && <img src={URL + booth.photo_3} alt="" />}
                    </div>
                    <div className={"spot" + current_pack.css}>
                        <i
                            style={{ position: "absolute", bottom: "250px", width: "100%", margin: "0 auto", textAlign: "center", left: "0px", zIndex: "30" }}>
                            {booth.photo_1 && <img
                                style={{ display: "block", maxWidth: "100%", maxHeight: "160px", margin: "0 auto", textAlign: "center" }}
                                src={URL + booth.photo_1}
                                alt=""
                            />}
                        </i>
                        <span><img src={`${process.env.PUBLIC_URL}/img/img_center_booth.png`} alt="" /></span>
                        <div className={"center" + current_pack.css}>
                            {videoType(booth.youtube_link)}
                        </div>
                        <div className={"button-area"}>
                            <button className={"buy" + current_pack.css} onClick={booth.link ?
                                (booth.link.indexOf("http") !== -1) ?
                                () => window.open(booth.link, '_blank') : () => window.open("http://" + booth.link, '_blank')
                                : () => {}}
                            
                            >
                                
                                {language === 'en' ? "Purchase"
                                    : language === 'cn' ? "중국어"
                                        : language === 'jp' ? "일본어"
                                            : "구매하러 가기"} {'>'}
                            </button>
                            <button className={"buy" + current_pack.css} onClick={() => window.open(URL + booth.file_1, '_blank')}>{current_pack.download} {'>'}</button>
                        </div>
                    </div>
                </>
            }
            <Loading open={loading} />
        </section >
    );
};

export default OnlineExhibitionContainer;