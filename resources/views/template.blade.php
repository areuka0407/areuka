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
                @if(!user())
                    <a href="{{route("users.login")}}">로그인</a>
                    <a href="{{route("users.join")}}">회원가입</a>
                @else
                    <p class="inline"><span class="username">{{user()->user_name}}</span>님 안녕하세요!</p>
                    <a class="logout" href="{{route("session.destroy")}}" onclick="return confirm('로그아웃 하시겠습니까?')"></a>
                @endif
            </div>

        </div>
    </header>
@endsection


<!--footer-->
@section("footer")
<footer>
    <div class="body outside">
        <div class="inside flex f-between">
            <div class="projects">
                <div class="title">
                    주요 프로젝트 바로가기
                </div>
                <div class="contents">
                    <!--2018년-->
                    <div class="year-2018">
                        <div class="sub-title">2018</div>
                        <div class="project-list">
                            @forelse($main_projects['year_2018'] as $project)
                            <div class="item">
                                <a href="{{route("projects.view", $project->id)}}">
                                    {{$project->title}}
                                </a>
                            </div>
                            @empty
                                <p class="no-mouse non-record">프로젝트 기록이 없습니다.</p>
                            @endforelse

                        </div>
                    </div>
                    <!--2019년-->
                    <div class="year-2018">
                        <div class="sub-title">2019</div>
                        <div class="project-list">
                            @forelse($main_projects['year_2019'] as $project)
                                <div class="item">
                                    <a href="{{route("projects.view", $project->id)}}">
                                        {{$project->title}}
                                    </a>
                                </div>
                            @empty
                                <p class="no-mouse non-record">프로젝트 기록이 없습니다.</p>
                            @endforelse
                        </div>
                    </div>
                    <!--2020년-->
                    <div class="year-2018">
                        <div class="sub-title">2020</div>
                        <div class="project-list">
                            @forelse($main_projects['year_2020'] as $project)
                                <div class="item">
                                    <a href="{{route("projects.view", $project->id)}}">
                                        {{$project->title}}
                                    </a>
                                </div>
                            @empty
                                <p class="no-mouse non-record">프로젝트 기록이 없습니다.</p>
                            @endforelse
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
