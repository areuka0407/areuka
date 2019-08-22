<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Areuka.com</title>

    {{--Default--}}
    <link rel="stylesheet" href="/assets/css/default.css" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
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
