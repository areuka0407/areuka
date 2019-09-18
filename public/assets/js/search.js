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
        if(qs.trim().length !== 0){
            if(qs.match(/^\?keyword=([^&]*)/) || qs.match(/&keyword=([^&]*)/))
                addString = qs.replace(/keyword=([^&]*)/, addString);
            else addString = qs + "&" + addString;
            location.assign(pathname + addString);
        }
        else location.assign(pathname + "?" + addString)
    });
});
