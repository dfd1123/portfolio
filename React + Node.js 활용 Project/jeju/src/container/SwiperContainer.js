import React from 'react';
import { useSelector } from 'react-redux';

// import Swiper core and required components
import SwiperCore, { Navigation, Pagination, Scrollbar, A11y } from 'swiper';

import { Swiper, SwiperSlide } from 'swiper/react';

import '../static/stylesheets/Swiper.scss';
// Import Swiper styles
import 'swiper/swiper.scss';
import 'swiper/components/navigation/navigation.scss';
import 'swiper/components/pagination/pagination.scss';
import 'swiper/components/scrollbar/scrollbar.scss';

// install Swiper components
SwiperCore.use([Navigation, Pagination, Scrollbar, A11y]);

export default ({ dataSet, firstOpen }) => {
    const URL = 'http://14.63.174.102:84';

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
if(dataSet[0].id != 114){
        const push_obj = { 
            "id": 114, 
            "module_id": 1, 
            "title": "(강원)정선아라리한과농원<br>영농조합법인", 
            "contents": "사단법인 제주관광문화산업진흥원_자연본색", 
            "photo_1": "/data/uploaded/documents-photo_1-116.png?v=1604392471", 
            "photo_1_thumb": "/data/uploaded/documents-photo_1-116-thumb.png?v=1604392471", 
            "photo_2": "/data/uploaded/documents-photo_2-114.png?v=1604576068", 
            "photo_2_thumb": "/data/uploaded/documents-photo_2-114-thumb.png?v=1604576068", 
            "photo_3": "/data/uploaded/documents-photo_3-116.png?v=1604481147", 
            "photo_3_thumb": "/data/uploaded/documents-photo_3-116-thumb.png?v=1604481147", 
            "photo_4": "/data/uploaded/documents-photo_4-116.png?v=1604481491", 
            "photo_4_thumb": "/data/uploaded/documents-photo_4-116-thumb.png?v=1604481491", 
            "photo_5": "", 
            "photo_5_thumb": "", 
            "created_at": "2020-11-02 14:58:04", 
            "updated_at": "2020-11-06 06:24:41", 
            "video_1": "", 
            "photo_1_width": "533", 
            "photo_2_width": "300", 
            "photo_3_width": "400", 
            "photo_4_width": "400", 
            "photo_5_width": "", 
            "photo_1_height": "94", 
            "photo_2_height": "201", 
            "type": 6,
            "title_en": "Jeongseon Arari Hangwa<br>Farming Association"
        }
        dataSet.splice(0, 0, push_obj)
    }
    
    function ChangeBrToBr(props) {
        console.log(props.name_ko,props.name_en)
        let title = (language === 'en' ? props.name_en : props.name_ko)
        console.log(title.split('<br>').length,title.split('<br>'))
        if(title.split('<br>').length == 1){
            console.log('return title')
            return title
        }else{
            var return_title = ''
            console.log('return title to br')
            return_title = title.split('<br>').map( line => { //국영문 구분법
                return (<span>{line}<br/></span>)
            })
            return return_title
        }
    }
	
    return (
        <>
            {dataSet === 'Error' ? (
                <Swiper>
                    <SwiperSlide>
                        <img
                            className={"error" + current_pack.css}
                            src={`${process.env.PUBLIC_URL}/img/ic_check_on.png`}
                            alt=""
                        />
                    </SwiperSlide>
                </Swiper>
            ) : (
                    <Swiper
                        slidesPerView={5} // 보이는 슬라이드 수
                        slidesPerGroup={1} // 슬라이드 할때 몇개를 슬라이드 할것이냐
                        loop={dataSet.length > 5}
                        initalslide={0}
                        loopFillGroupWithBlank
                        navigation
                        // watchOverflow
                        loopPreventsSlide // 활성화되면 전환이 이미 진행 중일 때 스 와이퍼 슬라이드 이전 / 다음 전환을 방지
                        delay={300}
                    >
                        {dataSet.map((data) => (
                            <SwiperSlide key={data.id}>

        <div  className="ex_li">
                                    <em>
                                        <ChangeBrToBr name_ko={data.title}  name_en={data.title_en} />
                                    </em>
                                    <img src={URL + data.photo_2} alt="no_image" onClick={() => firstOpen(data.id)} />
                                </div>
                            </SwiperSlide>
                        ))}
                    </Swiper>
                )}
        </>
    );
};
