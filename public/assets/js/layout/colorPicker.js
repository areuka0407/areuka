$(function(){
    var _mouse_possible = true;
    var c_select = "";

    var trigger = $(".color-picker");
    var picker = $("#colorPicker");
    var main_box = $(".main-select-box");
    var sub_box = $(".sub-select-box");
    var sub_canvas = $(".sub-select-box canvas");


    // Color Input
    var c_red = $("#color-red");
    var c_green = $("#color-green");
    var c_blue = $("#color-blue");
    var c_hex = $("#color-hex");
    var preview = $("#colorPicker .pre-view");


    var target_input = "";
    var target_preview = null;


    // 캔버스 기본색 지정

    fillMainGradient(255, 0, 0);
    fillSubGradient();


    // 창 여닫기
    trigger.siblings(".bar").css("border-width", parseInt(trigger.parents().css("width")) + "px");
    trigger.on("click", function(e){
        $(this).siblings(".bar").css("border-width", "0");
        picker.css("left", $(this).offset().left + parseInt($(this).css("width")) + 10 + "px");
        picker.css("top", $(this).offset().top + "px");
        picker.fadeIn(250);
        target_input = $(e.target).data("target");
        target_preview = $(e.target).attr('id');
    });
    picker.children(".close").on("click", function(e){
        $("#"+target_preview).siblings(".bar").css("border-width", parseInt($(this).parents().css("width")) + "px");
        picker.fadeOut(250);
        target_input = "";
        target_preview = null;
    });

    picker.find(".submit-btn").on("click", function(){
        $("#"+target_preview).siblings(".bar").css("border-width", parseInt($(this).parents().css("width")) + "px");
        var red = c_red.val();
        var green = c_green.val();
        var blue = c_blue.val();

        /* 선택한 Color input :: #000000 */
        $("#"+target_input).val(parseHex(red, green, blue).toLowerCase());

        /* 선택한 미리보기 :: rgb(0, 0, 0) */
        $("#"+target_preview).css("backgroundColor", parseColor(red, green, blue));

        picker.find(".close").click();
    });

    /*
    선택한 캔버스의 색상을 뽑아오는 함수
     */
    function getCursorColor(target){
        var cursor = target.find(".select");
        var canvas = target.find("canvas")[0];
        var x = parseInt(cursor.css("left"));
        var y = parseInt(cursor.css("top"));

        // 캔버스의 크기와 x, y 값이 같으면 -1
        x = canvas.width === x ? x - 1 : x;
        y = canvas.height === y ? y - 1 : y;

        var ctx =  canvas.getContext('2d');

        // sub-canvas 이면 x값은 중간값
        x = canvas.dataset.id === "sub-canvas" ? canvas.width / 2 : x;

        return getImageData(ctx, x, y);
    }

    function getImageData(ctx, x, y){
      var m_color = ctx.getImageData(x, y, 1, 1).data;
      var a = m_color[3] / 255;
      m_color[0] = Math.round((1 - a) * 255 + a * m_color[0]);
      m_color[1] = Math.round((1 - a) * 255 + a * m_color[1]);
      m_color[2] = Math.round((1 - a) * 255 + a * m_color[2]);
      return {red: m_color[0], green: m_color[1], blue: m_color[2]};
    }



    // 좌클릭시 클릭 대상을 알려줌
    main_box.on("mousedown", function(e){
        e.preventDefault();
        _mouse_possible = e.which !== 1;
        if (e.which !== 1) return;
        c_select = "main";
    });
    sub_box.on("mousedown", function(e){
        e.preventDefault();
        _mouse_possible = e.which !== 1;
        if (e.which !== 1) return;
        c_select = "sub";
    });

    // 좌클릭을 떼면 클릭 대상 초기화
    $(window).on("mouseup", function(){
        c_select = "";
    });

    // 마우스 클릭시 Color Picker 의 커서를 조종
    $(window).on("mousemove", function(e){
        var x = e.pageX, y = e.pageY;
        if (c_select !== ""){
            /*
            Mouse 이벤트 시 Select 노드 이동
             */
            var box = $("." + c_select + "-select-box");
            var select = box.children(".select");
            var offset = {
                x: box.offset().left,
                y: box.offset().top,
                w: parseInt(box.css("width")),
                h: parseInt(box.css("height"))
            };

            // 박스 내에서 이동 중이라면, 정상적으로 작동할 것임.
            x -= offset.x;
            y -= offset.y;

            x = x < 0 ? 0 : x;
            x = x > offset.w ? offset.w : x;

            y = y < 0 ? 0 : y;
            y = y > offset.h ? offset.h : y;

            setCursor(select, x, y);

            /*
            현재 main 과 sub 의 캔버스 커서에 따라
            미리보기와 main 의 색상을 지정
             */

            var main_c = getCursorColor(main_box);
            var sub_c = getCursorColor(sub_box);

            fillMainGradient(sub_c.red, sub_c.green, sub_c.blue);
            fillPreview(main_c.red, main_c.green, main_c.blue);


            /*
            Color Input 변경
             */

            update(main_c.red, main_c.green, main_c.blue);
        }

        if (_mouse_possible === false)  e.preventDefault();
    });

    /*
    Cursor 의 위치를 변경
     */
    function setCursor(cursorNode, x, y){
        var style = {top: y + "px"};
        if (c_select === "main") style.left = x + "px";
        else if(c_select === "sub" && y === sub_canvas[0].height) style.top = y-1 + "px";
        cursorNode.css(style);
    }


    /*
    모든 미리보기와 input 을 변경
     */
    function update(red, green, blue){
        /* Color Input 의 미리보기 */
        preview.css("backgroundColor", parseColor(red, green, blue));

        /* Color Input (각 R G B 마다 설정) :: 0 0 0 */
        c_red.val(red);
        c_green.val(green);
        c_blue.val(blue);

        /* Hex Input :: 000000 */
        c_hex.val(parseHex(red, blue, green).substr(1));
    }


    /*
    Color_Input 이 바뀔 때마다 미리보기
     */
    $("#color-red, #color-green, #color-blue").on("change click keyup keydown", function(e)
    {
        /* 숫자가 아닌 값이 있으면 치환 */
        e.target.value = e.target.value.replace(/[^0-9]+/, "");
        /* 3자까지만 작성 가능 (0 ~ 255) */
        e.target.value = e.target.value.substr(0, 3);

        /* 0 ~ 255 보다 크거나 작으면 0, 255로 변경 */
        e.target.value = e.target.value < 0 ? 0 : e.target.value;
        e.target.value = e.target.value > 255 ? 255 : e.target.value;

        var red = c_red.val();
        var green = c_green.val();
        var blue = c_blue.val();

        if(e.target.value) {
            update(red, green, blue);

            /*
            * 커서의 위치 변경하기
            */

            /* Sub Canvas */

            // 순색 구하기
            var um_color = [parseInt(red), parseInt(green), parseInt(blue)]
            var color, c1, c2, c3;
            c1 = [];
            c2 = [];
            c3 = [];

            var c_max = um_color.reduce(function(acc, item) { return Math.max(acc, item) })
            var c_min = um_color.reduce(function(acc, item) { return Math.min(acc, item) })

            um_color.forEach(function(item, index){
              if(c_max === item) c1.push(index);
              else if(c_min === item) c3.push(index);
              else c2.push(index);
            });

            // 명도 올리기
            var power
            power = 255 / c_max;
            for (color in um_color){
              um_color[color] = Math.round(um_color[color] * power);
            }

            console.log(um_color);
            // 채도 올리기

            /* RGB값이 모두 같은 무채색일 떄 */
            if(c1.length === 3) um_color[1] = um_color[2] = 0;
            /* RGB값 중 가장 큰 색이 같을 때  */
            else if(c1.length === 2) um_color[c2[0]] === 0;
            /* RGB 값중 가장 큰 색이 하나일 때 */
            else if(c1.length === 1){
              /* 나머지 두 RGB 값이 같을 떄 */
              if(c3.length === 2){
                c3.forEach(function(color){
                  um_color[color] = 0;
                });
              }
              /* RGB 값이 각자 모두 다를 때 */
              else {
                um_color[c2[0]] = Math.ceil(um_color[c2[0]] - ( (255 - um_color[c2[0]]) * um_color[c3[0]] / (255 - um_color[c3[0]]) ));
                um_color[c3[0]] = 0;
              }
            }
            console.log(um_color);

            /* 각 그라데이션 비율에 따라 Y값 구하기 */
            /* #f00 0% ~ #ff0 17% ~ #0f0 33% ~ #0ff 50% ~ #00f 66% ~ #f0f 83% ~ #f00 100% */
            var gradients = [
              { name: "red", offset: [0, 299] },
              { name: ["red", "green"], offset: 52 },
              { name: "green", offset: 101 },
              { name: ["green", "blue"], offset: 150 },
              { name: "blue", offset: 197 },
              { name: ["red", "blue"], offset: 248 }
            ];


          }
    });

    /*
    Color Hex (16진수 Input)이 바뀔 때마다 미리보기
     */
    c_hex.on("click keyup keydown", function(e){
        /* 16진수 내에 속하는 값이 아니면 치환 */
        this.value = this.value.replace(/[^a-fA-F0-9]+/, "");
        /* 6글자까지 허용 */
        this.value = this.value.substr(0, 6);
    });
    c_hex.on("change", function(e){
        /* 대문자로 변경 */
        this.value = this.value.toUpperCase();

        /*
        * c_code.length === 3 일 때: 1,2와 3,4와 5,6 자리를 통일
        * c_code.length === 6 일 때: 그대로
        * 그 외: #000
        */
        if(this.value.length === 3){
            var trans = "";
            trans += this.value.substr(0, 1) + this.value.substr(0, 1);
            trans += this.value.substr(1, 1) + this.value.substr(1, 1);
            trans += this.value.substr(2, 1) + this.value.substr(2, 1);
            this.value = trans;
        }
        else if(this.value.length !== 6){
            this.value = "000000";
        }

        /*
        2자리씩 잘라서 R, G, B 코드 도출
         */
        var h_red = this.value.substr(0, 2);
        var h_green = this.value.substr(2, 2);
        var h_blue = this.value.substr(4, 2);

        var d_red = hexToDec(h_red);
        var d_green = hexToDec(h_green);
        var d_blue = hexToDec(h_blue);

        update(d_red, d_green, d_blue);
    });

    /*
    색상 미리보기 변경
     */
    function fillPreview(r, g, b){
        preview.css("backgroundColor", parseColor(r, g, b));
        preview.data("red", r);
        preview.data("green", g);
        preview.data("blue", b);
    }


    /*
    R, G, B를 입력 받아서 rgb( ) 형식으로 배출
     */
    function parseColor(r, g, b){
        return "rgb(" + r + ", " + g + ", " + b + ")";
    }

    /*
    RGB 값을 입력 받아서 hex 코드로 변환
     */
    function parseHex(r, g, b){
        var result = "#";
        var hex = {
            red: decToHex(r),
            green: decToHex(g),
            blue: decToHex(b)
        };

        result += hex.red.length < 2 ? '0' + hex.red : hex.red;
        result += hex.green.length < 2 ? '0' + hex.green : hex.green;
        result += hex.blue.length < 2 ? '0' + hex.blue : hex.blue;
        return result;
    }

    function hexToDec(hex){
        var i = 0, result = 0;
        var hexArr = hex.split('');
        var item;

        while(hexArr.length !== 0){
            item = hexArr.pop();
            item = item === 'A' ? 10 : item;
            item = item === 'B' ? 11 : item;
            item = item === 'C' ? 12 : item;
            item = item === 'D' ? 13 : item;
            item = item === 'E' ? 14 : item;
            item = item === 'F' ? 15 : item;
            result += Math.pow(16, i) * item;
            i++;
        }
        return result;
    }

    /*
    10진수를 입력받아 16진수로 변환
     */
    function decToHex(dec){
        dec = parseInt(dec);
        /*
        * result: 16진수 결과값
        * temp: 임시 저장 변수
         */
        var result = "", temp;
        do {
            temp = dec % 16 + '';
            temp = temp == 10 ? "A" : '' + temp;
            temp = temp == 11 ? "B" : '' + temp;
            temp = temp == 12 ? "C" : '' + temp;
            temp = temp == 13 ? "D" : '' + temp;
            temp = temp == 14 ? "E" : '' + temp;
            temp = temp == 15 ? "F" : '' + temp;
            result += temp;
            dec = parseInt(dec / 16);
        } while ( dec !== 0 );

        return result.split('').reverse().join('');
    }

    /*
    Main Canvas 에 그라데이션 채우기
    */
    function fillMainGradient(r, g, b){
        var target = document.querySelector("#colorPicker .main-select-box canvas");
        var ctx = target.getContext('2d');
        ctx.clearRect(0, 0, target.width, target.height);

        var grd; // 그라데이션 변수

        /* 메인 색상 */
        grd = ctx.createLinearGradient(0, 0, target.width, 0);
        grd.addColorStop(0.01, "rgba(" + r + ", " + g + ", " + b + ", 0)");
        grd.addColorStop(0.99, "rgba(" + r + ", " + g + ", " + b + ", 1)");
        ctx.fillStyle = grd;
        ctx.fillRect(0, 0, target.width, target.height);

        /* to bottom, transparent, rgba(0, 0, 0, 1) 100% */
        grd = ctx.createLinearGradient(0, 0, 0, target.height);
        grd.addColorStop(0.01, "rgba(0, 0, 0, 0)");
        grd.addColorStop(0.99, "rgba(0, 0, 0, 1)");
        ctx.fillStyle = grd;
        ctx.fillRect(0, 0, target.width, target.height);
    }

    /*
    Sub Canvas 에 그라데이션 채우기
     */
    function fillSubGradient(){
        var target = document.querySelector("#colorPicker .sub-select-box canvas");
        var ctx = target.getContext("2d");

        var grd; // 그라데이션 변수
        /* to bottom, red 0%, #ff0 17%, lime 33%, cyan 50%, blue 66%, #f0f 83%, red 100% */
        grd = ctx.createLinearGradient(0,  0, 0, target.height);
        grd.addColorStop(0.005, "#f00");
        grd.addColorStop(0.17, "#ff0");
        grd.addColorStop(0.175, "#ff0"); // +
        grd.addColorStop(0.33, "#0f0");
        grd.addColorStop(0.335, "#0f0"); // +
        grd.addColorStop(0.5, "#0ff");
        grd.addColorStop(0.505, "#0ff"); // +
        grd.addColorStop(0.66, "#00f");
        grd.addColorStop(0.665, "#00f"); // +
        grd.addColorStop(0.83, "#f0f");
        grd.addColorStop(0.835, "#f0f"); // +
        grd.addColorStop(0.995, "#f00");
        ctx.fillStyle = grd;
        ctx.fillRect(0, 0, target.width, target.height);
    }
});
