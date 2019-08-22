@extends("structure")

@push("head")
    <script src="/assets/js/default.js" type="application/javascript"></script>
@endpush

<!--Header-->
@section("header")
    <header class="outside">
        <div class="inside flex f-between">
            <!--Logo-->
            <a href="/" class="logo left" title="AREUKA">
                <img src="/assets/images/logo.png" alt="AREUKA" height="70">
            </a>

            {{--Navigation--}}
            <nav class="center">
                <a class="item{{!empty($page_name) && $page_name === "home" ? " active" : ""}}" href="{{ route("home") }}">
                    <span class="nav-name">HOME</span>
                </a>
                <a class="item{{!empty($page_name) && $page_name === "projects" ? " active" : ""}}" href="{{ route("projects.home") }}">
                    <span class="nav-name">프로젝트 일람</span>
                </a>
                <a class="item{{!empty($page_name) && $page_name === "tryouts" ? " active" : ""}}" href="{{ route("tryouts.home") }}">
                    <span class="nav-name">삽질 일기</span>
                </a>
                <a class="item{{!empty($page_name) && $page_name === "practices" ? " active" : ""}}" href="{{ route("practices.home") }}">
                    <span class="nav-name">기능대회 준비</span>
                </a>
            </nav>

            <!-- user-form -->
            <div class="user-group right">
                @if(!auth()->check())
                    <a href="{{route("users.login")}}">로그인</a>
                    <a href="{{route("users.join")}}">회원가입</a>
                @else
                    <div class="user-info f-left">
                        <div class="user-icon f-left">
                            <img src="/assets/images/users/default.png" alt="유저 프로필">
                        </div>
                        <span class="user_name">{{auth()->user()->user_name}}</span>
                    </div>
                    <button class="list-btn">
                        <img class="no-mouse" src="/assets/images/nav-icon.png" alt="nav-icon">
                        <div class="drop-down list">
                            <a href="{{route("bookmarks.home")}}">즐겨찾기</a>
                            <a href="{{route("options.home")}}">설정</a>
                            <a href="{{route("session.destroy")}}" id="logout">로그아웃</a>
                        </div>
                    </button>
                @endif
            </div>

        </div>
    </header>
@endsection


<!--footer-->
@section("footer")
    <footer>
    <div class="body outside">
        <div class="inside flex">
            <div class="left">
                <!--유저 프로필-->
                <div class="user-profile">
                    <div class="user-icon f-left">
                        <img src="/assets/images/users/default.png" alt="유저 프로필">
                    </div>
                    <div class="user-info f-left">
                        @if(!auth()->check())
                            <div class="login-message">
                                <a href="{{route("users.login")}}">로그인</a>이 필요합니다.
                            </div>
                            <div class="join-message">
                                <a href="{{route("users.join")}}" class="muted">계정이 없으신가요?</a>
                            </div>
                        @else
                            <div class="info-group">
                                <p><span class="user_name">{{auth()->user()->user_name}}</span>님 안녕하세요!</p>
                                <span class="user_id muted">{{"@". auth()->user()->user_id}}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <!--유저 알림 & 메세지-->
                <div class="user-alert">
                    <div class="alert-group f-left">
                        <img src="/assets/images/icons/bell.png" alt="알림">
                        <a href="/users/alert">알림</a>
                        <span class="count">0</span>
                    </div>
                    <div class="alert-group f-left">
                        <img src="/assets/images/icons/mail.png" alt="메세지">
                        <a href="/users/mail">메세지</a>
                        <span class="count">0</span>
                    </div>
                </div>
                <!--SNS 공유-->
                <div class="network-share">
                    <div class="icons">
                        <a href="">
                            <img src="/assets/images/social/facebook.png" alt="페이스북">
                        </a>
                        <a href="">
                            <img src="/assets/images/social/twitter.png" alt="트위터">
                        </a>
                        <a href="">
                            <img src="/assets/images/social/kakato.png" alt="카카오톡">
                        </a>
                    </div>
                </div>
            </div>
            <div class="center">
                <div class="title">
                    주요 프로젝트 바로가기
                </div>
                <div class="contents">
                    <!--2018년-->
                    <div class="year-2018">
                        <div class="sub-title">2018</div>
                        <div class="project-list">
                            <div class="item">
                                <a href="">
                                    Dinecto
                                </a>
                            </div>
                            <div class="item">
                                <a href="">
                                    Cartoon Viewer
                                </a>
                            </div>
                            <div class="item">
                                <a href="">
                                    Naver News Viewer
                                </a>
                            </div>
                            <div class="item">
                                <a href="">
                                    지번 주소 조회
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--2019년-->
                    <div class="year-2018">
                        <div class="sub-title">2019</div>
                        <div class="project-list">
                            <div class="item">
                                <a href="">
                                    Cloud Music
                                </a>
                            </div>
                            <div class="item">
                                <a href="">
                                    TIS 주차정보 입력
                                </a>
                            </div>
                            <div class="item">
                                <a href="">
                                    AREUKA
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--2020년-->
                    <div class="year-2018">
                        <div class="sub-title">2020</div>
                        <div class="project-list">
                            <div class="item">
                                <a href="#" class="no-mouse">
                                    2020학년도 예정
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right">
                <div class="made-info">
                    <div class="info-group">
                        <div class="label f-left">Tel</div>
                        <div class="muted f-left">010-6862-4010</div>
                    </div>
                    <div class="info-group">
                        <div class="label f-left">E-mail</div>
                        <div class="muted f-left">areuka0407@gmail.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="foot outside">
        <div class="inside">
            <p class="muted">Copyright ⓒ 2019 Areuka All Right Resolved</p>
        </div>
    </div>
</footer>
@endsection
