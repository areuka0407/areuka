@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/write.css">
    <script type="text/javascript" src="/assets/js/articles/write.js"></script>
    <script type="text/javascript" src="/assets/js/input.js"></script>
@endpush

@include("layout.colorPicker")
@include("layout.imageUpload")

@section("contents")
    <div id="write" class="outside">
        <div class="inside">
            <div class="form-box mt-5 mb-5">
                <div class="section-title">
                    <p>새로운 연습활동 등록</p>
                    <span class="design-box"></span>
                </div>
                <form action="{{route("practices.update")}}" method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="w-100 f-left">
                        <div class="f-left w-50">
                            <div class="form-group">
                                <label for="title" class="form-label">대회문제 명</label>
                                <p class="form-error inline">{{$errors->first('title')}}</p>
                                <div class="custom-input">
                                    <input type="text" id="title" name="title" placeholder="등록할 연습활동의 제목을 작성해 주세요!" value="{{old("title")}}">
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>연습 시작</label>
                                <p class="form-error inline">{{$errors->first("dev_start")}}</p>
                                @php
                                    $date = explode("-", old("dev_start") ? old("dev_start") : "--");
                                    $year = $date[0];
                                    $month = $date[1];
                                    $date = $date[2];
                                @endphp
                                <div class="custom-date mt-2">
                                    <input type="hidden" id="dev_start" class="value" name="dev_start">
                                    <div class="year">
                                        <input type="number" min="2018" max="2020" value="{{$year}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">년</div>
                                    <div class="month">
                                        <input type="number" min="1" max="12" value="{{$month}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">월</div>
                                    <div class="date">
                                        <input type="number" min="1" max="31" value="{{$date}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">일</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>연습 종료</label>
                                <p class="form-error inline">{{$errors->first("dev_end")}}</p>
                                @php
                                    $date = explode("-", old("dev_end") ? old("dev_end") : "--");
                                    $year = $date[0];
                                    $month = $date[1];
                                    $date = $date[2];
                                @endphp
                                <div class="custom-date mt-2">
                                    <input type="hidden" id="dev_end" class="value" name="dev_end">
                                    <div class="year">
                                        <input type="number" min="2018" max="2020" value="{{$year}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">년</div>
                                    <div class="month">
                                        <input type="number" min="1" max="12" value="{{$month}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">월</div>
                                    <div class="date">
                                        <input type="number" min="1" max="31" value="{{$date}}">
                                        <div class="bar"></div>
                                    </div>
                                    <div class="suffix">일</div>
                                </div>
                            </div>
                        </div>
                        <div class="f-left w-50">
                            <div class="form-group">
                                <label for="thumbnail" class="form-label">섬네일 이미지</label>
                                <p class="form-error inline">{{$errors->first('thumbnail')}}</p>
                                <label for="thumbnail" id="preview-thumbnail" class="preview-thumbnail mt-2"></label>
                                <input type="file" id="thumbnail" class="possible-preview" name="thumbnail" data-preview="preview-thumbnail" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="root" class="form-label">파일 시작 경로</label>
                                <p class="form-error inline">{{$errors->first('root')}}</p>
                                <div class="custom-input">
                                    <input type="text" id="root" name="root" placeholder="프로젝트의 시작 경로를 작성해 주세요!" value="{{old("root") ? old("root") : "/index.php"}}">
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="execute_file" class="form-label">실행 파일</label>
                                <p class="form-error inline">{{$errors->first('execute_file')}}</p>
                                <div class="custom-file mt-2">
                                    <label for="execute_file" class="upload-btn"></label>
                                    <label for="execute_file" onclick="alert('zip 확장자 파일만 업로드 가능하니 참고하시기 바랍니다.')">해당 문제의 풀이 파일을 업로드 하세요!</label>
                                    <input type="file" id="execute_file" name="execute_file">
                                    <div class="bar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 pr-5">
                        <button type="reset" class="btn f-right mr-1">다시쓰기</button>
                        <button type="submit" class="btn f-right mr-3">등록하기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
