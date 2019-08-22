$(function(){
    var submit_ready = {
        "#user_id": false,
        "#user_pw": false,
        "#confirm": false,
        "#user_email": false,
        "#user_name": false
    };

    // 유저 아이디가 이미 존재하는지 확인
    $("#user_id").on("change", checkUserId);
    function checkUserId(){
        var user_id = $("#user_id").val();

        var err1 = errorMessage("#user_id", user_id.trim() === "", "아이디를 입력해 주세요.");
        if (err1) return;

        /*
        알파벳 소문자, 아라비아 숫자가 포함되어 있으면 True
        그렇지 않으면 False
         */

        var regex1 = /^([a-z0-9]+)$/;
        var err2 =  errorMessage("#user_id", !regex1.test(user_id), "아이디는 영문 소문자와 숫자로만 구성되어야 합니다.");
        if (err2) return;

        var regex2 = /^([0-9]+)$/;
        var err3 = errorMessage("#user_id", regex2.test(user_id), "아이디는 숫자로만 작성할 수 없습니다.");
        if (err3) return;

        $.getJSON("/users/id/"+user_id, function(res){
            errorMessage("#user_id", res.exist, "이미 해당 아이디가 존재합니다.");
        });
    }


    // 비밀번호 조홥 확인
    $("#user_pw").on("change", checkPassword);
    function checkPassword(){
        var password = $("#user_pw").val();
        var err1 = errorMessage("#user_pw", password.trim() === "", "비밀번호를 입력해 주세요.");
        if (err1) return;
        /*
        알파벳 대문자와 소문자와 아라비아 숫자가 둘 다 포함되면서 8자 이상이면 True
         그렇지 않은 경우엔 False 를 반환해야 한다.
         */
        var regex = /^(?=.*[a-zA-Z])(?=.*[0-9])(.{8,})$/;
        var err2 = errorMessage("#user_pw", !regex.test(password) || password.length < 8, "비밀번호는 영문/숫자 조합으로 8자 이상 작성해 주십시오.")
        if (err2) return;

        var confirm = $("#confirm").val();
        if(confirm !== "")
            errorMessage("#confirm", password !== confirm, "비밀번호와 비밀번호 확인이 일치하지 않습니다.");
    }

    // 비밀번호 재확인
    $("#confirm").on("change", checkconfirm);
    function checkconfirm(){
        var password = $("#user_pw").val();
        var confirm = $("#confirm").val();
        if(!password) return;
        errorMessage("#confirm", password !== confirm, "비밀번호화 비밀번호 재확인이 일치하지 않습니다.");
    }


    // 이메일 양식 확인
    $("#user_email").on("change", checkUserEmail);
    function checkUserEmail(){
        var email = $("#user_email").val();
        var err1 = errorMessage("#user_email", email.trim() === "", "이메일을 입력해 주세요.");
        if (err1) return;

        var regex = /^([a-zA-Z0-9-_]+)@([a-zA-Z0-9-_]+)\.([a-zA-Z0-9-_]{3,4})$/;
        errorMessage("#user_email", !regex.test(email), "정확한 이메일 주소를 작성해 주시기 바랍니다.");
    }

    // 닉네임 확인
    $("#user_name").on("change", checkUserName);
    function checkUserName(){
        var name = $("#user_name").val();

        var err1 = errorMessage("#user_name", name.trim() === "", "아이디를 입력해 주세요.");
        if (err1) return;

        var regex = /^([0-9]+)$/;
        errorMessage("#user_name", regex.test(name), "닉네임은 숫자로만 작성할 수 없습니다.");
    }


    // Submit 전송 확인
    $("#join-form").on("submit", function(e){
        checkUserId();
        checkPassword();
        checkconfirm();
        checkUserEmail();
        checkUserName();

        var result = true;
        for(var propName in submit_ready){
            result = result && submit_ready[propName];
        }
        if (!result){
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    //Functions


    // 에러 메세지 발생

    var success_msg = {
        "#user_id": "멋진 아이디네요!",
        "#user_pw": "좋은 비밀번호 같아요!",
        "#confirm": "비밀번호와 동일해요!",
        "#user_email": "사용할 수 있는 이메일이에요!",
        "#user_name": "좋은 이름이네요!"
    };

    function errorMessage(target, value, error_msg){
        // 에러 메세지 모두 삭제
        var form_box = $(target).parent();
        while( true ) {
            var next = form_box.next();
            if ( next.is("p") )
                next.remove();
            else break;
        }

        if (value) {
            // 에러 메세지 출력
            $("<p class='form-error'>· "+ error_msg +"</p>").insertAfter(form_box);

            // 회원가입 비활성화
            submit_ready[target] = false;

            $(target).focus();
        }

        else {
            // 회원가입 활성화
            $("<p class='form-success'>· "+ success_msg[target] +"</p>").insertAfter(form_box);
            submit_ready[target] = true;
        }
        return value;
    }
});
