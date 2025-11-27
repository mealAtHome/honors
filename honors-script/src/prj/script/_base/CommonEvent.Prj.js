CommonEvent.Prj =
{
    hyperlink(e)
    {
        /* class name */
        let className = "commonEvent-tag-hyperlink";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* 가고자 하는 페이지코드 */
        let validation = true;
        let pageData = {};
        let viewMode = target.attr("hyperlink-viewmode");
        let pageCode = target.attr("hyperlink");
        switch(pageCode)
        {
            case Navigation.Page.F10ClassUpdate000Default     : { pageData = { option : target.attr("option"), grpno   : target.attr("grpno")   , clsno : target.attr("clsno"), }; break; }
            case Navigation.Page.F00Class000Detail            : { pageData = {                                 grpno   : target.attr("grpno")   , clsno : target.attr("clsno"), }; break; }
            case Navigation.Page.F10ClassUpdate020Settle      : { pageData = {                                 grpno   : target.attr("grpno")   , clsno : target.attr("clsno"), }; break; }
            case Navigation.Page.B71GrpMemberDetail           : { pageData = {                                 grpno   : target.attr("grpno")   , userno : target.attr("userno"), }; break; }
            case Navigation.Page.B72GrpMemberMergeTemp        : { pageData = {                                 grpno   : target.attr("grpno")   , userno : target.attr("userno"), }; break; }
            case Navigation.Page.D10DetailGrp                 : { pageData = {                                 grpno   : target.attr("grpno")   , }; break; }
            case Navigation.Page.Z22SystemBoardDetail         : { pageData = {                                 sbindex : target.attr("sbindex") , }; break; }
            case Navigation.Page.F10ClassUpdate010TypeLineup  : { pageData = { option : target.attr("option"), grpno   : target.attr("grpno")   , clsno : target.attr("clsno"), }; break; }
            case Navigation.Page.F10ClassUpdate030Cancel      : { pageData = {                                 grpno   : target.attr("grpno")   , clsno : target.attr("clsno"), }; break; }
        }
        if(validation)
            Navigation.moveFront(viewMode, pageCode, pageData);
    },
}