var GGslideform =
{
    next: function(el)
    {
        let elOpen = $(el).find(".common-div-slideformChild[slideform-status='open']");
        let elNext = $(elOpen.attr("slideform-next"));
        let elProg = $(elNext.attr("slideform-progress"));
        let elProgVal = elNext.attr("slideform-progressval");
        elOpen.attr("slideform-status", "exit");
        elNext.attr("slideform-status", "open");
        elProg.css("width", elProgVal + "%");
    },

    prev: function(el)
    {
        let elOpen = $(el).find(".common-div-slideformChild[slideform-status='open']");
        let elPrev = $(elOpen.attr("slideform-prev"));
        let elProg = $(elPrev.attr("slideform-progress"));
        let elProgVal = elPrev.attr("slideform-progressval");
        elOpen.attr("slideform-status", "exit");
        elPrev.attr("slideform-status", "open");
        elProg.css("width", elProgVal + "%");
    },


};