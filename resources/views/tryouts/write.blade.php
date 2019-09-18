@extends("template")

@push("head")
    <link rel="stylesheet" href="/assets/css/articles/write.css">
    <script type="text/javascript" src="/assets/js/articles/write.js"></script>
    <script type="text/javascript" src="/assets/js/input.js"></script>
    <script src="https://cdn.tiny.cloud/1/l9f9vttyg8px4l55vb21nn6aisx920095glouddm2er3y6kj/tinymce/5/tinymce.min.js"></script>
    <style>
        .tox { margin-top: 10px; }
    </style>
@endpush

@push("anything")
    <script type="text/javascript">
        tinymce.init({
            selector: "#c_input",
            language: "ko_KR",
            menu: {
                format: { title: "Format", items: "forecolor backcolor" }
            },
            toolbar: 'fontsizeselect fontselect forecolor backcolor bold italic alignleft aligncenter alignright bullist numlist',
            fontsize_formats: '11px 12px 14px 16px 18px 24px 36px 48px',
            font_formats: '굴림체=굴림체,gulimche,sans-serif; 궁서체=궁서체,gungsuhche; 돋움체=돋움체,dotumche',
            icons: 'material',
            language_url: '/assets/langs/ko_KR.js',
            height: 700,
            resize: false,
            menubar: false
        });

        $("#submit-btn").on("mousedown", function(){
            const tiny = tinymce.get('c_input');
            const i_selector = document.querySelector("#"+tiny.id).dataset.target;
            document.querySelector(i_selector).value = tiny.getContent();
        });
    </script>
@endpush

@include("layout.colorPicker")
@include("layout.imageUpload")

@section("contents")
    <div id="write" class="outside">
        <div class="inside">
            <div class="form-box mt-5 mb-5">
                <div class="section-title">
                    <p>새로운 일지 작성</p>
                    <span class="design-box"></span>
                </div>
                <form method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="w-100">
                        <div class="form-group">
                            <label for="title" class="form-label">제목</label>
                            <p class="form-error inline">{{$errors->first("title")}}</p>
                            <div class="custom-input" data-name="제목" data-max="50">
                                <input type="text" id="title" name="title" placeholder="제목을 입력하세요" value="{{old("title")}}">
                                <div class="bar"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contents" class="form-label mb-3">내용</label>
                            <p class="form-error inline">{{$errors->first("contents")}}</p>
                            <input type="hidden" id="contents" name="contents">
                            <textarea id="c_input" data-target="#contents" cols="30" rows="40"></textarea>
                        </div>
                    </div>
                    <div class="w-100 pl-5">
                        <button type="submit" id="submit-btn" class="btn ml-1 f-left">등록하기</button>
                        <button type="reset" class="btn ml-3 f-left">다시쓰기</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
