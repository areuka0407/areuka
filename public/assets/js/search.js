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
    });
});
