@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/list.css">
    <script src="/assets/js/articles/list.js" type="text/javascript"></script>
     <script src="/assets/js/search.js"></script>
@endpush

@section("contents")
    <div class="outside list-container">
        <div class="inside">

            {{--Section Header--}}
            <div class="section-header">
                <div class="section-title">
                    <span>
                        <div class="design-box"></div>
                        프로젝트 일람
                    </span>
                </div>
                <form id="search" onsubmit="return false" autocomplete="off">
                    <input type="text" id="search-input" class="search-bar" placeholder="검색어를 입력하세요." value="{{isset($_GET['keyword'])? rtrim($_GET['keyword']) : ""}}" autofocus="true">
                    <button class="search-btn" data-input="search-input"></button>
                </form>
                @if (admin())
                    <a href="{{ route("projects.write")  }}" class="post-btn f-right">
                        새 프로젝트 +
                    </a>
                @endif
            </div>

            {{--Section Body--}}
            <div class="section-body">
                <div class="section-time f_select" data-key="dev_start">
                    <button class="item f_option{{$condition->year == 2018 ? " active" : ""}}" data-value="^2018.*" data-year="2018" onclick="location.assign('{{route('projects.home', 2018)}}')">
                        <span class="no-mouse">
                            <img src="/assets/images/icons/active_small_pot.png" alt="2018" class="active">
                            <img src="/assets/images/icons/small_pot.png" alt="2018">
                            2018
                        </span>
                    </button>
                    <button class="item f_option{{$condition->year == 2019 ? " active" : ""}}" data-value="^2019.*" data-year="2019" onclick="location.assign('{{route('projects.home', 2019)}}')">
                        <span class="no-mouse">
                            <img src="/assets/images/icons/active_medium_pot.png" alt="2019" class="active">
                            <img src="/assets/images/icons/medium_pot.png" alt="2019">
                            2019
                        </span>
                    </button>
                    <button class="item f_option{{$condition->year == 2020 ? " active" : ""}}" data-value="^2020.*" data-year="2020" onclick="location.assign('{{route('projects.home', 2020)}}')">
                        <span class="no-mouse">
                            <img src="/assets/images/icons/active_big_pot.png" alt="2020" class="active">
                            <img src="/assets/images/icons/big_pot.png" alt="2020">
                            2020
                        </span>
                    </button>
                </div>
                @php
                    function catSum($a, $x){
                        $a += $x->cnt;
                        return $a;
                    }
                    function catFilter($a){
                        return $a !== 'category';
                    }
                @endphp
                <div class="section-category">
                    <div class="categories f-left f_select" data-key="main_lang">
                        <div class="item f_option{{$condition->category == "%_%" ? " active" : ""}}" data-value="%_%" onclick="location.assign('{{route('projects.home', $condition->year)}}{{$_GET ? "?" : ""}}{{http_build_query(array_filter($_GET, "catFilter", ARRAY_FILTER_USE_KEY))}}')">
                            <span class="name no-mouse">
                                All
                            </span>
                            <span class="count no-mouse">
                                {{array_reduce($categories, "catSum", 0)}}
                            </span>
                        </div>
                        @foreach ($categories as $item)
                            @php($item->lang = explode("|", $item->lang)[0])
                            <div class="item f_option{{preg_match($condition->category, $item->lang) ? " active" : ""}}" data-value="%{{$item->lang}}%" onclick="location.assign('{{route('projects.home', $condition->year)}}?{{http_build_query(array_merge($_GET, ['category'=>$item->lang]))}}')">
                                <div class="name no-mouse">
                                    {{$item->lang}}
                                </div>
                                <div class="count no-mouse">
                                    {{$item->cnt}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="sort f-right">
                        <select class="o_select">
                            <option data-key="title" value="0"{{$order->key == "title" && $order->direction == "ASC" ? " selected" : ""}}>제목: 오름차순</option>
                            <option data-key="title" value="1"{{$order->key == "title" && $order->direction == "DESC" ? " selected" : ""}}>제목: 내림차순</option>
                            <option data-key="dev_start" value="0"{{$order->key == "dev_start" && $order->direction == "ASC" ? " selected" : ""}}>생성된 날짜: 오름차순</option>
                            <option data-key="dev_start" value="1"{{$order->key == "dev_start" && $order->direction == "DESC" ? " selected" : ""}}>생성된 날짜: 내림차순</option>
                            <option data-key="main_lang" value="0"{{$order->key == "main_lang" && $order->direction == "ASC" ? " selected" : ""}}>주 사용 언어: 오름차순</option>
                            <option data-key="main_lang" value="1"{{$order->key == "main_lang" && $order->direction == "DESC" ? " selected" : ""}}>주 사용 언어: 내림차순</option>
                        </select>
                    </div>
                </div>
                <div class="section-list">
                    @forelse ($projects as $project)
                        <a href="{{route("projects.view", $project->id)}}" class="section-card">
                            <div class="image" style="background-color: {{$project->back_color}}">
                                <img src="/files/Projects/{{$project->saved_folder}}/{{$project->thumbnail}}" alt="{{$project->title}}">
                            </div>
                            <div class="info">
                                <div class="title" style="color: {{$project->font_color}}">{{$project->title}}</div>
                                <div class="hash-box">
                                    @foreach (explode("|", $project->hash_tag) as $item)
                                        <span class="hash-tag" title="{{$item}}">{{$item}}</span>
                                    @endforeach
                                </div>
                                <div class="info-group">
                                    <div class="calender">
                                        <img src="/assets/images/icons/calender.png" alt="{{date("Y년 m월", strtotime($project->dev_start))}}">
                                        <span>{{date("Y년 m월", strtotime($project->dev_start))}}</span>
                                    </div>
                                    <div class="lang">
                                        {{explode("|", $project->main_lang)[0]}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-center">
                            추가된 프로젝트가 없습니다.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    <button class="refresh-btn">
        <img src="/assets/images/icons/reload.png" alt="새로고" class="no-mouse">
    </button>
@endsection
