@extends('layouts.app')

@section('content')

<div class="wrapper wrapper--splash">

    <div class="wrapper--splash__container">

        <div class="ani-logo-group">

            <span class="em-logo-text">
                <em class="logo--text logo--text--00">세</em>
                <em class="logo--text logo--text--01">상</em>
                <em class="logo--text logo--text--02">에</em>
                <em class="logo--text logo--text--03 em-logo-text--mr">서</em>
                <em class="logo--text logo--text--04">나</em>
                <em class="logo--text logo--text--05 em-logo-text--mr">를</em>
                <em class="logo--text logo--text--06">가</em>
                <em class="logo--text logo--text--07 em-logo-text--mr">장</em>
                <em class="logo--text logo--text--08 em-logo-text--mr">잘</em>
                <em class="logo--text logo--text--09">아</em>
                <em class="logo--text logo--text--010 em-logo-text--mr">는</em>
                <em class="logo--text logo--text--011">여</em>
                <em class="logo--text logo--text--012">행</em>
            </span>

            <div class="ani-logo-group__logo">

                <svg id="그룹_410" class="logo--text logo--text--015" data-name="그룹 410"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="52.252"
                    height="72.119" viewBox="0 0 52.252 72.119">
                    <image id="사각형_45" data-name="사각형 45" width="48.557" height="52.227" transform="translate(3.695 0)"
                        xlink:href="data:image/png;base64,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" />
                    <circle id="타원_21" data-name="타원 21" cx="3.567" cy="3.567" r="3.567"
                        transform="translate(22.38 24.178)" fill="#00dbd8" />
                    <path id="패스_2419" data-name="패스 2419"
                        d="M149.8,288.63a3.837,3.837,0,0,0-.735-1.411Q149.407,287.932,149.8,288.63Z"
                        transform="translate(-146.48 -249.674)" fill="#00dbd8" />
                    <path id="패스_2420" data-name="패스 2420"
                        d="M172.22,239.664l-1.078-2.083L151.39,208.975l0,0c-1.146.482-3.068-2.174-3.913-4.275a6.832,6.832,0,0,1-.478-2.094l-3.151-4.564a26.312,26.312,0,0,0-3.511,18.514l-.005.065a25.951,25.951,0,0,0,2.174,6.609,3.838,3.838,0,0,1,.738,1.409,26.284,26.284,0,0,0,2.033,3.078c.059.077.123.15.183.226l-.006,0,1.018,1.464-.014.01,19.622,28.371s4.359-3.714,4.861-4.325A11.925,11.925,0,0,0,172.22,239.664Z"
                        transform="translate(-139.912 -185.675)" fill="#00dbd8" />
                    <path id="패스_2421" data-name="패스 2421"
                        d="M169.388,220.58l0,0-4.4-6.366a6.834,6.834,0,0,0,.478,2.094C166.32,218.406,168.242,221.063,169.388,220.58Z"
                        transform="translate(-157.915 -197.277)" fill="#00dbd8" />
                    <circle id="타원_22" data-name="타원 22" cx="4.02" cy="4.02" r="4.02"
                        transform="translate(21.814 23.386)" fill="#00dbd8" />
                    <path id="패스_2422" data-name="패스 2422"
                        d="M216.193,225.114c.066-.12.133-.239.2-.357a9.511,9.511,0,0,0-17.215,8.065l8.194,11.867a1.38,1.38,0,0,0,1.691-.481l6.62-9.624c.053-.075.105-.15.156-.227l0,0h0a9.462,9.462,0,0,0,1.463-3.681l-.054-.051A4.727,4.727,0,0,1,216.193,225.114Zm-8.268,7.443a3.484,3.484,0,1,1,3.484-3.484A3.484,3.484,0,0,1,207.925,232.558Z"
                        transform="translate(-181.896 -201.115)" fill="none" />
                    <path id="패스_2423" data-name="패스 2423"
                        d="M155.95,299.128c-.047-.068-.094-.135-.139-.2C155.857,298.993,155.9,299.06,155.95,299.128Z"
                        transform="translate(-151.323 -258.076)" fill="#fff" opacity="0.5" />
                    <path id="패스_2424" data-name="패스 2424" d="M160.485,305.435h0l-1.022-1.478Z"
                        transform="translate(-153.944 -261.687)" fill="#fff" opacity="0.5" />
                    <path id="패스_2425" data-name="패스 2425" d="M151.665,292.216h0Z"
                        transform="translate(-148.347 -253.261)" fill="#fff" opacity="0.5" />
                    <path id="패스_2426" data-name="패스 2426" d="M154.078,296.27l-.1-.151Z"
                        transform="translate(-150.011 -256.062)" fill="#fff" opacity="0.5" />
                    <path id="패스_2427" data-name="패스 2427" d="M153.206,294.857l-.075-.125Z"
                        transform="translate(-149.399 -255.067)" fill="#fff" opacity="0.5" />
                    <path id="패스_2428" data-name="패스 2428"
                        d="M158.088,302.132q-.15-.2-.3-.4Q157.938,301.935,158.088,302.132Z"
                        transform="translate(-152.744 -260.093)" fill="#fff" opacity="0.5" />
                    <path id="패스_2429" data-name="패스 2429" d="M154.993,297.7l-.115-.176Z"
                        transform="translate(-150.653 -257.067)" fill="#fff" opacity="0.5" />
                    <path id="패스_2430" data-name="패스 2430" d="M152.385,293.473l-.051-.088Z"
                        transform="translate(-148.827 -254.1)" fill="#fff" opacity="0.5" />
                    <path id="패스_2431" data-name="패스 2431"
                        d="M156.958,300.576c-.058-.081-.117-.162-.175-.244C156.841,300.414,156.9,300.495,156.958,300.576Z"
                        transform="translate(-152.02 -259.085)" fill="#fff" opacity="0.5" />
                    <path id="패스_2432" data-name="패스 2432"
                        d="M155.435,298.363c-.05-.073-.1-.147-.148-.221C155.336,298.216,155.385,298.29,155.435,298.363Z"
                        transform="translate(-150.947 -257.514)" fill="none" />
                    <path id="패스_2433" data-name="패스 2433"
                        d="M173.262,169.2a26.573,26.573,0,0,0-3.757,2.868c1.128-.263,1.671-1.22,2.66-1.835A3.7,3.7,0,0,0,173.262,169.2Z"
                        transform="translate(-161.151 -164.972)" fill="none" />
                    <path id="패스_2434" data-name="패스 2434"
                        d="M154.478,296.9c-.053-.081-.106-.163-.158-.244C154.372,296.737,154.424,296.818,154.478,296.9Z"
                        transform="translate(-150.253 -256.446)" fill="none" />
                    <path id="패스_2435" data-name="패스 2435"
                        d="M145.451,278.651a25.929,25.929,0,0,0,1.754,3.83,3.837,3.837,0,0,0-.735-1.411A25.923,25.923,0,0,1,145.451,278.651Z"
                        transform="translate(-143.887 -243.525)" fill="none" />
                    <path id="패스_2436" data-name="패스 2436" d="M156.44,299.837l-.135-.195Z"
                        transform="translate(-151.677 -258.591)" fill="none" />
                    <path id="패스_2437" data-name="패스 2437"
                        d="M157.512,301.348c-.037-.051-.073-.1-.11-.152C157.439,301.247,157.475,301.3,157.512,301.348Z"
                        transform="translate(-152.464 -259.706)" fill="none" />
                    <path id="패스_2438" data-name="패스 2438"
                        d="M153.562,295.441q-.083-.133-.165-.267Q153.479,295.308,153.562,295.441Z"
                        transform="translate(-149.59 -255.384)" fill="none" />
                    <path id="패스_2439" data-name="패스 2439"
                        d="M152.687,293.989c-.059-.1-.117-.195-.174-.293C152.57,293.794,152.629,293.891,152.687,293.989Z"
                        transform="translate(-148.956 -254.323)" fill="none" />
                    <path id="패스_2440" data-name="패스 2440"
                        d="M151.855,292.547c-.064-.11-.127-.219-.189-.33C151.728,292.327,151.791,292.437,151.855,292.547Z"
                        transform="translate(-148.348 -253.261)" fill="none" />
                    <path id="패스_2443" data-name="패스 2443"
                        d="M223.257,240.921a3.484,3.484,0,1,0,3.484,3.484A3.484,3.484,0,0,0,223.257,240.921Z"
                        transform="translate(-197.228 -216.447)" fill="none" />
                </svg>

                <svg class="logo--text logo--text--016" xmlns="http://www.w3.org/2000/svg" width="40.391"
                    height="52.808" viewBox="0 0 40.391 52.808">
                    <path id="패스_2695" data-name="패스 2695"
                        d="M387.647,201.413H372.8v-8.849h40.391v8.849H398.209v43.959H387.647Z"
                        transform="translate(-372.804 -192.564)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--017" xmlns="http://www.w3.org/2000/svg" width="25.62" height="40.96"
                    viewBox="0 0 25.62 40.96">
                    <path id="패스_2696" data-name="패스 2696"
                        d="M530.492,235.615h10.029l.836,8.274h.334a20.626,20.626,0,0,1,6.1-7.021,12.519,12.519,0,0,1,7.188-2.424,14.342,14.342,0,0,1,3.176.293,18,18,0,0,1,2.34.71l-2.173,10.614a12.206,12.206,0,0,0-2.3-.585,17.583,17.583,0,0,0-2.549-.167,10.323,10.323,0,0,0-5.767,2.048q-3.009,2.049-5.015,7.146v27.915h-12.2Z"
                        transform="translate(-530.492 -234.445)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--018" xmlns="http://www.w3.org/2000/svg" width="12.488"
                    height="57.803" viewBox="0 0 12.488 57.803">
                    <path id="패스_2697" data-name="패스 2697"
                        d="M643.24,186.321a6.466,6.466,0,0,1-4.5-1.606,5.652,5.652,0,0,1,0-8.242,6.558,6.558,0,0,1,4.5-1.57,6.429,6.429,0,0,1,4.46,1.57,5.732,5.732,0,0,1,0,8.242A6.339,6.339,0,0,1,643.24,186.321Zm-5.281,6.423h10.419v39.963H637.959Z"
                        transform="translate(-636.96 -174.903)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--019" xmlns="http://www.w3.org/2000/svg" width="38.036"
                    height="52.808" viewBox="0 0 38.036 52.808">
                    <path id="패스_2698" data-name="패스 2698"
                        d="M720.216,192.564h17.341a38.383,38.383,0,0,1,8.135.821,18.171,18.171,0,0,1,6.565,2.748,13.484,13.484,0,0,1,4.389,5.1,17.375,17.375,0,0,1,1.606,7.885,17.763,17.763,0,0,1-1.606,7.814,15.191,15.191,0,0,1-4.353,5.459,18.427,18.427,0,0,1-6.458,3.176,29.631,29.631,0,0,1-7.992,1.035h-7.065v18.768H720.216Zm16.913,25.69q10.774,0,10.776-9.135,0-4.638-2.819-6.423t-8.314-1.784h-5.994v17.341Z"
                        transform="translate(-720.216 -192.564)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--020" xmlns="http://www.w3.org/2000/svg" width="12.488"
                    height="57.803" viewBox="0 0 12.488 57.803">
                    <path id="패스_2697" data-name="패스 2697"
                        d="M643.24,186.321a6.466,6.466,0,0,1-4.5-1.606,5.652,5.652,0,0,1,0-8.242,6.558,6.558,0,0,1,4.5-1.57,6.429,6.429,0,0,1,4.46,1.57,5.732,5.732,0,0,1,0,8.242A6.339,6.339,0,0,1,643.24,186.321Zm-5.281,6.423h10.419v39.963H637.959Z"
                        transform="translate(-636.96 -174.903)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--021" xmlns="http://www.w3.org/2000/svg" width="32.541"
                    height="41.961" viewBox="0 0 32.541 41.961">
                    <path id="패스_2700" data-name="패스 2700"
                        d="M972.817,276.406a20.562,20.562,0,0,1-7.707-1.427,17.641,17.641,0,0,1-6.173-4.1,19.126,19.126,0,0,1-4.1-6.565,24.369,24.369,0,0,1-1.5-8.813,22.508,22.508,0,0,1,1.677-8.92,19.825,19.825,0,0,1,4.46-6.6,19.217,19.217,0,0,1,6.494-4.1,21.485,21.485,0,0,1,7.779-1.428,15.853,15.853,0,0,1,6.565,1.285,21.7,21.7,0,0,1,5.067,3.14l-5,6.78a13.059,13.059,0,0,0-2.961-1.963,7.47,7.47,0,0,0-3.176-.678,9.143,9.143,0,0,0-7.386,3.39q-2.82,3.39-2.819,9.1,0,5.638,2.783,8.992a8.855,8.855,0,0,0,7.136,3.354,10.038,10.038,0,0,0,4.139-.892,14.559,14.559,0,0,0,3.64-2.319l4.139,6.922a18.709,18.709,0,0,1-6.28,3.675A20.763,20.763,0,0,1,972.817,276.406Z"
                        transform="translate(-953.335 -234.445)" fill="#00244C" />
                </svg>
                <svg class="logo--text logo--text--022" xmlns="http://www.w3.org/2000/svg" width="37.037"
                    height="56.947" viewBox="0 0 37.037 56.947">
                    <path id="패스_2701" data-name="패스 2701"
                        d="M1095.126,177.93h10.2v34.254h.285l13.844-17.269h11.418l-13.844,16.342,15.129,23.621h-11.347l-9.705-16.556-5.78,6.565v9.991h-10.2Z"
                        transform="translate(-1095.126 -177.93)" fill="#00244C" />
                </svg>

            </div>

            <div class="splash__progress-group">
                <span class="splash__progress-bar"></span>
            </div>

        </div>

        <div class="splash__progress">
            <div class="splash__progress__bar">
                <span class="splash__progress__gage"></span>
            </div>
        </div>

    </div>

</div>
@endsection

@section('script')
    <script>
        setTimeout(function() {
            location.href = '/intro';
        }, 3000);
    </script>
@endsection