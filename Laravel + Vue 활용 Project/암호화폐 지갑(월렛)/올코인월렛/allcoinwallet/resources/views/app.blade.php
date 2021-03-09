<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- NICECHECK Token -->
    <meta name="enc_data" content="{{ $enc_data }}">
</head>

<body>
    <div id="app" class="content" v-cloak>
        <progress-component></progress-component>
        <router-view></router-view>
    </div>

    <!-- Lang -->
    <script>
        window.__ = JSON.parse(atob('{{$lang_data}}'));
        window.__.VERSION = "1.4.0";
    </script>

    <!-- App -->
    @if (app()->environment('production'))
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    @endif
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('vendor/android/android_api.js') }}"></script>
</body>

</html>