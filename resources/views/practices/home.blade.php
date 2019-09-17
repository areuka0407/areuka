@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/list.css">
    <script src="/assets/js/articles/list.js" type="text/javascript"></script>
@endpush

@section("contents")
    <div class="outside list-container">
        <div class="inside">

            {{--Section Header--}}
            <div class="section-header">
                <div class="section-title">
                    <span>
                        <div class="design-box"></div>
                        기능대회 준비
                    </span>
                </div>
                <form id="search" onsubmit="return false" autocomplete="off">
                    <input type="text" id="search-input" class="search-bar" placeholder="검색어를 입력하세요.">
                    <button class="search-btn" data-input="search-input"></button>
                </form>
                @if (admin())
                    <a href="{{ route("practices.write")  }}" class="post-btn f-right">
                        연습활동 추가 +
                    </a>
                @endif
            </div>

            {{--Section Body--}}
            <div class="section-body">
                <div class="section-time f_select" data-key="dev_start">
                    <button class="item f_option{{$condition->year == 2018 ? " active" : ""}}" data-value="^2018.*" data-year="2018" onclick="location.assign('{{route('practices.home', 2018)}}')">
                        <span class="no-mouse">
                            <img src="/assets/images/icons/active_small_pot.png" alt="2018" class="active">
                            <img src="/assets/images/icons/small_pot.png" alt="2018">
                            2018
                        </span>
                    </button>
                    <button class="item f_option{{$condition->year == 2019 ? " active" : ""}}" data-value="^2019.*" data-year="2019" onclick="location.assign('{{route('practices.home', 2019)}}')">
                        <span class="no-mouse">
                            <img src="/assets/images/icons/active_medium_pot.png" alt="2019" class="active">
                            <img src="/assets/images/icons/medium_pot.png" alt="2019">
                            2019
                        </span>
                    </button>
                    <button class="item f_option{{$condition->year == 2020 ? " active" : ""}}" data-value="^2020.*" data-year="2020" onclick="location.assign('{{route('practices.home', 2020)}}')">
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
                @endphp
                <div class="section-category">
                    <div class="categories f-left f_select" data-key="title">
                        <div class="item f_option{{$condition->category == "%_%" ? " active" : ""}}" data-value="%_%" onclick="location.assign('{{route('practices.home', $condition->year)}}')">
                            <span class="name no-mouse">
                                All
                            </span>
                            <span class="count no-mouse">
                                {{array_reduce($categories, "catSum", 0)}}
                            </span>
                        </div>
                        @foreach ($categories as $item)
                            <div class="item f_option{{preg_match($condition->category, $item->title) ? " active" : ""}}" data-value="%{{$item->title}}%" onclick="location.assign('{{route('practices.home', $condition->year)}}?{{http_build_query(array_merge($_GET, ['category'=>$item->title]))}}')">
                                <div class="name no-mouse">
                                    {{$item->title}}
                                </div>
                                <div class="count no-mouse">
                                    {{$item->cnt}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="sort f-right">
                        <select class="o_select">
                            <option data-key="title" value="0">제목: 오름차순</option>
                            <option data-key="title" value="1">제목: 내림차순</option>
                            <option data-key="dev_start" value="0">생성된 날짜: 오름차순</option>
                            <option data-key="dev_start" value="1">생성된 날짜: 내림차순</option>
                            <option data-key="main_lang" value="0">주 사용 언어: 오름차순</option>
                            <option data-key="main_lang" value="1">주 사용 언어: 내림차순</option>
                        </select>
                    </div>
                </div>
                <div class="section-list">
                </div>
            </div>
        </div>
    </div>
    <button class="refresh-btn">
        <img src="/assets/images/icons/reload.png" alt="새로고" class="no-mouse">
    </button>
@endsection
