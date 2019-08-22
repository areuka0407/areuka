@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/index.css" type="text/css">
    <script type="text/javascript" src="/assets/js/app.js"></script>
    
@endpush


@section("contents")
<!--Visual-->
<div id="visual" class="outside">
    <div class="inside">
        <div class="main-text"></div>
        <form id="search" autocomplete="off">
            <div class="search-bar">
                <input type="text" name="keyword" placeholder="키워드 검색을 통해서 그동안 만든 작품들을 살펴보세요!" title="키워드를 입력해 주세요!" required>
            </div>
            <button type="submit" class="search-btn" title="검색하기"></button>
        </form>
    </div>
</div>

<!--Introduction-->
<div id="introduction" class="outside">
    <div class="left-side"></div>
    <div class="right-side">
        <div class="title">
            <div class="box-effect"></div>
            <h1>사이트 소개</h1>
        </div>
        <div class="contents">
            <h2>"꿈을 향한 나의 발자취"</h2>
            <p>
                이 사이트를 한마디로 표현하자면 이렇게 표현할 수 있을 것 같습니다. 고등학교라는 3년의 시간동안<br>
                저의 목표인 '웹 개발자'를 향해서 걸어왔던 발자취를 저장하는 곳이기 때문입니다.<br>
                이 사이트의 주요 목적은 제가 지금까지 모아둔 포트폴리오나 프로젝트를 진행했던 자료들, 혹은 공부했던 파일들을<br>
                여러 카테고리 별로 정리하고, 어떤 사람이든지 제가 해온 일들을 쉽게 설명할 수 있게 하기 위함입니다.<br>
                또한, 이 사이트는 저 자신이 지금까지 공부해 왔던 것을 되돌아보고 부족한 부분들을 매꿀 수 있는 기회가 될 것입니다.
            </p>
        </div>
    </div>
</div>

<!--ProjectView-->
<div id="project-view">
    <div class="head outside">
        <div class="inside flex f-between">
            <div class="count self-bottom bold mb-2">
                <p><span class="bold">{{count($projects)}}</span>개의 프로젝트 발견</p>
            </div>
            <div class="title self-center">
                <h1>프로젝트 일람</h1>
                <div class="box-effect"></div>
            </div>
            <div class="plus self-bottom mb-2 right-text">
                <a href="{{ route("projects.home") }}" class="bold">+ 더보기</a>
            </div>
        </div>
    </div>
    <div class="body outside">
        <div class="inside">
            <ul class="card-list">
                @forelse ($projects as $project)
                    <li class="card">
                        <div class="card-head" style="background-color: {{$project->back_color}};">
                            <div class="image">
                                <img src="/files/projects/{{$project->saved_folder}}/{{$project->thumbnail}}" alt="{{$project->title}}">
                            </div>
                        </div>
                        <div class="card-body">
                            <h3 class="info-title" style="color: {{$project->font_color}}" title="{{$project->title}}">
                                {{$project->title}}
                            </h3>
                            <div class="hash-box">
                                @foreach ($project->hash_tag as $tag)
                                    <span class="hash-tag">{{$tag}}</span>
                                @endforeach
                            </div>
                            <div class="info-group">
                                <div class="calender">
                                    <span class="label f-left">
                                        <img src="/assets/images/icons/calender.png" alt="개발 일시" width="20" height='20'>
                                    </span>
                                    <span class="f-left" title="{{date("Y.m.d", strtotime($project->dev_start))}} ~ {{date("Y.m.d", strtotime($project->dev_end))}}">
                                        {{date("Y년 m월", strtotime($project->dev_start))}}
                                    </span>
                                </div>
                                <div class="lang">
                                    <span>PHP</span>
                                </div>
                            </div>
                        </div>
                        <a href="/project/1" class="detailed_info">
                            <div class="reading-glass">
                            </div>
                            <p>자세히 보기</p>
                        </a>
                    </li>
                @empty
                    <p>등록된 프로젝트가 없습니다.</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
