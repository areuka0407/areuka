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
                    <p>기존 프로젝트 수정</p>
                    <span class="design-box"></span>
                </div>
                <form method="post" autocomplete="off" enctype="multipart/form-data">
                    @csrf
                    <div class="w-100">
                        <div class="f-left w-50">
                            <div class="form-group">
                                <label for="title" class="form-label">프로젝트 명</label>
                                <p class="form-error inline">{{$errors->first('title')}}</p>
                                <div class="custom-input">
                                    <input type="text" id="title" name="title" placeholder="당신의 멋진 프로젝트의 이름을 작성해 주세요!" value="{{old("title") ? old("title") : $project->title}}">
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="main-lang" class="form-label">주 사용 언어</label>
                                <p class="form-help inline">가장 첫번째로 작성한 언어가 가장 먼저 노출됩니다.</p>
                                <p class="form-error">{{$errors->first('main_lang')}}</p>
                                <div class="tag-box mt-2">
                                    <input type="hidden" class="value" name="main_lang" value="{{old("hash_tag") ? old("main_lang") : implode("|", $project->main_lang)}}">
                                    <div class="input-area">
                                        @if(old('main_lang'))
                                            @foreach(explode("|", old("main_lang")) as $item)
                                                <span class='v_tag'>{{$item}}</span>
                                            @endforeach
                                        @else
                                            @foreach ($project->main_lang as $item)
                                                <span class='v_tag'>{{$item}}</span>
                                            @endforeach
                                        @endif
                                        <input type="text" id="main-lang" class="input" placeholder="주_사용_언어" data-name="언어">
                                    </div>
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="description" class="form-label">프로젝트 설명</label>
                                <p class="form-error inline">{{$errors->first('description')}}</p>
                                <div class="custom-textarea mt-2">
                                    <textarea name="description" id="description" placeholder="프로젝트에 대한 간단한 설명을 작성해 주세요!">{{old("description") ? old("description") : $project->description}}</textarea>
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="execute_file" class="form-label">실행 파일</label>
                                <p class="form-help inline">업로드를 하지 않으면 기존 파일이 그대로 유지 됩니다.</p>
                                <p class="form-error">{{$errors->first('execute_file')}}</p>
                                <div class="custom-file mt-2">
                                    <label for="execute_file" class="upload-btn"></label>
                                    <label for="execute_file">/files{{$project->saved_folder}}/compress.zip</label>
                                    <input type="file" id="execute_file" name="execute_file">
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>개발 시작</label>
                                <p class="form-error inline">{{$errors->first("dev_start")}}</p>
                                @php
                                    $date = explode("-", old("dev_start") ? old("dev_start") : $project->dev_start);
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
                        </div>
                        <div class="f-left w-50">
                            <div class="form-group">
                                <label for="thumbnail" class="form-label">섬네일 이미지</label>
                                <p class="form-error inline">{{$errors->first('thumbnail')}}</p>
                                <label for="thumbnail" id="preview-thumbnail" class="preview-thumbnail mt-2" {{is_file(public_path("files".DS."Projects".DS.$project->saved_folder.DS.$project->thumbnail)) ? "style=background-image:url('/files/Projects/{$project->saved_folder}/{$project->thumbnail}');background-size:contain;" : ""}}></label>
                                <input type="file" id="thumbnail" class="possible-preview" name="thumbnail" data-preview="preview-thumbnail" accept="image/*">
                            </div>
                            <div class="form-group flex">
                                <div class="flex items-bottom mr-5">
                                    <label for="back-color" class="form-label mr-2">배경색</label>
                                    <div class="color-picker-wrap">
                                        <div id="back-preview" class="color-picker mr-5" style="background-color: {{old("back_color") ? old("back_color") : $project->back_color}}" data-target="back-color"></div>
                                        <div class="bar"></div>
                                    </div>
                                    <input type="color" id="back-color" name="back_color" value="{{old("back_color") ? old("back_color") : "#ffffff"}}">
                                </div>
                                <div class="flex items-bottom ml-5">
                                    <label for="font-preview" class="form-label mr-2">글자색</label>
                                    <div class="color-picker-wrap">
                                        <div id="font-preview" class="color-picker mr-5" style="background-color: {{old("font_color") ? old("font_color") : $project->font_color}}" data-target="font-color"></div>
                                        <div class="bar"></div>
                                    </div>
                                    <input type="color" id="font-color" name="font_color" value="{{old("font_color") ? old("font_color") : $project->font_color}}">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label for="hash-tag" class="form-label">해시 태그</label>
                                <p class="form-error inline">{{$errors->first('hash_tag')}}</p>
                                <div class="tag-box mt-2">
                                    <input type="hidden" class="value" name="hash_tag" value="{{old("hash_tag") ? old("hash_tag") : implode("|", $project->hash_tag)}}">
                                    <div class="input-area">
                                        @if(old('hash_tag'))
                                            @foreach(explode("|", old("hash_tag")) as $item)
                                                <span class='v_tag'>{{$item}}</span>
                                            @endforeach
                                        @else
                                            @foreach($project->hash_tag as $item)
                                                <span class='v_tag'>{{$item}}</span>
                                            @endforeach
                                        @endif
                                        <input type="text" id="hash-tag" class="input" placeholder="#해시태그" data-name="해시태그">
                                    </div>
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="root" class="form-label">프로젝트 시작 경로</label>
                                <p class="form-error inline">{{$errors->first('root')}}</p>
                                <div class="custom-input">
                                    <input type="text" id="root" name="root" placeholder="프로젝트의 시작 경로를 작성해 주세요!" value="{{old("root") ? old("root") : $project->root}}">
                                    <div class="bar"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label>개발 종료</label>
                                    <p class="form-error inline">{{$errors->first("dev_end")}}</p>
                                    @php
                                        $date = explode("-", old("dev_end") ? old("dev_end") : $project->dev_end);
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
