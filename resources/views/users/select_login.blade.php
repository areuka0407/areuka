@extends("structure")

@push("head")
    <link rel="stylesheet" href="/assets/css/users/users.css">
    <script type="text/javascript" src="/assets/js/users/login.js"></script>
    <script type="text/javascript" src="/assets/js/input.js"></script>
@endpush

@section("contents")
    <div id="users-container" class="outside">
        <div class="inside">
            <div class="contents">
                <form method="post" autocomplete="off" action="{{ route("session.create") }}">
                    @csrf
                    <input type="hidden" name="select" value="1">
                    <input type="hidden" name="user_id" value="{{$user->user_id}}">
                    <div class="form-profile"></div>
                    <div class="form-title">
                        <h2><span class="color-font">{{$user->user_name}}</span> 계정으로 로그인합니다.</h2>
                    </div>
                    <div class="form-group">
                        <div class="custom-input">
                            <input type="password" placeholder="비밀번호" name="password" required>
                            <div class="bar"></div>
                        </div>
                        @if($errors->first("login_message"))
                            <p class="form-error">{{$errors->first("login_message")}}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="form-item f-left">
                            <input type="checkbox" id="login-maintain" name="maintain" value="0">
                            <label for="login-maintain">로그인 상태 유지</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="submit-btn">
                            로그인
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection