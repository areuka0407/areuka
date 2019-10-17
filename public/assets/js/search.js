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
    });
});
