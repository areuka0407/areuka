@extends("structure")

@push("head")
    <link rel="stylesheet" href="/assets/css/users/users.css">
    <link rel="stylesheet" href="/assets/css/input.css">
    <script type="text/javascript" src="/assets/js/users/login.js"></script>
    <script type="text/javascript" src="/assets/js/input.js"></script>
@endpush

@push("anything")
    <script type="text/javascript">
        (function() {
            return new Promise((res, rej) => {
                let data = new FormData();


                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/load/logs");
                xhr.setRequestHeader("Content-type", "application/json");
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").content);
                xhr.send(JSON.stringify({ip_address: "{{$_SERVER['REMOTE_ADDR']}}"}));
                xhr.onload = () => res(JSON.parse(xhr.responseText));
                xhr.onerror = () => rej(xhr.response);
            });
        })().then(data => {
            return new Promise((res, rej) => {
                console.log(data);
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/load/users");
                xhr.setRequestHeader("Content-type", "application/json");
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").content);
                xhr.send(JSON.stringify({id: data[0].uid}));
                xhr.onload = () => res(JSON.parse(xhr.responseText));
                xhr.onerror = () => rej(xhr.response);
            });
        }).then(data => {
            console.log(data);
            let container = document.querySelector(".pre-login");
            container.querySelector("span").remove();
            let div = document.createElement("div");
            div.innerHTML = `<a href="/login/${data[0].user_id}"><span class="color-font">${data[0].user_name}</span> 계정으로 로그인</a>`;
            container.append(div.firstChild);
        });
    </script>
@endpush

@section("contents")
    <div id="users-container" class="outside">
        <div class="inside">
            <div class="contents">
                <a href="/" class="logo">
                    <img src="/assets/images/favicon.png" alt="AREUKA">
                </a>
                <form method="post" autocomplete="off" action="{{ route("session.create") }}">
                    @csrf
                    <div class="form-title">
                        <h2>로그인 정보를 입력해 주세요</h2>
                    </div>
                    <div class="form-group">
                        <div class="custom-input">
                            <input type="text" placeholder="아이디" name="user_id" required value="{{old("user_id")}}">
                            <div class="bar"></div>
                        </div>
                        <div class="custom-input">
                            <input type="password" placeholder="비밀번호" name="password" required>
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("login_message"))
                            <p class="form-error">{{$errors->first("login_message")}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn">
                            로그인
                        </button>
                    </div>
                    <div class="form-group">
                        <div class="form-item f-left">
                            <input type="checkbox" id="login-maintain" name="maintain" value="0">
                            <label for="login-maintain">로그인 상태 유지</label>
                        </div>
                        <div class="form-item f-right">
                            <div class="pre-login">
                                <img src="/assets/images/users/default.png" alt="유저 프로필 이미지" class="f-left mr-2 mt-2" width="30" height="30">
                                <span>이전 로그인 정보가 없습니다.</span>
                            </div>
                        </div>
                    </div>
                </form>
                <hr>
                <div class="find-info">
                    <a href="{{ route('remind.id') }}" id="findid">아이디 찾기</a>
                    <span class="bar"></span>
                    <a href="{{ route('remind.password') }}" id="findpw">비밀번호 찾기</a>
                    <span class="bar"></span>
                    <a href="{{ route('users.join') }}" id="join">회원가입</a>
                </div>
            </div>
        </div>
    </div>
@endsection