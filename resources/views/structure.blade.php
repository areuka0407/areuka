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
    @stack("anything")
</body>
</html>
