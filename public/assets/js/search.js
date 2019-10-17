window.addEventListener("load", function()
{
    function sel(target)
    {
        return document.querySelectorAll(target).length > 1 ? document.querySelectorAll(target) : document.querySelector(target);
    }

    sel("input.search-bar").addEventListener("keydown", function(e){
        if(e.keyCode === 13 ) sel(".search-btn[data-input='"+e.target.id+"']").click();
    });


    sel(".search-btn").addEventListener("click", function(e)
    {
<<<<<<< HEAD
        // if(location.pathname === "/projects/list") return false;
        if(!e.target.dataset.input) return false;
        var input = "^.*" + sel("#"+e.target.dataset.input).value + ".*$";
        var old_val = JSON.parse(localStorage.getItem("filter"));

        if(old_val)
        {
            var find = old_val.find(x => x.key === "title");
            if(find) find.value = input;
            else old_val.push({key: "title", value: input});
        }
        else old_val = [{key: "title", value: input}];

        localStorage.setItem("filter", JSON.stringify(old_val));
        location.replace("/projects/list");
        return true;
=======
        if(!e.target.dataset.input) return false;

        // URL에 삽입할 키워드
        let keyword = sel("#"+e.target.dataset.input).value;
        let pathname = location.pathname === "/" ? "/projects" : location.pathname;
        let addString = "keyword="+keyword;

        let qs = location.search;
        // 쿼리 스트링이 있으면
        if(qs.trim().length !== 0){
            if(qs.match(/^\?keyword=([^&]*)/) || qs.match(/&keyword=([^&]*)/))
                addString = keyword.length > 0 ? qs.replace(/keyword=([^&]*)/, addString) : qs.replace(/(keyword=[^&]*|&keyword=[^&]*|\?keyword=[^&]*)/, "");
            else addString = keyword.length > 0 ?  qs + "&" + addString : qs;
            location.assign(pathname + addString);
        }
        // 쿼리 스트링이 없으면
        else location.assign((keyword.length ? pathname + "?" + addString : pathname))
>>>>>>> origin/dev
    });
});
