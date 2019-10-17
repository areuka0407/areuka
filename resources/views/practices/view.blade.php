@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/view.css">
@endpush

@section('contents')
    <div id="project-view" class="outside mt-5">
        <div class="inside">
            <div class="w-100 infomation">
                <div class="image-wrap w-50 f-left">
                    <div class="image" style="background-image: url(/files/practices/{{$practice->saved_folder}}/{{$practice->created_no}}/{{$practice->thumbnail}})"></div>
                </div>
                <div class="info w-50">
                    <div class="info-group">
                        <span class="col">과제명</span>
                        <span class="val">{{$practice->title}}</span>
                    </div>
                    <div class="info-group">
                        <span class="col">회차 수</span>
                        <span class="val">{{number_format($practice->created_no)}}회차</span>
                    </div>
                    <div class="info-group">
                        <span class="col">개발 일자</span>
                        <span class="val">
                            {{date("Y년 m월 d일", strtotime($practice->dev_start))}} ~ {{date("Y년 m월 d일", strtotime($practice->dev_end))}}
                        </span>
                    </div>
                    <div class="button-group">
                        <a href="{{route("practices.download", [$practice->id])}}" class="btn btn-color">파일 다운로드</a>
                        @if (admin())
                            <a href="{{route("practices.delete", $practice->id)}}" class="btn btn-danger f-right" onclick="return confirm('정말 삭제하시겠습니까?')">연습기록 삭제</a>
                            <a href="{{route("practices.rewrite", $practice->id)}}" class="btn f-right mr-2">연습기록 수정</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection