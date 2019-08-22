$(function(){
    function check_file_extension(file){ return file.name.substr(file.name.indexOf(".") + 1, 3) === "zip" }

    $("#execute_file").on("change", function(e){
        var files = this.files;
        if(!check_file_extension(files[0])){
            alert("실행 파일을 zip 형식으로 압축해서 업로드 해 주세요!");
            this.value = null;
            return;
        }
    });

    $("form").on("submit", function(e){
        var dev_start = new Date($("#dev_start").val());
        var dev_end = new Date($("#dev_end").val());
        if(dev_start === '' || dev_end === ''){
            alert("개발 일시를 작성해 주세요!");
            e.preventDefault();
            return false;
        }
        if(dev_start > dev_end){
            alert("개발을 시작하기도 전에 끝낼 수는 없겠죠?");
            e.preventDefault;
            return false;
        }
    });
});
