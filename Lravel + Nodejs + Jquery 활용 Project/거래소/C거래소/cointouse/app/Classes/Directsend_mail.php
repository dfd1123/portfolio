<?php

namespace App\Classes;

class Directsend_mail {
	public function send($m_recipients, $m_subject, $m_body, $m_directsend_username, $m_directsend_key, $m_sendfrom, $m_sendfrom_name)
	{
        $ch = curl_init();

        info(curl_error($ch));

        /* 
        * subject  : 받을 mail 제목.
        * body  : 받을 mail 본문.
        * sender : 발송자 메일주소
        * sender_name : 발송자 이름
        * username : directsend 발급 ID
        * recipients : 발송 할 고객 이메일 , 로 구분함. ex) aaa@naver.com,bbb@nate.com
        * key : directsend 발급 api key
        * 
        * 각 내용이 유효하지않을 경우에는 발송이 되지 않습니다.
        * 상업성 광고 메일이나 업체 홍보 메일을 발송하는 경우, 제목에 (광고) 문구를 표기해야 합니다.
        * 영리광고 발송 시, 명시적인 사전 동의를 받은 이에게만 광고 메일 발송이 가능합니다.
        * 수신동의 여부에 대한 분쟁이 발생하는 경우 이에 대한 입증책임은 광고성 정보 전송자에게 있습니다.
        * 수신자가 수신거부 또는 수신동의 철회 의사를 쉽게 표시할 수 있는 안내문을 명시해야 합니다.
        * 스팸 메일 발송 용도로 악용하실 경우 이용에 제한이 있을 수 있으니 이용 시 주의 부탁 드립니다.
        * 불법 스팸 메일 발송 시 예고없이 서비스 이용이 정지될 수 있으며 이용정지 시 해당 아이디의 주소록과 잔액은 소멸되며, 환불되지 않으니 서비스 이용에 주의를 부탁드립니다.
        *
        * API 연동 발송시 다량의 주소를 한번에 입력하여도 수신자에게는 1:1로 보내는 것으로 표기되며, 동일한 내용의 메일을 한건씩 발송하는 것보다 다량으로 한번에 보내는 것이 발송 효율이 더 높습니다.
        * 동일한 내용의 메일을 다수에게 발송하시는 경우 수신자 주소를[ , ]로 구분하시어 한번에 발송하시는 것을 권장 드립니다.
        */ 

        /* 여기서부터 수정해주시기 바랍니다. */	
        $subject = $m_subject;                           //필수입력
        $body = $m_body;                                 //필수입력
        $sender = $m_sendfrom;                           //필수입력
        $sender_name = $m_sendfrom_name;
        $username = $m_directsend_username;              //필수입력
        $recipients = $m_recipients;                     //필수입력
        $key = $m_directsend_key;                        //필수입력

        $bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

        // 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
        $return_url = 0;
        //open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
        //등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
        $option_return_url = 0;

        $open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
        $click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
        $check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

        // 예약발송 파라미터 추가
        $mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
        $start_reserve_time = date('Y-m-d H:i:00'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
        $end_reserve_time = date('Y-m-d H:i:00'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
        // WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
        $remained_count = 1;

        //필수안내문구 추가
        $agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
        $deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
        $sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
        $logo_state = 1; // logo 사용시 1 / 사용안할 시 0
        $logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

        // 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
        $file_url = 'http://domain/test.png|http://domain/test1.png';
        // 첨부파일의 이름을 지정할 수 있도록 합니다.
        // 첨부파일의 이름은 순차적(http://domain/test.png - image.png, http://domain/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름이로 메일에 보여집니다.
        $file_name = 'image.png|image2.png';

        /* 여기까지 수정해주시기 바랍니다. */

        $postvars = "subject=" . urlencode(base64_encode($subject))
        . "&body=" . urlencode(base64_encode($body))
        . "&sender=" . urlencode($sender)
        . "&sender_name=" . urlencode(base64_encode($sender_name))
        . "&username=" . urlencode($username)
        . "&recipients=" . urlencode($recipients)

        // 예약 관련 파라미터 사용할 경우 주석 해제
        //  . "&mail_type=" . urlencode($mail_type)
        //  . "&start_reserve_time=" . urlencode($start_reserve_time)
        //  . "&end_reserve_time=" . urlencode($end_reserve_time)
        //  . "&remained_count=" . urlencode($remained_count)

        // 필수 안내문구를 추가할 경우 주석 해제
        //	. "&agreement_text=" . urlencode(base64_encode($agreement_text))
        //	. "&deny_text=" . urlencode(base64_encode($deny_text))
        //	. "&sender_info_text=" . urlencode(base64_encode($sender_info_text))
        //	. "&logo_state=" . urlencode($logo_state)
        //	. "&logo_path=" . urlencode($logo_path)

        // 메일 발송결과를 받고 싶은 URL 
        //	. "&return_url=" . urlencode($return_url) // return_url이 있는 경우 주석해제 바랍니다.

        // 발송결과 옵션 측정 항목을 사용할 경우 주석 해제
        //	. "&option_return_url=" . urlencode($option_return_url)
        //	. "&open=" . urlencode($open)
        //	. "&click=" . urlencode($click)
        //	. "&check_period=" . urlencode($check_period)

        // 첨부파일이 있는 경우 주석 해제
        //	. "&file_url=" . urlencode($file_url)
        //	. "&file_name=" . urlencode($file_name)

        // 메일 내용 텍스트로 보내실 경우 주석 해제
        //  . "&bodytag=" . urlencode($bodytag)
        . "&key=" . urlencode($key);


        // return_url이 없는 경우 사용하는 URL
        $url = "https://directsend.co.kr/index.php/api/v2";

        // return_url이 있을 경우 사용하는 URL
        //$url = "https://directsend.co.kr/index.php/api/result_v2";

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt($ch,CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);

        /* 
        * response의 실패
        *  {"status":101} 
        */

        /* 
        * response 성공
        * {"status":0}
        * 성공 코드번호.
        */

        /* 
            status code
            0   : 정상발송
            100 : POST validation 실패
            101 : 회원정보가 일치하지 않음
            102 : subject, message 정보가 없습니다.
            103 : sender 이메일이 유효하지 않습니다.
            104 : recipient 이메일이 유효하지 않습니다.
            105 : 본문에 포함하면 안되는 확장자가 있습니다.
            106 : body validation 실패
            107 : 받는 사람이 없습니다.
            108 : 예약정보가 유효하지 않습니다.
            109 : return_url이 유효하지 않습니다.
            110 : 첨부파일이 없습니다.
            111 : 첨부파일의 개수가 5개를 초과합니다.
            112 : 첨부파일의 Size가 10MB를 초과합니다.
            113 : 첨부파일이 다운로드 되지 않았습니다.
            200 : 동일 예약시간으로는 200건 이상 발송할 수 없습니다.
            205 : 잔액부족
            999 : Internal Error.
        */ 

        curl_close ($ch);
        
        return $response;
	}
}
