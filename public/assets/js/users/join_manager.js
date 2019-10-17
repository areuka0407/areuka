window.onload = function(){
    let sideBar = document.querySelector("#join-manager");
    let fadeButton = document.querySelector("#join-manager .fade");
    let blackLayer = document.querySelector(".black-layer.join");

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
};
