@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/view.css">
@endpush

@section('contents')
    <div id="project-view" class="outside mt-5">
        <div class="inside">
            <div class="w-100 infomation">
                <div class="info-group">
                    <span class="col title">{{$tryout->title}}</span>
                    <span class="val">
                        작성일: {{date("Y년 m월 d일", strtotime($tryout->w_date))}}&nbsp;&nbsp;&nbsp;&nbsp;조회수: {{$tryout->v_count}}
                    </span>
                </div>
                <div class="info-group">
                    <?=$tryout->contents?>
                </div>
                <div class="button-group">
                    <a href="{{route("tryouts.home")}}" class="btn f-left mr-2">목록으로</a>
                    @if (admin())
                       <a href="{{route("tryouts.rewrite", $tryout->id)}}" class="btn btn-color f-left mr-2">수정하기</a>
                        <a href="{{route("tryouts.delete", $tryout->id)}}" class="btn btn-danger f-left" onclick="return confirm('정말 삭제하시겠습니까?')">삭제하기</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection