window.onload = function(){
    /*
    연도 Hover 시 활성화 / 비활성화 설정
    */
    $(".section-time .item").hover(e => active_year(e.target), e => deactive_year(e.target));
    function active_year(target){
        if(target.classList.contains("active")) return false;
        var active_box = $(".section-time .item.active");
        if (active_box.is(":animated") || $(target).is(":animated")) return false;

        active_box.css({
            width: "calc((100% - 40px) / 3)",
            height: "100px",
            lineHeight: "100px",
            backgroundColor: "#4D6199"
        });
        active_box.find("span").css({
            color: "#fff",
            fontSize: "1.8rem"
        });
        active_box.find("img:not(.active)").css({opacity: "1", width: "40px", height: "40px"});
        active_box.find("img.active").css({opacity: "0", width: "40px", height: "40px"});

        $(target).css({
            width: "calc((100% - 20px) / 3)",
            height: "120px",
            lineHeight: "120px",
            backgroundColor: "#fff"
        });
        $(target).find("span").css({
            color: "#79B52C",
            fontSize: "2rem"
        });
        $(target).find("img:not(.active)").css({opacity: "0", width: "50px", height: "50px"});
        $(target).find("img.active").css({opacity: "1", width: "50px", height: "50px"});

    }

    function deactive_year(target){
        if(target.classList.contains("active")) return false;
        var active_box = $(".section-time .item.active");

        if (active_box.is(":animated") || $(target).is(":animated")) return false;

        active_box.css({
            width: "calc((100% - 20px) / 3)",
            height: "120px",
            lineHeight: "120px",
            backgroundColor: "#fff"
        });
        active_box.find("span").css({
            color: "#79B52C",
            fontSize: "2rem"
        });
        active_box.find("img:not(.active)").css({opacity: "0", width: "50px", height: "50px"});
        active_box.find("img.active").css({opacity: "1", width: "50px", height: "50px"});

        $(target).css({
            width: "calc((100% - 40px) / 3)",
            height: "100px",
            lineHeight: "100px",
            backgroundColor: "#4D6199"
        });
        $(target).find("span").css({
            color: "#fff",
            fontSize: "1.8rem"
        });
        $(target).find("img:not(.active)").css({opacity: "1", width: "40px", height: "40px"});
        $(target).find("img.active").css({opacity: "0", width: "40px", height: "40px"});
    }

    /*
    정렬 이동
    */

    $(".o_select").on("change", e => selectOrder(e));
    function selectOrder(e){
        let key = e.target.children[e.target.selectedIndex].dataset.key;
        let value = e.target.value === "0" ? "ASC" : "DESC";
        let addString = "order=" + key + "-" + value;
        if(key.indexOf("-") !== -1 || value.indexOf("-") !== -1) return; // 오류 방지

        let qs = location.search;
        // 쿼리스트링이 존재하지 않는가?
        if(qs.trim().length == 0) location.assign(location.pathname + "?" + addString); // 기존 URL에 바로 삽입
        else {
            // 정렬 항목이 포함되어 있나?
            if(qs.match(/^\?order=([^&]+)/) !== null || qs.match(/&order=([^&]+)/) !== null)
                qs = qs.replace(/order=([^&]+)/, addString); // 정렬 항목 변경
            else
                qs += "&" + addString; // 아니면 새로 추가
            location.assign(location.pathname + qs);
        }
    }
}


/* 데이터 로드 속도는 빠르지만, 자잘한 버그가 많아서 사용 보류  */

// window.onload = function(){
//     /**
//      * Process
//      */

//     var path = location.pathname.split("/")[1]; // 현재 선택한 페이지
//     var categories;
//     var filter;
//     var order;
//     var dataList;

//     (async function process(){
//         init();
//         await loadLocalStorage()
//         await eventTrigger();
//         rendering();
//     })();

//     /**
//     * Functions
//     */

//     /*
//     DOM 선택 (select)
//     */
//     function sel(target){
//         return document.querySelectorAll(target).length > 1 ? document.querySelectorAll(target) : document.querySelector(target);
//     }

//     /*
//     DOM 생성 (create)
//     ! div 태그 내에 존재할 수 있는 태그만 가능 !
//     */
//     function crt(textNode){
//         let parents = document.createElement("div");
//         parents.innerHTML = textNode;
//         return parents.firstChild;
//     }

//     /*
//     초기 설정
//     */
//     function init(){
//         sel(".f_select[data-key='main_lang']").innerHTML = '';
//         sel(".section-list").innerHTML = '';
//     }

//     /*
//     랜더링 함수
//     */
//     function rendering(){
//         /* 연도 설정 */
//         let s_year = filter.find(x => x.key === "dev_start").value;
//         let s_cat = filter.find(x => x.key === "main_lang").value;

//         /* 해당 카테고리가 연도 내에 존재하지 않으면 필터 조건 삭제 */
//         if(!categories.find(x => new RegExp(s_year).test(x.year)).data.find(x => new RegExp(s_cat).test(x.name))){
//             filter.find(x => x.key === "main_lang").value = ".+";
//             s_cat = ".+";
//         }

//         for(let elem of sel(".f_select[data-key='dev_start']").children){
//             if(elem.dataset.value === s_year) {
//                 elem.classList.add("active");
//                 active_year(elem);
//             }
//             else {
//                 elem.classList.remove("active");
//                 deactive_year(elem);
//             }
//         }

//         /* 카테고리 추가 */
//         let cat_div = sel(".f_select[data-key='main_lang']");
//         let all_cnt = categories.find(x => new RegExp(s_year).test(x.year));
//         all_cnt = all_cnt.data.length ? all_cnt.data.map(i => i.count).reduce((a, x) => a + x) : 0;
//         cat_div.innerHTML = '';
        // let s_all = `<div class="item ${s_cat === ".+" ? "active " : ""}f_option" data-value=".+">
        //                 <span class="name no-mouse">
        //                     All
        //                 </span>
        //                 <span class="count no-mouse">
        //                     ${all_cnt}
        //                 </span>
        //             </div>`;
//         s_all = crt(s_all);
//         s_all.addEventListener("click", e => setFilter(e));
//         cat_div.append(s_all);

//         categories.find(x => new RegExp(s_year).test(x.year)).data.forEach(x => {
//             let cat = `<div class="item ${new RegExp(s_cat).test(x.name) && s_cat !== ".+" ? "active " : ""}f_option" data-value="^${x.name}$">
//                             <span class="name no-mouse">
//                                 ${x.name}
//                             </span>
//                             <span class="count no-mouse">
//                                 ${x.count}
//                             </span>
//                         </div>`;
//             cat = crt(cat);
//             cat.addEventListener("click", e => setFilter(e));
//             cat_div.append(cat);
//         });

//         /* 정렬 select 설정 */
//         sel(".o_select > option[data-key='"+order.key+"'][value='"+order.value+"']").selected = true;

//         /* 검색 메세지 설정 */
//         let keyword = filter.find(x => x.key === "title");
//         sel("input.search-bar").value = keyword ? keyword.value.substr(3, keyword.value.length - 6) : "";

//         /* 글 로드 */
//         let view_list = JSON.parse(JSON.stringify(dataList));

//         /* 필터링 */
//         filter.forEach(item => {
//             if(view_list.find(x => x[item.key]) == true)
//             {
//                 view_list = view_list.filter(x => new RegExp(item.value).test(x[item.key]))
//             }
//         });


//         /* 글 정렬 */
//         view_list = view_list.sort((a, b) => {
//             if(order.key === "dev_start"){
//                 return order.value == 0 ? new Date(a.dev_start) - new Date(b.dev_start) : new Date(b.dev_start) - new Date(a.dev_start);
//             }
//             if(order.key === "title"){
//                 return order.value == 0 ? a.title.localeCompare(b.title) : b.title.localeCompare(a.title);
//             }
//             if(order.key === "main_lang"){
//                 return order.value == 0 ? a.main_lang.localeCompare(b.main_lang) : b.main_lang.localeCompare(a.main_lang);
//             }

//         });

//         /* View */
//         var view_div = sel(".section-list");
//         view_div.innerHTML = '';
//         view_list.forEach(x => {
//             let dev_date = new Date(x.dev_start);
            // let view_node = `<a href="/${path}/view/${x.id}" class="section-card">
            //                     <div class="image" style="background-color: ${x.back_color}">
            //                         <img src="/files/${path[0].toUpperCase() + path.substr(1)}/${x.saved_folder}/${path === "practices" ? x.created_no + "/" : ""}${x.thumbnail}" alt="${x.title}">
            //                     </div>
            //                     <div class="info">
            //                         <div class="title" style="color: ${x.font_color}">${x.title}</div>
            //                         <div class="hash-box">`;
            // if(path === "projects")
            //     x.hash_tag.forEach(tag => view_node += `<span class="hash-tag" title="${tag}">${tag}</span>`);
            // view_node +=        `</div>
            //                         <div class="info-group">
            //                             <div class="calender">
            //                                 <img src="/assets/images/icons/calender.png" alt="${dev_date.getFullYear()}년 ${dev_date.getMonth() + 1}월">
            //                                 <span>${dev_date.getFullYear()}년 ${dev_date.getMonth() + 1}월</span>
            //                             </div>
            //                             <div class="lang">
            //                                 ${path === "practices" ? x.created_no + "회차" : x.main_lang}
            //                             </div>
            //                         </div>
            //                     </div>
            //                 </a>`;
//             view_div.append(crt(view_node));
//         });
//         if(view_list.length === 0) view_div.innerHTML = `<p class="bold text-center w-100">아직 등록된 프로젝트가 없습니다.</p>`;


//         localStorage.setItem("filter", JSON.stringify(filter));
//         localStorage.setItem("order", JSON.stringify(order));
//         localStorage.setItem("dataList", JSON.stringify(dataList));
//         localStorage.setItem("categories", JSON.stringify(categories));
//     }
//     /*
//     LocalStorage 로드 후 재저장
//     */
//     async function loadLocalStorage(){
//         filter = JSON.parse(localStorage.getItem("filter"));
//         order = JSON.parse(localStorage.getItem("order"));
//         dataList = JSON.parse(localStorage.getItem("dataList"));
//         categories = JSON.parse(localStorage.getItem("categories"));

//         filter = filter || [
//             {key: "dev_start", value: "^2018.*"},
//             {key: "main_lang", value: ".+"}
//         ];

//         /* filter에 dev_start / main_lang 이 없으면 추가 */
//         if(!filter.find(x => x.key === "dev_start"))
//             filter.push({key: "dev_start", value: "^2018.*"});
//         if(!filter.find(x => x.key === "main_lang"))
//             filter.push({key: "main_lang", value: ".+"});


//         order = order || {key: "title", value : 0};
//         dataList = dataList || await loadData();
//         categories = categories || await loadCategories();

//         localStorage.setItem("filter", JSON.stringify(filter));
//         localStorage.setItem("order", JSON.stringify(order));
//         localStorage.setItem("dataList", JSON.stringify(dataList));
//         localStorage.setItem("categories", JSON.stringify(categories));
//     }

//     /*
//     해당 테이블에서 데이터를 모두 가져오는 비동기 ajax
//     */
//     function ajax(t_table){
//         return new Promise((res, rej) => {
//             var xhr = new XMLHttpRequest();
//             var csrf = document.querySelector("meta[name='csrf-token']").content;
//             xhr.open("POST", "/load/ajax?table="+t_table);
//             xhr.setRequestHeader("X-CSRF-TOKEN", csrf);
//             xhr.send();
//             xhr.onload = () => res(JSON.parse(xhr.responseText));
//             xhr.onerror = () => rej(xhr.response);
//         });
//     };

//     /*
//     테이블에서 가져온 데이터를 재가공
//     */
//     function loadData(){
//         return new Promise((res, rej) => {
//             ajax(path)
//                 .then(data => {
//                     if(path === "projects"){
//                         data = data.map(x => {
//                             x.hash_tag = x.hash_tag.split("|").slice(0, 4);
//                             x.main_lang = x.main_lang.split("|")[0];
//                             return x;
//                         });
//                     }
//                     res(data);
//                 })
//                 .catch(err => rej(err));
//         });
//     }

//     /*
//     프로젝트의 개수를 카테고리 별로 정리
//     */
//     function loadCategories(){
//         return new Promise( res => {
//             categories = [
//                 { year: 2018, data: [] },
//                 { year: 2019, data: [] },
//                 { year: 2020, data: [] }
//             ];
//             dataList.forEach(x => {
//                 /* 카테고리 추가 */
//                 let s_year = categories.find( y => y.year == x.dev_start.split("-")[0]);
//                 let s_cat = s_year.data.find(z => z.name === x.main_lang);
//                 if(s_cat) s_cat.count++;
//                 else s_year.data.push({name: x.main_lang, count: 1});
//             });
//             res(categories);
//         });
//     }

//     function eventTrigger(){
//         return new Promise((res, rej) => {
//             /*
//             연도 Hover 시 활성화 / 비활성화 설정
//             */
//             $(".section-time .item").hover(e => active_year(e.target), e => deactive_year(e.target));

//             /*
//             필터 대상 설정시 필터링
//             */
//             sel(".f_option").forEach(x => {
//                 x.addEventListener("click",  e => setFilter(e));
//             });

//             /*
//             정렬 대상 설정시 정렬
//             */
//             sel(".o_select").addEventListener("change", e => setOrder(e));

//             /*
//             Refresh 버튼 클릭시 모든 설정 초기화 및 재로드
//             */
//             sel(".refresh-btn").addEventListener("click", () => dataReload());

//             res();
//         });
//     }

//     /**
//     * Event Functions
//     */

//    function active_year(target){
//         if(target.classList.contains("active")) return false;
//         var active_box = $(".section-time .item.active");
//         if (active_box.is(":animated") || $(target).is(":animated")) return false;

//         active_box.css({
//             width: "calc((100% - 40px) / 3)",
//             height: "100px",
//             lineHeight: "100px",
//             backgroundColor: "#4D6199"
//         });
//         active_box.find("span").css({
//             color: "#fff",
//             fontSize: "1.8rem"
//         });
//         active_box.find("img:not(.active)").css({opacity: "1", width: "40px", height: "40px"});
//         active_box.find("img.active").css({opacity: "0", width: "40px", height: "40px"});

//         $(target).css({
//             width: "calc((100% - 20px) / 3)",
//             height: "120px",
//             lineHeight: "120px",
//             backgroundColor: "#fff"
//         });
//         $(target).find("span").css({
//             color: "#79B52C",
//             fontSize: "2rem"
//         });
//         $(target).find("img:not(.active)").css({opacity: "0", width: "50px", height: "50px"});
//         $(target).find("img.active").css({opacity: "1", width: "50px", height: "50px"});

//     }

//     function deactive_year(target){
//         if(target.classList.contains("active")) return false;
//         var active_box = $(".section-time .item.active");

//         if (active_box.is(":animated") || $(target).is(":animated")) return false;

//         active_box.css({
//             width: "calc((100% - 20px) / 3)",
//             height: "120px",
//             lineHeight: "120px",
//             backgroundColor: "#fff"
//         });
//         active_box.find("span").css({
//             color: "#79B52C",
//             fontSize: "2rem"
//         });
//         active_box.find("img:not(.active)").css({opacity: "0", width: "50px", height: "50px"});
//         active_box.find("img.active").css({opacity: "1", width: "50px", height: "50px"});

//         $(target).css({
//             width: "calc((100% - 40px) / 3)",
//             height: "100px",
//             lineHeight: "100px",
//             backgroundColor: "#4D6199"
//         });
//         $(target).find("span").css({
//             color: "#fff",
//             fontSize: "1.8rem"
//         });
//         $(target).find("img:not(.active)").css({opacity: "1", width: "40px", height: "40px"});
//         $(target).find("img.active").css({opacity: "0", width: "40px", height: "40px"});
//     }


//     function setFilter(e){
//         let key = e.target.parentElement.dataset.key;
//         let value = e.target.dataset.value;
//         let find = filter.findIndex(x => x.key === key);

//         if(find >= 0) filter[find].value = value;
//         else filter.push({key: key, value: value});
//         rendering();
//     }

//     function setOrder(e){
//         let value = e.target.value;
//         let key = e.target.options[e.target.selectedIndex].dataset.key;
//         order = {key : key, value : value};
//         rendering();
//     }

//     async function dataReload(){
//         filter = [
//             {key: "dev_start", value: "^2018.*"},
//             {key: "main_lang", value: ".+"}
//         ];
//         order = {key : "dev_start", value : 0};
//         dataList = await loadData();
//         categories = await loadCategories();

//         localStorage.setItem("filter", JSON.stringify(filter));
//         localStorage.setItem("order", JSON.stringify(order));
//         localStorage.setItem("dataList", JSON.stringify(dataList));
//         localStorage.setItem("categories", JSON.stringify(categories));
//         rendering();
//     }

// };
