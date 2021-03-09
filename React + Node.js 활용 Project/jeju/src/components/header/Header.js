import React, { useCallback, useState } from 'react';
import HeaderLogo from './HeaderLogo';
import HeaderNav from './HeaderNav';

import { Paths } from '../../paths/index'
import { useHistory, useLocation } from 'react-router-dom';

const LANGUAGE_URL_LIST = ['/kr', '/en', '/cn', '/jp'];

const Header = () => {
    const location = useLocation();
    const history = useHistory();


    const selectLanguage = useCallback(e => {
        // 언어 변경
        const pathbase = LANGUAGE_URL_LIST.reduce((prev, cur) => {
            return prev.replace(cur, '');
        }, location.pathname);
        history.push(`/${e.target.value}` + pathbase + location.search);
    }, [location, history]);

    const [navList, setNavList] = useState([
        {
            id: 1,
            kr: "오프닝세션",
            en: "Opening-Session",
            cn: "중국어",
            jp: "일본어",
            path: Paths.session,
            checked: false
        },
        {
            id: 2,
            kr: "컨퍼런스",
            en: "Conference",
            cn: "중국어",
            jp: "일본어",
            path: Paths.conference,
            checked: false
        },
        {
            id: 3,
            kr: "온라인전시관",
            en: "Online-Exhibition",
            cn: "중국어",
            jp: "일본어",
            path: Paths.exhibition,
            checked: false
        },
        {
            id: 4,
            kr: "공지 및 이벤트",
            en: "Notice",
            cn: "중국어",
            jp: "일본어",
            path: Paths.notice,
            checked: false
        },
        {
            id: 5,
            kr: "SNS",
            en: "SNS",
            cn: "중국어",
            jp: "일본어",
            path: Paths.sns,
            checked: false
        }
    ])

    const selected = useCallback(id => {
        setNavList(
            navList.map(item =>
                item.id === id ? { ...item, checked: true } : { ...item, checked: false }
            )
        )
    }, [navList])

    const setDefault = () => setNavList(navList.map(item => ({ ...item, checked: false })))

    return (
        <header>
            <HeaderLogo selectLanguage={selectLanguage} setDefault={setDefault} />
            <HeaderNav navList={navList} selected={selected} />
        </header>
    );
};

export default Header;