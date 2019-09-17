@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/view.css">
@endpush

@section('contents')
    <div id="project-view" class="outside mt-5">
        <div class="inside">
            <div class="w-100 infomation">
                <div class="image-wrap w-50 f-left" style="background-color: {{$project->back_color}};">
                    <div class="image" style="background-image: url(/files/projects/{{$project->saved_folder}}/{{$project->thumbnail}})"></div>
                </div>
                <div class="info w-50">
                    <div class="flex">
                        <div class="info-group w-50">
                            <span class="col">프로젝트 명</span>
                            <span class="val" style="color: {{$project->font_color}}">{{$project->title}}</span>
                        </div>
                        <div class="info-group w-50">
                            <span class="col">개발 일자</span>
                            <span class="val">
                                {{date("Y년 m월 d일", strtotime($project->dev_start))}} ~ {{date("Y년 m월 d일", strtotime($project->dev_end))}}
                            </span>
                        </div>
                    </div>
                    <div class="info-group">
                        <span class="col">주 사용 언어</span>
                        <div id="main-lang" class="vals">
                            @foreach ($project->main_lang as $lang)
                                <span class="val">{{$lang}}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="info-group">
                        <div id="hash-tag" class="vals">
                            @foreach ($project->hash_tag as $tag)
                                <span class="val">{{$tag}}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="button-group">
                        <a href="{{route("projects.download", [$project->id])}}" class="btn btn-color">파일 다운로드</a>
                        @if (admin())
                            <a href="{{route("projects.delete", $project->id)}}" class="btn btn-danger f-right" onclick="return confirm('정말 삭제하시겠습니까?')">프로젝트 삭제</a>
                            <a href="{{route("projects.rewrite", $project->id)}}" class="btn f-right mr-2">프로젝트 수정</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-5 w-100 description">
                <span class="col">프로젝트 소개</span>
                <p><?=nl2br(htmlentities($project->description))?></p>
            </div>
        </div>
    </div>
@endsection
