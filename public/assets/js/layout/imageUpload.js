$(function(){
    function sel(selector){ return document.querySelectorAll(selector).length > 1 ? document.querySelectorAll(selector) : document.querySelector(selector); }
    function check_file_size(file){ return file.size <= 1024 * 1024 * 2; } // 2MB까지 업로드 가능
    function check_file_image(file){ return file.type.substr(0, 5) === "image" }
    function check_file_extension(file){
        var exts = ["jpg", "jpeg", "png", "gif"];
        return exts.reduce(function(acc, x){ return acc === true || file.type.substr(6).toLowerCase() === x });
    }

    /* 이미지 자르기 */
    // var clipImage = sel("#clipImage");
    // var c_ctx = clipImage.getContext('2d');

    $(".possible-preview").on("change", function(e){
        var files = e.target.files;
        var preview = sel( "#" + e.target.dataset.preview );

        /* 파일 업로드 제한 */
        for(var i = 0; i < files.length; i++){
            /* 파일 업로드 제한 :: 이미지 파일 */
            if(!check_file_image(files[i])){
                alert("이미지 파일만 업로드할 수 있습니다.");
                e.target.value = null;
                return;
            }

            /* 이미지 용량 제한 :: 2MB */
            if(!check_file_size(files[i])) {
                alert("이미지 파일은 최대 2MB까지만 업로드 가능합니다.");
                e.target.value = null;
                return;
            }

            /* 파일 확장자 검사 :: jpg, png, gif, jpeg */
            if(!check_file_extension(files[i])){
                alert("이미지 파일은 .jpg, .jpeg, .png, .gif 확장자 파일만 업로드할 수 있습니다.");
                e.target.value = null;
                return;
            }
        }

        /* 이미지 미리보기 */
        var reader = new FileReader();
        reader.readAsDataURL(files[0]);
        reader.onload = function(){
            var img = new Image();
            img.src = reader.result;
            img.onload = function(){
                preview.style.backgroundImage = "url(" + img.src + ")";
                if(img.width > img.height) preview.style.backgroundSize = "100% auto";
                else preview.style.backgroundSize = "auto 100%";
            }
        };
    });
});
