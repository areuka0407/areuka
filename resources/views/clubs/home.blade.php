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
                <button class="post-btn f-right">
                    새 프로젝트 +
                </button>
            </div>

            {{--Section Body--}}
            <div class="section-body">
                <div class="section-time">
                    <a href="/projects/2018" class="item {{$year === "2018" ? "active" : ""}}">
                        <span>
                            <img src="/assets/images/icons/active_small_pot.png" alt="2018" class="active">
                            <img src="/assets/images/icons/small_pot.png" alt="2018">
                            2018
                        </span>
                    </a>
                    <a href="/projects/2019" class="item {{$year === "2019" ? "active" : ""}}">
                        <span>
                            <img src="/assets/images/icons/active_medium_pot.png" alt="2018" class="active">
                            <img src="/assets/images/icons/medium_pot.png" alt="2019">
                            2019
                        </span>
                    </a>
                    <a href="/projects/2020" class="item {{$year === "2020" ? "active" : ""}}">
                        <span>
                            <img src="/assets/images/icons/active_big_pot.png" alt="2018" class="active">
                            <img src="/assets/images/icons/big_pot.png" alt="2020">
                            2020
                        </span>
                    </a>
                </div>

                <div class="section-category">
                    <div class="categories f-left">
                        <div class="item active">
                            <span class="name">
                                All
                            </span>
                            <span class="count">
                                0
                            </span>
                        </div>
                        <div class="item">
                            <span class="name">
                                1과제
                            </span>
                            <span class="count">
                                0
                            </span>
                        </div>
                        <div class="item">
                            <span class="name">
                                2과제
                            </span>
                            <span class="count">
                                0
                            </span>
                        </div>
                        <div class="item">
                            <span class="name">
                                3과제
                            </span>
                            <span class="count">
                                0
                            </span>
                        </div>
                        <div class="item">
                            <span class="name">
                                4과제
                            </span>
                            <span class="count">
                                0
                            </span>
                        </div>
                    </div>
                    <div class="sort f-right">
                        <select name="" id="">
                            <option value="">제목: 오름차순</option>
                            <option value="">제목: 내림차순</option>
                            <option value="">생성된 날짜: 오름차순</option>
                            <option value="">생성된 날짜: 내림차순</option>
                        </select>
                    </div>
                </div>

                <div class="section-list">
                    <div class="section-card">
                        <div class="image" style="background-color: #EAE1B2">
                            <img src="/assets/images/cartoon.png" alt="Cartoon Viewer">
                        </div>
                        <div class="info">
                            <div class="title">Cartoon Viewer</div>
                            <div class="hash-box">
                                <span class="hash-tag" title="#웹툰_사이트">#웹툰_사이트</span>
                                <span class="hash-tag" title="#PHP_파싱_연습">#PHP_파싱_연습</span>
                                <span class="hash-tag" title="#네이버_웹툰">#네이버_웹툰</span>
                                <span class="hash-tag" title="#파싱_사이트">#파싱_사이트</span>
                            </div>
                            <div class="info-group">
                                <div class="calender">
                                    <img src="/assets/images/icons/calender.png" alt="2018년 12월">
                                    <span>2018년 12월</span>
                                </div>
                                <div class="lang">
                                    PHP
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
