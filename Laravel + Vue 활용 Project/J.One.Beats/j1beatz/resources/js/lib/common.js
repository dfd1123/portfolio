/**
 * common.js
 * 공통 함수입니다.
 * [WARNING] 아무 함수나 넣지 말 것
 */

function padPrefix(index, number, string) {
    return String(index).padStart(number, string);
}

function prefixNum(num, digits) {
    num = Number(num);
    var units = ["k", "M", "G", "T", "P", "E", "Z", "Y"],
        decimal;

    for (var i = units.length - 1; i >= 0; i--) {
        decimal = Math.pow(1000, i + 1);

        if (num <= -decimal || num >= decimal) {
            return +(num / decimal).toFixed(digits) + units[i];
        }
    }

    return num;
}

function checkPassword(pw) {
    // 6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상 조합
    const num = pw.search(/[0-9]/g);
    const eng = pw.search(/[a-z]/g);
    const ung = pw.search(/[A-Z]/g);
    const spe = pw.search(/[`~!@@#$%^&*|₩₩₩'₩";:₩/?]/gi);

    if (pw.length < 6 || pw.length > 20) {
        // "6자리 ~ 20자리 사이로 입력해주세요"
        return -1;
    }

    if (pw.search(/\s/) != -1) {
        // "비밀번호는 공백없이 입력해주세요"
        return -2;
    }

    if (
        [num, eng, ung, spe].filter(check => {
            return check < 0;
        }).length > 2
    ) {
        // "6~20자 영문 대소문자, 숫자, 특수문자 중 2가지 이상을 조합하여 입력해주세요"
        return -3;
    }

    return 1;
}

export { padPrefix, prefixNum, checkPassword };
