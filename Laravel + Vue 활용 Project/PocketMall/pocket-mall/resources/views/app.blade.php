<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>포켓몰</title>

  <meta name="format-detection" content="telephone=no">
  <meta name="title" content="포켓몰">
  <meta name="author" content="플랫커머스">
  <meta name="description" content="">
  <meta name="keywords" content="영상제작, 광고배너 제작, 보고서, 홈페이지 제작, SNS 마케팅, 마케팅, 올인원 패키지, 상세페이지 디자인, 사업 제안서">

  <!-- Open Graph -->
  <meta property="og:type" content="website">
  <!-- <meta property="og:url" content="http://www.pocketmall.com"> -->
  <meta property="og:title" content="포켓몰">
  <meta property="og:description" content="">
  <meta property="og:image" content="assets/images/favicon/metathumb.png">
  <!-- END Open Graph -->

  <!-- favicon -->
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-36x36.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-48x48.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-72x72.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-96x96.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-144x144.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-192x192.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-256x256.png">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/android-chrome-512x512.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="assets/images/favicon/site.webmanifest">
  <link rel="mask-icon" href="assets/images/favicon/safari-pinned-tab.svg" color="#1dbbff">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  <!-- END favicon -->

  <!-- CSS -->
  <link rel="stylesheet" href="css/ui.css">
  <!-- END CSS -->
  <script charset="utf-8" src="//cdn.iframe.ly/embed.js?api_key=770654f01a5097db10a296"></script>
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/rolling_banner.js') }}"></script>

  <!-- Facebook Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window,document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '924501544633767'); 
  fbq('track', 'PageView');
  </script>
  <noscript>
  <img height="1" width="1" 
  src="https://www.facebook.com/tr?id=924501544633767&ev=PageView
  &noscript=1"/>
  </noscript>
  <!-- End Facebook Pixel Code -->
</head>

<body>
  <div id="app">
  </div>
  <script src="{{ asset('js/app.js') }}"></script>
  <script>
  document.querySelectorAll('oembed[url]').forEach(element => {
    iframely.load(element, element.attributes.url.value);
  });
  </script>
</body>

</html>