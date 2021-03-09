import React, {
    forwardRef,
    useImperativeHandle,
    useCallback,
    useEffect,
    useRef,
    useState,
} from 'react';
import { useHistory } from 'react-router-dom';
/* Library */

import { requestPostAuth } from '../../api/user';

import useInput from '../../hooks/useInput';
import useBirth from '../../hooks/useBirth';
import { useDialog } from '../../hooks/useDialog';
import useLoading from '../../hooks/useLoading';

import InputBox from '../../components/inputbox/InputBox';
import Birth from '../../components/birth/Birth';
import CheckBox from '../../components/checkbox/CheckBox';
import VerifyPhone from '../../components/verifyphone/VerifyPhone';
import FixedButton from '../../components/button/FixedButton';

import { isEmailForm, isPasswordForm } from '../../lib/formatChecker';

import { Paths } from '../../paths';

import classNames from 'classnames/bind';
import styles from './SignUpContainer.module.scss';

const cx = classNames.bind(styles);

const Email = forwardRef(({ setCheck, onKeyDown }, ref) => {
    const [email, onChangeEmail, checkEmail] = useInput('', isEmailForm);
    const emailRef = useRef(null);
    useImperativeHandle(ref, () => ({
        email,
        focusing: () => {
            onChangeEmail('');
            emailRef.current.focus();
        },
    }));
    useEffect(() => setCheck(checkEmail), [setCheck, checkEmail]);
    return (
        <div className={cx('input-wrapper')}>
            <div className={cx('input-title')}>이메일</div>
            <InputBox
                className={'input-bar'}
                type={'email'}
                value={email}
                placeholder={'이메일을 입력해주세요.'}
                onChange={onChangeEmail}
                onKeyDown={onKeyDown}
                reference={emailRef}
            />
        </div>
    );
});

const Name = forwardRef(({ setCheck, onKeyDown }, ref) => {
    const [name, onChangeName] = useInput('');
    useEffect(() => setCheck(name !== '' ? true : false), [setCheck, name]);
    useImperativeHandle(ref, () => ({
        name,
    }));
    return (
        <div className={cx('input-wrapper')}>
            <div className={cx('input-title')}>이름</div>
            <InputBox
                className={'input-bar'}
                type={'text'}
                value={name}
                placeholder={'이름을 입력해주세요.'}
                onChange={onChangeName}
                onKeyDown={onKeyDown}
            />
        </div>
    );
});

const SAME_TEXT = '비밀번호가 일치합니다.';
const DIFF_TEXT = '비밀번호가 일치하지 않습니다.';

const Password = forwardRef(({ setCheck, onKeyDown }, ref) => {
    const [password, onChangePassword, checkPasswordForm] = useInput(
        '',
        isPasswordForm,
    );
    const [passwordCheck, onChangePasswordCheck] = useInput('');
    const [apear, setApear] = useState(false);
    const [same, setSame] = useState(false);
    const pwRef = useRef(null);

    useImperativeHandle(ref, () => ({
        password,
        focusing: () => {
            onChangePassword('');
            pwRef.current.focus();
        },
    }));

    useEffect(() => {
        setCheck(checkPasswordForm && password === passwordCheck);
        setApear(password !== '' && passwordCheck !== '');
        setSame(password === passwordCheck);
    }, [setCheck, password, passwordCheck, checkPasswordForm]);

    return (
        <div className={cx('input-wrapper')}>
            <div className={cx('input-title')}>비밀번호</div>
            <InputBox
                className={'input-bar'}
                type={'password'}
                value={password}
                placeholder={'비밀번호를 입력해주세요.'}
                onChange={onChangePassword}
                onKeyDown={onKeyDown}
                reference={pwRef}
            />
            <InputBox
                className={'input-bar'}
                type={'password'}
                value={passwordCheck}
                placeholder={'비밀번호를 재입력해주세요.'}
                onChange={onChangePasswordCheck}
                onKeyDown={onKeyDown}
            />
            <p className={cx('password-check', { apear, same })}>
                {apear && (same ? SAME_TEXT : DIFF_TEXT)}
            </p>
        </div>
    );
});

const BirthSelector = ({ onChangeBirth }) => {
    return (
        <div className={cx('input-wrapper')}>
            <div className={cx('input-title')}>생년월일</div>
            <div className={cx('select-wrapper')}>
                <Birth onChangeBirth={onChangeBirth} />
            </div>
        </div>
    );
};

const CheckList = ({ setCheck, url, modal }) => {
    const [checkList, setCheckList] = useState([
        {
            id: 1,
            checked: false,
            description: '이용약관 필수 동의',
            policy: 0,
        },
        {
            id: 2,
            checked: false,
            description: '개인정보 처리방침 필수 동의',
            policy: 1,
        },
        {
            id: 3,
            checked: false,
            description: '쿠폰 / 이벤트 알림 선택 동의',
            subDescription:
                'SMS, 이메일을 통해 파격할인/이벤트/쿠폰 정보를 받아보실 수 있습니다.',
            policy: -1,
        },
    ]);
    useEffect(() => setCheck(checkList[0].checked && checkList[1].checked), [
        setCheck,
        checkList,
    ]);

    return (
        <div className={cx('check-box-wrapper')}>
            <CheckBox
                allCheckTitle={'모두 동의합니다.'}
                checkListProps={checkList}
                box={true}
                setterFunc={setCheckList}
                url={url}
                modal={modal}
            />
        </div>
    );
};

const SignUpContainer = ({ match }) => {
    const history = useHistory();
    const [signUp, setSignUp] = useState(false);
    const [checkEmail, setCheckEmail] = useState(false);
    const [checkName, setCheckName] = useState(false);
    const [checkPassword, setCheckPassword] = useState(false);
    const [checkPhone, setCheckPhone] = useState(false);
    const [checkAgree, setCheckAree] = useState(false);
    const [onChangeBirth, getBirth] = useBirth({
        year: '1970',
        month: '1',
        day: '1',
    });

    const emailRef = useRef(null);
    const nameRef = useRef(null);
    const passwordRef = useRef(null);
    const phoneRef = useRef(null);

    const openDialog = useDialog();
    const [onLoading, offLoading] = useLoading();

    const onClickSignUp = useCallback(async () => {
        if (!signUp) {
            return;
        }
        onLoading('signUp');
        try {
            const { data } = await requestPostAuth(
                emailRef.current.email,
                nameRef.current.name,
                passwordRef.current.password,
                getBirth(),
                phoneRef.current.phoneNumber,
            );

            if (data.msg === 'success') {
                sessionStorage.setItem('session_token', data.token);
                sessionStorage.setItem('session_name', nameRef.current.name);
                history.push(Paths.auth.enrollment);
            } else {
                openDialog(data.msg);

                if (data.msg === '이미 가입한 이메일입니다.')
                    emailRef.current.focusing();
                else if (data.msg === '비밀번호를 설정하지 못했습니다.')
                    passwordRef.current.focusing();
            }
        } catch (e) {
            console.error(e);
        }
        offLoading('signUp');
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [history, signUp, getBirth, openDialog]);

    const onKeyDownSignUp = useCallback(
        async (e) => {
            if (e.key === 'Enter') onClickSignUp();
        },
        [onClickSignUp],
    );

    useEffect(
        () =>
            setSignUp(
                checkEmail &&
                checkName &&
                checkPassword &&
                checkPhone &&
                checkAgree,
            ),
        [checkEmail, checkName, checkPassword, checkPhone, checkAgree],
    );

    return (
        <>
            <div className={cx('container')}>
                <Email
                    setCheck={setCheckEmail}
                    onKeyDown={onKeyDownSignUp}
                    ref={emailRef}
                ></Email>
                <Name
                    setCheck={setCheckName}
                    onKeyDown={onKeyDownSignUp}
                    ref={nameRef}
                ></Name>
                <Password
                    setCheck={setCheckPassword}
                    onKeyDown={onKeyDownSignUp}
                    ref={passwordRef}
                ></Password>
                <BirthSelector onChangeBirth={onChangeBirth}></BirthSelector>
                <div className={cx('input-title')}>휴대폰 번호 인증</div>
                <VerifyPhone setCheck={setCheckPhone} ref={phoneRef} />
                <CheckList setCheck={setCheckAree} url={Paths.auth.signup} modal={match.params.modal}></CheckList>
            </div>
            <FixedButton
                button_name={'회원가입하기'}
                disable={!signUp}
                onClick={onClickSignUp}
            />
        </>
    );
};

export default SignUpContainer;
