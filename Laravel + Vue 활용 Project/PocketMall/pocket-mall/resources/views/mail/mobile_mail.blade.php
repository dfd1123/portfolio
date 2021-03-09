
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>포켓몰</title>
  
  <!-- default css -->
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/vendors/typography.css">
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/vendors/normalize-custom.css">
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/vendors/kronos.css">
  <!-- END default css -->

  <!-- custom css -->
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/custom/layout.css">
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/custom/sub.css">
  <link rel="stylesheet" href="http://pocketmall-vue.local/mail/css/custom/repon.css">
  <!-- END custom css -->

  

</head>

<body>

<style type="text/css">
  /* 견적서 페이지 */
.invoice-wrapper {
  padding-bottom: 60px;
}

.invoice-container {
  width: 100%;
  min-height: 100vh;
  padding-top: 80px; /* 헤더높이 띄우기 */
}

.invoice-container .container-bg {
  background: linear-gradient(125deg, #1dbbff, #1d6aff);
}
.invoice-container .container-bg-bottom{
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  background: linear-gradient(167deg, rgba(0,0,0,0) 50%,rgba(0,83,203,0.3) 50%);
  z-index: -1;
}

.invoice-wrap{
  max-width: 880px;
  margin: 0px auto;
  background-color: #fff;
  box-shadow: 12px 31px 34px rgba(0,0,0,0.1);
}
.invoice-wrap .invoice_title{
  padding: 24px 50px;
  background-color: #20345A;
  color: white;
}

/* 남색배경영역 */
.invoice_title .icon_box{
  float:left;
  width: 69px;
  height:69px;
  border-radius: 50%;
  background-color:#fff;
  background-image:url('../../images/icon/category-symbol.svg');
  background-repeat: no-repeat;
  background-size: 43px;
  background-position: center center;
}
.invoice_title ._title{
  float:left;
  height: 69px;
  line-height: 69px;
  margin: 0px 16px;
  font-size: 2.5em;
  font-weight: 600;
  letter-spacing: 8px;
}
.invoice_title ._title .txt-en{
  float: right;
  display: block;
  margin: 0px 12px;
  text-transform: uppercase;
  font-size: 1.25rem;
  font-weight: 500;
  letter-spacing: 5px;
  opacity: 0.65;
}
.invoice_title ._company {
  float: right;
  margin: 9px 0px;
  line-height: 1.6;
  text-align: right;
}
.invoice_title ._company ._name {
  font-size: 1.25em;
  letter-spacing: 4px;
}
.invoice_title ._company ._sub {
  opacity: 0.3;
}
/* 남색배경영역 END */
.invoice-wrap .invoice-desc{
  padding: 26px 64px 41px;
}
.invoice-wrap .invoice-desc ._title {
  margin: 41px 0px;
  text-align: center;
  font-size: 1.75em;
  font-weight: 500;
}
/* 주문내역 */
.invoice-prdt-table{
  width: 100%;
  border-collapse: collapse;
  font-size: 1.25em;
  color: #585858;
}
.invoice-prdt-table td, .invoice-prdt-table th {
  box-sizing: border-box;
  vertical-align: middle;
}
.invoice-prdt-table ._service {
  width: 50%;
  padding-left: 42px;
}
.invoice-prdt-table ._concept {
  width: 20%;
  text-align: center;
}
.invoice-prdt-table ._price {
  width: 30%;
  padding-right: 42px;
  text-align: right;
}
.invoice-prdt-table ._tbody_price {
  padding-right: 25px;
}
.invoice-prdt-table ._doublecol {
  width: 70%;
  padding-left: 42px;
}
.invoice-prdt-table .tbl-hd{
  line-height: 50px;
  border-top:1px solid #20345A;
  border-bottom:1px solid #20345A;
  background-color: #F4F4F4;
  text-transform: capitalize;
  text-align: left;
}
.invoice-prdt-table .tbl-con .order-prdt {
  height: 89px;
  border-top: 1px solid #D6D6D6;
}
.invoice-prdt-table .tbl-con .order-prdt:first-of-type {
  border-top: 0px none;
}
.invoice-prdt-table .order-prdt ._detail {
  opacity: 0.5;
}
.invoice-prdt-table .tbl-ft{
  border-top:1px solid #20345A;
  text-transform: capitalize;
}
.invoice-prdt-table .tbl-ft ._tfoot_cell {
  padding-top: 20px;
  padding-bottom: 20px;
}
.invoice-prdt-table .tbl-ft ._tfoot_cell b.txt-en{
  font-size: 1.875rem;
  color: #1D6AFF;
}
.tbl_scroll{
  position: relative;
  height: 253px;
  overflow-y:scroll;
}
/* 주문내역 END */
/* 주문자 정보 */
.invoice-customar-info {
  margin: 30px 0px;
}
.invoice-customar-info ._infomation {
  border-top: 1px solid #D6D6D6;
}
.invoice-customar-info ._bdtop_none {
  border: 0px none;
}
.invoice-customar-info .customar-info > dt {
  display: inline-block;
  width: 77px;
  padding: 16px 8px;
  line-height: 1.7;
  vertical-align: top;
  color: #B9B9B9;
}
.invoice-customar-info .customar-info > dd {
  display: inline-block;
  padding: 16px 8px;
  line-height: 1.7;
  color: #585858;
}
.invoice-customar-info .customar-info ._date {
  height: 86px;
  /* height - padding */
  line-height: 54px;
}
/* 주문자 정보 END */
/* END 견적서 페이지 */
</style>

  <div class="app">

    <!-- 페이지컨텐츠 영역 -->
    <div class="wrapper">

      <!-- * 견적서확인 페이지 -->
      <div class="container invoice-container">
        <span class="container-bg"></span>
        <span class="container-bg-bottom"></span>

        <div class="invoice-wrapper">
          <section style="max-width: 880px;margin: 0px auto;background-color: #fff;box-shadow: 12px 31px 34px rgba(0, 0, 0, 0.1);">
            <div class="invoice_title clearfix" style="padding: 24px 20px;background-color: #20345a;color: white;height: 70px;">
              <div style="float: left;width: 45px;height: 45px;border-radius: 50%;background-color: #fff;background-image: url(http://pocketmall-vue.local/assets/images/icon/category-symbol.svg);background-repeat: no-repeat;background-size: 43px;background-position: center center;"> </div>
              <h2 class="_title" style="float: left;height: 45px;line-height: 45px;margin: 0px 16px;font-size: 1.5em;font-weight: 600;letter-spacing: 8px;">견적서<small style="float: right;display: block;margin: 0px 12px;text-transform: uppercase;font-size: 1.25rem;font-weight: 500;letter-spacing: 5px;opacity: 0.65;">invoice</small></h2>
              <div style="float: right;margin: 9px 0px;line-height: 1.6;text-align: right;">
                <div style="font-size: 0.75em;letter-spacing: 4px;">주식회사 포켓컴퍼니</div>
                <div style="opacity: 0.3;">소중한 인연을 기대합니다</div>
              </div>
            </div>
            <div style="padding: 26px 0px 41px;">
              <h3 style="margin: 22px 0px;text-align: center;font-size: 1.25em;font-weight: 500;">
                신청해주셔서 감사합니다
              </h3><table style="width: 100%;border-collapse: collapse;font-size: 1.25em;color: #585858;">
                <thead style="line-height: 50px;border-top: 1px solid #20345a;border-bottom: 1px solid #20345a;background-color: #f4f4f4;text-transform: capitalize;text-align: left;">
                  <tr>
                    <th style="width: 50%;padding-left: 42px;box-sizing: border-box;vertical-align: middle;">service name</th>
                    <th style="width: 20%;text-align: center;box-sizing: border-box;vertical-align: middle;">concept</th>
                    <th style="width: 30%;padding-right: 42px;box-sizing: border-box;vertical-align: middle;">price</th>
                  </tr>
                </thead>
              </table>
              <div style="position: relative;height: 253px;overflow-y: scroll;">
                <table style="width: 100%;border-collapse: collapse;font-size: 1.25em;color: #585858;">
                  <tbody class="tbl-con">
                  @foreach($items as $item)
                    <tr style="border-bottom: 1px solid #d6d6d6;height: 89px;">
                      <td style="width: 50%;padding-left: 42px;box-sizing: border-box;vertical-align: middle;">
                        <div class="_desc">{{$item['title']}}</div>
                        <small style="opacity: 0.5;">{{$item['simple_intro']}}</small>
                      </td>
                      <td style="width: 20%;text-align: center;box-sizing: border-box;vertical-align: middle;">
                        <div class="_desc">
                        {{$item['option']['op_type']}} Type ({{$item['option']['op_concept']}})
                        </div>
                      </td>
                      <td style="width: 30%;padding-right: 25px;text-align:right;box-sizing: border-box;vertical-align: middle;">
                        <div class="_desc"><b style="letter-spacing: 0.5px;">{{number_format($item['total_price'])}}</b>원</div>
                      </td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              </div>
              <table style="width: 100%;border-collapse: collapse;font-size: 1.25em;color: #585858;">
                <tfoot style="border-top: 1px solid #20345a;text-transform: capitalize;">
                  <tr>
                    <td style="width: 70%;padding-left: 42px;box-sizing: border-box;vertical-align: middle;" colspan="2">
                      <div style="letter-spacing: 0.5px;">total price</div>
                    </td>
                    <td style="width: 30%;padding-right: 42px;text-align: right;box-sizing: border-box;vertical-align: middle;padding-top: 20px;padding-bottom: 20px;">
                      <div class="_desc"><b class="txt-en">{{number_format($data->total_price)}} </b>원</div>
                    </td>
                  </tr>
                </tfoot>
              </table>
              <ul style="margin: 30px 0px;padding: 0;list-style: none;">
                <li>
                  <dl class="customar-info">
                    <dt style="display: inline-block;width: 77px;padding: 16px 8px;line-height: 1.7;vertical-align: top;color: #b9b9b9;">이름 :</dt>
                    <dd style="display: inline-block;padding: 16px 8px;line-height: 1.7;color: #585858;margin:0;">{{$data->req_name}}</dd>
                  </dl>
                </li>
                <li style="border-top: 1px solid #d6d6d6;">
                  <dl class="customar-info">
                    <dt style="display: inline-block;width: 77px;padding: 16px 8px;line-height: 1.7;vertical-align: top;color: #b9b9b9;">연락처 :</dt>
                    <dd style="display: inline-block;padding: 16px 8px;line-height: 1.7;color: #585858;margin:0;">{{$data->req_tel}}</dd>
                  </dl>
                </li>
                <li style="border-top: 1px solid #d6d6d6;">
                  <dl class="customar-info">
                    <dt style="display: inline-block;width: 77px;padding: 16px 8px;line-height: 1.7;vertical-align: top;color: #b9b9b9;">회사명 :</dt>
                    <dd style="display: inline-block;padding: 16px 8px;line-height: 1.7;color: #585858;margin:0;">{{$data->req_company}}</dd>
                  </dl>
                </li>
                <li style="border-top: 1px solid #d6d6d6;">
                  <dl class="customar-info">
                    <dt style="display: inline-block;width: 77px;padding: 16px 8px;line-height: 1.7;vertical-align: top;color: #b9b9b9;">진행<br>가능 일자</dt>
                    <dd style="display: inline-block;padding: 16px 8px;line-height: 1.7;color: #585858;height: 86px;line-height: 54px;margin:0;">{{$data->req_date}}</dd>
                  </dl>
                </li>
                <li style="border-top: 1px solid #d6d6d6;">
                  <dl class="customar-info">
                    <dt style="display: inline-block;width: 77px;padding: 16px 8px;line-height: 1.7;vertical-align: top;color: #b9b9b9;">기타<br>요청사항</dt>
                    <dd style="display: inline-block;padding: 16px 8px;line-height: 1.7;color: #585858;margin:0;">
                    {!! $data->req_contents !!}
                    </dd>
                  </dl>
                </li>
              </ul>
            </div>
          </section>
        </div>
      </div>
      <!-- END * 장바구니내역확인 페이지 -->

    </div>
    <!-- END 페이지컨텐츠 영역 -->

  </div>

</body>

</html>