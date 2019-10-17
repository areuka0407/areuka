@php
    if(!user() && (session()->has("current_key") || isset($_COOKIE['SESSION_KEY'])))
    {
        echo "<script type='text/javascript'>alert('세션이 만료되어 로그아웃 됩니다.');</script>";
        session()->forget("current_key");
        setcookie("SESSION_KEY", "", 0);
        auth()->logout();
    }


    /* 지정된 Session key 가 Cookie에 없으면 로그아웃 */
    //if(user() && !isset($_COOKIE['SESSION_KEY'])) auth()->logout();
@endphp
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Areuka.com</title>

    {{--Default--}}
    <link rel="stylesheet" href="/assets/css/default.css" type="text/css">
    <script src="https://kit.fontawesome.com/d996c53776.js"></script>
    <link rel="icon" href="/assets/images/favicon.png">
    <script type="application/javascript" src="/assets/js/jquery-3.4.1.min.js"></script>
    <script>
        // 로그인 관리 부분 추가 요망
    </script>

    {{--Flash Message--}}
    @if(session('flash_message'))
        <script type="text/javascript">
            alert('{{session("flash_message")}}');
        </script>
        @php(session()->forget("flash_message"))
    @endif


    {{--Yield--}}
    @stack("head")
</head>
<body>
    @yield("header")
    @yield("contents")
    @yield("footer")

    @if(admin())
        @include("common/join_manager")
    @endif

    @stack("anything")
</body>
</html>
