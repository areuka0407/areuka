window.onload = function(){
    /**
     * Dom 정리
     */
    let sideBar = document.querySelector("#join-manager");
    let fadeButton = document.querySelector("#join-manager .fade");
    let blackLayer = document.querySelector(".black-layer.join");

    if(!sideBar || !fadeButton || !blackLayer) return false;


    /**
     * 가입 신청 데이터 가져오기
     */
    let xhr = new this.XMLHttpRequest();
    xhr.open("POST", "/load/join_requests");
    xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").content);
    xhr.send();
    xhr.onload = function(){
        let data = JSON.parse(xhr.responseText);
        fadeButton.querySelector(".join-num").innerText = data.length;


        data.forEach(item => {
            // 엘레먼트 생성
            let template = `<li class="item" data-idx="${item.id}">
                                <div class="info">
                                    <span class="column">닉네임</span>
                                    <b class="name">${item.user_name}</b><br/>
                                    <span class="column">이메일</span>
                                    <b class="name">${item.user_email}</b>
                                </div>
                                <div class="button-group">
                                    <button data-type="accept">수락하기</button>
                                    <button data-type="cancel">거부하기</button>
                                </div>
                            </li>`;
            let temp = document.createElement("div");
            temp.innerHTML = template;
            template = temp.firstChild;

            // 이벤트 부여
            template.querySelector("button[data-type='accept']").addEventListener("click", e => {
                let parent = e.target.parentElement.parentElement;
                // 데이터 형성
                let data = new FormData();
                data.append("insert_id", parent.dataset.idx);
                // 가입 허락
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/join/accept");
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").content);
                xhr.send(data);
                xhr.onload = function(){
                    let res = JSON.parse(xhr.responseText);
                    if(res) parent.remove();
                    else alert("회원을 추가하는 도중 문제가 발생한 것 같습니다! 관리자에게 문의하세요!");
                }
            });

            template.querySelector("button[data-type='cancel']").addEventListener("click", e => {
                let parent = e.target.parentElement.parentElement;
                // 데이터 형성
                let data = new FormData();
                data.append("delete_id", parent.dataset.idx);
                // 가입 요청 삭제
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "/join/cancel");
                xhr.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").content);
                xhr.send(data);
                xhr.onload = function(){
                    let res = JSON.parse(xhr.responseText);
                    if(res.response) parent.remove();
                    else alert("회원을 삭제하는 도중 문제가 발생한 것 같습니다! 관리자에게 문의하세요!");
                }
            });


            // DOM 추가
            sideBar.querySelector("#join-list").append(template);
        });

        if(data.length === 0) sideBar.querySelector("#join-list").innerHTML = "<p class='mt-5'>가입 요청이 오지 않았습니다.</p>";
    }

    function fadeSidebar() {
        // 사이드 바가 열리지 않음
        if(sideBar.classList.contains("close"))
        {
            sideBar.classList.add("open");
            sideBar.classList.remove("close");

            fadeButton.classList.add("close");
            fadeButton.classList.remove("info");

            blackLayer.style.opacity = 0;
            blackLayer.style.display = "block";

            let opacity = 0;
            const fade = setInterval(() => {
                blackLayer.style.opacity = opacity;
                opacity += 0.05;
                if(opacity >= 1) clearInterval(fade);
            }, 20);
        }
        // 사이드 바가 열려 있음
        else
        {
            sideBar.classList.add("close")
            sideBar.classList.remove("open");

            fadeButton.classList.add("info");
            fadeButton.classList.remove("close");

            blackLayer.style.opacity = 1;

            let opacity = 1;
            const fade = setInterval(() => {
                blackLayer.style.opacity = opacity;
                opacity -= 0.05;
                if(opacity <= 0) {
                    clearInterval(fade);
                    blackLayer.style.display = "none";
                }
            }, 20);
        }
    }

    fadeButton.addEventListener("click", fadeSidebar);
    blackLayer.addEventListener("click", fadeSidebar);
};
