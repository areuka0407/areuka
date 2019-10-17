@extends("structure")

@push("head")
    <link rel="stylesheet" href="/assets/css/users/users.css">
    <link rel="stylesheet" href="/assets/css/input.css">
    <script src="/assets/js/input.js" type="text/javascript"></script>
    <script src="/assets/js/users/join.js" type="text/javascript"></script>
@endpush


@section("contents")
    <div id="users-container" class="outside">
        <div class="inside">
            <div class="contents">
                <a href="/" class="logo">
                    <img src="/assets/images/favicon.png" alt="AREUKA">
                </a>
                <form id="join-form" method="POST" autocomplete="off" action="{{ route("users.create") }}">
                    @csrf
                    <div class="form-title">
                        <h2>회원 정보를 입력하세요</h2>
                    </div>
                    <div id="form-input" class="form-group">
                        <div class="custom-input">
                            <input type="text" id="user_id" name="user_id" placeholder="아이디" value="{{ old("user_id") }}">
                            <div class="bar"></div>
                        </div>

                        @if($errors->first("user_id"))
                            <p class="form-error">{{$errors->first("user_id")}}</p>
                        @endif


                        <div class="custom-input">
                            <input type="password" id="user_pw" name="password" placeholder="비밀번호">
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("password"))
                            <p class="form-error">{{$errors->first("password")}}</p>
                        @endif

                        <div class="custom-input">
                            <input type="password" id="confirm" name="password_confirmation" placeholder="비밀번호 재입력">
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("password_confirm"))
                            <p class="form-error">{{$errors->first("password_confirm")}}</p>
                        @endif


                        <div class="custom-input">
                            <input type="email" id="user_email" name="user_email" placeholder="이메일" value="{{ old("user_email") }}">
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("user_email"))
                            <p class="form-error">{{$errors->first("user_email")}}</p>
                        @endif


                        <div class="custom-input">
                            <input type="text" id="user_name" name="user_name" placeholder="닉네임" value="{{ old("user_name") }}">
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("user_name"))
                            <p class="form-error">{{$errors->first("user_name")}}</p>
                        @endif

                    </div>
                    <div class="form-group">
                        <button class="submit-btn" type="submit">회원가입</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
