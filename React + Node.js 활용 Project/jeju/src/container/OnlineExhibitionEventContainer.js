import React, { useCallback, useReducer, useState } from 'react';
import { Link } from 'react-router-dom';
import { useSelector, useDispatch } from 'react-redux';

import { secondModalOpen, modalClose } from '../store/modal';

import { makeStyles } from '@material-ui/core/styles';
import { Backdrop } from '@material-ui/core';
import { postUserEvent } from '../api/UserAPI';
import { useHistory } from 'react-router-dom';
import { Paths } from '../paths';
import { isCellPhoneForm, isEmailForm, onlyNumberListener } from '../lib/formatChecker';
/* Redux */

function reducer(state, action) {
    return {
        ...state,
        [action.name]: action.value.trim(),
    };
}

const useStyles = makeStyles((theme) => ({
    backdrop: {
        css: "",
        zIndex: 90,
    },
}));

const OnlineExhibitionEventContainer = () => {

    const language = useSelector(state => state.language.current);
    const viewId = useSelector(state => state.exhibition.current);
    const classes = useStyles();
    const history = useHistory();
    const dispatch = useDispatch();

    const secondOpen = useCallback(() => dispatch(secondModalOpen()), [dispatch]);
    const close = useCallback(() => dispatch(modalClose()), [dispatch]);

    const states = useSelector(state => state.modal);
    const { first, second, open } = states;

    const [state, action] = useReducer(reducer, {
        name: '',
        position: '', // 소속
        title: '', // 직함
        phone: '', // 휴대폰 번호
        tel: '', // 전화번호
        email: ''
    });
    const { name, position, title, phone, tel, email } = state;
    const onChange = (e) => action(e.target);

    const [agree, setAgree] = useState(false);

    const LANGUAGE_PATH = language !== '' ? `/${language}` : '';

    const inputCheck = useCallback(async (e) => {

        e.preventDefault();

        const phoneData = isCellPhoneForm(phone, false);
        const emailData = isEmailForm(email);

        if (name === '' || position === '' || title === '' || phone === '' || tel === '' || email === '' || !agree)
            alert("필수항목들을 입력해주세요");
        else if (!phoneData && !emailData)  // 둘다 틀림
            alert("휴대폰 번호와 이메일 주소를 확인해 주세요.");
        else if (!phoneData && emailData)
            alert("휴대폰 번호를 확인해 주세요.");
        else if (phoneData && !emailData)
            alert("이메일 주소를 확인해 주세요.");
        else {
            try {
                await postUserEvent({
                    name: name,
                    position: position,
                    email: email,
                    phone: phone
                });
            } catch (e) {
                alert('서버에 오류가 발생했습니다.');
            }
            dispatch(modalClose());
            history.push(LANGUAGE_PATH + Paths.exhibition + '/' + viewId);
        }

    }, [name, position, email, phone, dispatch, history, viewId, agree, tel, title, LANGUAGE_PATH]);

    const nextTime = useCallback(() => {
        dispatch(modalClose());
        history.push(LANGUAGE_PATH + Paths.exhibition + '/' + viewId);
    }, [dispatch, history, viewId, LANGUAGE_PATH]);

    const closeModal = useCallback(() => {
        dispatch(modalClose());
        history.goBack()
    }, [dispatch, history]);

    //--------------------------------------------------------------------------------------
    const LANGUAGE_PACK = {
        kr: {
            css: "",
            subject: "이벤트 참여를 위한 회원정보 입력",
            subject2: "",
            necessary: "*는 필수 항목입니다.",
            name: "이름",
            placeholder: "영문, 숫자, _ 만 입력 가능, 최소 3자 이상",
            belong: "소속",
            title: "직함",
            mobile: "휴대폰 번호",
            phone: "전화번호",
            email: "이메일 주소",
            privacy: "개인정보취급방침",
            agree: "동의합니다",
            submit: "응모하기"
        },
        en: {
            css: " language-en",
            subject: "Entering member information",
            subject2: "to participate in the event",
            necessary: "*",
            name: "Name",
            placeholder: "Only English, numbers, and_can be entered, at least 3 characters",
            belong: "Organization",
            title: "Title",
            mobile: "Mobile",
            phone: "Phone",
            email: "e-mail",
            privacy: "Privacy policy",
            agree: "I agree",
            submit: "Submit"
        },
        cn: {
            css: " language-cn",
            subject: "중국어",
            subject2: "중국어",
            necessary: "중국어",
            name: "중국어",
            placeholder: "중국어",
            belong: "중국어",
            title: "중국어",
            mobile: "중국어",
            phone: "중국어",
            email: "중국어",
            privacy: "중국어",
            agree: "중국어",
            submit: "중국어"
        },
        jp: {
            css: " language-jp",
            mention: "일본어",
            mention2: "일본어",
            mention3: "일본어",
            join: "일본어",
            cancel: "일본어",
            subject: "일본어",
            subject2: "일본어",
            necessary: "일본어",
            name: "일본어",
            placeholder: "일본어",
            belong: "일본어",
            title: "일본어",
            mobile: "일본어",
            phone: "일본어",
            email: "일본어",
            privacy: "일본어",
            agree: "일본어",
            submit: "일본어"
        }
    }

    const current_pack = LANGUAGE_PACK[language] ? LANGUAGE_PACK[language] : LANGUAGE_PACK["kr"]
    //--------------------------------------------------------------------------------------

    return (
        <>
            <div className={"modal" + current_pack.css}>
                {/* event1 */}
                {first &&
                    <div className={"eventin" + current_pack.css}>
                        <h3><strong>이벤트 참여 후</strong>전시관 둘러보기</h3>
                        <span>행사 종료 후 추첨을 통하여 경품을 지급해 드립니다.</span>
                        <p><img src={`${process.env.PUBLIC_URL}/img/img_eventin.png`} alt="" /></p>
                        <Link to="#" className={"btin" + current_pack.css} onClick={secondOpen}>참여하기</Link>
                        <Link to="#" className={"btclose" + current_pack.css} onClick={nextTime}>다음에</Link>
                    </div>
                }
                {/* event2 */}
                {second &&
                    <div className={"eventtxt" + current_pack.css}>
                        <img src={`${process.env.PUBLIC_URL}/icon/close.svg`} alt="" style={{ position: "absolute", top: "20px", right: "30px" }} onClick={closeModal} />
                        {language === "en" ? <><h3>{current_pack.subject}</h3><p /><div>{current_pack.subject2}</div></>
                            : language === "cn" ? <><h3>{current_pack.subject}</h3><p /><div>{current_pack.subject2}</div></>
                                : language === "jp" ? <><h3>{current_pack.subject}</h3><p /><div>{current_pack.subject2}</div></>
                                    : <h3 style={{ fontWeight: 'bold' }}>{current_pack.subject}</h3>}
                        <span className={"inf" + current_pack.css}>{current_pack.necessary}</span>
                        <dl className={"fir" + current_pack.css}>
                            <dt>{current_pack.name}</dt>
                            <dd>
                                <input
                                    type="text"
                                    placeholder={current_pack.placeholder}
                                    style={{ width: '100%' }}
                                    name="name"
                                    value={name}
                                    onChange={onChange}
                                />
                            </dd>
                        </dl>
                        <dl>
                            <dt>{current_pack.belong} </dt>
                            <dd><input type="text" style={{ width: '100%' }} name="position" value={position} onChange={onChange} /></dd>
                        </dl>
                        <dl>
                            <dt>{current_pack.title}</dt>
                            <dd><input type="text" style={{ width: '100%' }} name="title" value={title} onChange={onChange} /></dd>
                        </dl>
                        <dl>
                            <dt>{current_pack.mobile} </dt>
                            <dd><input type="tel" style={{ width: '100%' }} name="phone" value={phone} onChange={onChange} onKeyDown={onlyNumberListener} /></dd>
                        </dl>
                        <dl>
                            <dt>{current_pack.phone} </dt>
                            <dd><input type="tel" style={{ width: '100%' }} name="tel" value={tel} onChange={onChange} onKeyDown={onlyNumberListener} /></dd>
                        </dl>
                        <dl>
                            <dt>{current_pack.email} </dt>
                            <dd>
                                <input type="email" style={{ width: '100%' }} name="email" value={email} onChange={onChange} />
                            </dd>
                        </dl>
                        <div className={"privacy" + current_pack.css}>
                            <h4>· {current_pack.privacy}</h4>
                            <span>
                                <strong>{current_pack.privacy}</strong>

                                    ㈜이노윙(이하 “회사”)는 정보통신망 이용촉진 및 정보보호에 관한 법률, 개인정보보호법에 따라
                                    모든 고객님의 개인정보보호 및 권익을 보호하기 위하여 수집, 보유된 정보를 적법하고 적정하게 취급할 것입니다.

                                    "회사"는 개인정보와 관련한 고객님의 고충을 원활하게 처리할 수 있도록 관련 법령에 의거한
                                    개인정보 취급방침을 정하여 "회사"의 서비스를 이용하는 고객님의 권익보호에 최선을 다하겠습니다.
                                    본 개인정보처리방침은 ㈜이노윙이 제공하는 서비스 이용에 적용되며 다음과 같은 내용을 담고 있습니다.

                                    제12조 (서비스의 이용 신청에 대한 승낙과 거절)
                                </span>
                            <em>
                                <strong>*</strong>{current_pack.agree}
                                <input type="checkbox" id="p1" name="" className={"leftch" + current_pack.css} onClick={() => setAgree(!agree)} />
                                <label htmlFor="p1"><span></span> </label>
                            </em>
                        </div>
                        <Link to="#" className={"btin" + current_pack.css} onClick={inputCheck}>{current_pack.submit} </Link>
                    </div>
                }
            </div>
            <Backdrop
                className={classes.backdrop + current_pack.css}
                open={open}
                onClick={close}
            />
        </>
    )
}

export default OnlineExhibitionEventContainer;