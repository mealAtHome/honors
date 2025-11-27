var Navigation =
{
    /* ================== */
    /* 이전 페이지에서 받은 데이터나, 뒤로가기를 실시 했을 때의 복구 데이터 호출 */
    /* ================== */
    getPageParam()
    {
        let rslt = null;
        let data = GGstorage.getPageStack();

        /* 복구할 데이터가 존재하는지? */
        if(data != null && data.length > 0)
        {
            let lastData = data[data.length-1];
            if(lastData.data != undefined)
                rslt = lastData.data;
        }
        return rslt;
    },

    /* ================== */
    /*
        이전 페이지의 데이터를 가져온다
        code가 공란이면, 가장 최근페이지를 가져온다
     */
    /* ================== */
    getPageData(code=null)
    {
        let rslt = null;
        let data = GGstorage.getPageStack();

        /* 데이터가 없을경우 */
        if(data == null)
            return null;

        /* 복구할 데이터가 존재하는지? */
        if(code == null)
        {
            let lastData = data[data.length-2];
            if(lastData.data != undefined)
                rslt = lastData.data;
        }
        else
        {
            for(let i = data.length-1; i >= 0; i--)
            {
                let dat  = data[i];
                if(dat.page == code)
                {
                    rslt = dat.data;
                    break;
                }
            }
        }
        return rslt;
    },

    /* ================== */
    /*
        이전 페이지의 데이터를 재정의한다.
        code가 공란이면, 가장 최근페이지의 데이터를 업데이트한다.
     */
    /* ================== */
    setPageData(code=null, updateData={})
    {
        let data = GGstorage.getPageStack();

        /* 데이터가 없을경우 */
        if(data == null)
            return false;

        /* 복구할 데이터가 존재하는지? */
        if(code == null)
        {
            data[data.length-2].data = updateData;
        }
        else
        {
            for(let i = data.length-1; i >= 0; i--)
            {
                let dat  = data[i];
                if(dat.page == code)
                {
                    dat.data = updateData;
                    break;
                }
            }
        }
        GGstorage.setPageStack(data);
        return true;
    },

    /* ============================== */
    /* 마지막 pageData 삭제 */
    /* ============================== */
    removeLastPageData()
    {
        let pageStack = GGstorage.getPageStack();
        pageStack.splice(pageStack.length-1);
        GGstorage.setPageStack(pageStack);
    },

    /* ================== */
    /* get last pagecode */
    /* ================== */
    getLastPagecode()
    {
        let pageStack = GGstorage.getPageStack();
        let code = "";
        if(pageStack[pageStack.length-1] != undefined)
            code = pageStack[pageStack.length-1].page;
        return code;
    },

    /* ================== */
    /* get last viewMode */
    /* ================== */
    getLastViewMode()
    {
        let pageStack = GGstorage.getPageStack();
        let viewMode = "";
        if(pageStack[pageStack.length-1] != undefined)
            viewMode = pageStack[pageStack.length-1].data.viewMode;
        return viewMode;
    },

    /* ================== */
    /* 다음 페이지로 이동 (페이지 혹은 다이어로그) */
    /*
        viewMode            : 다음에 이동할 페이지가 어떤 형태인지?
        movePage            : 이동하고자하는 페이지의 코드
        nextPageParam       : 이동하고자하는 페이지에 넘길 데이터
        url                 : return url only 웹 페이지의 경우에만 사용 (웹 페이지는 SPA)
     */
    /* ================== */
    moveFrontPage   (movePage, nextPageParam, url=false) { Navigation.moveFront("page"   , movePage, nextPageParam, url); },
    moveFrontDialog (movePage, nextPageParam, url=false) { Navigation.moveFront("dialog" , movePage, nextPageParam, url); },
    moveFront(viewMode=null, movePage, nextPageParam={}, url=false)
    {
        /* viewMode 가 null 이면 가장 마지막 데이터에서 viewMode를 불러옴 */
        if(viewMode == null)
            viewMode = Navigation.getLastViewMode();

        /* (모바일 전용) 혹시 러닝중이면 1초뒤에 다시 실시 */
        if($("#index-dom").attr("_is-running") == "true")
        {
            setTimeout(function()
            {
                Navigation.moveFront(viewMode, movePage, nextPageParam);
            }, 1000);
            return;
        }

        /* ---------- */
        /* 현재 페이지의 선택사항을 저장 */
        /* ---------- */
        let pageStack = GGstorage.getPageStack();
        if(pageStack != null && pageStack.length > 0)
        {
            let lastestStack     = pageStack[pageStack.length - 1];
            let lastestPage      = lastestStack.page;          /* 현재 페이지의 코드 */
            let lastestViewMode  = lastestStack.data.viewMode; /* 현재 페이지의 viewMode */

            /* 현재 페이지의 선택사항과, viewMode을 저장 */
            nowPageData = Navigation.getData(lastestPage);
            pageStack[pageStack.length - 1].data          = nowPageData;
            pageStack[pageStack.length - 1].data.viewMode = lastestViewMode;
        }
        else
        {
            pageStack = new Array();
        }

        /* ---------- */
        /* 다음 페이지와 이전 페이지가 동일하다면, 리프레쉬 후 종료 */
        /* ---------- */
        if(pageStack.length > 0 && pageStack[pageStack.length-1].page == movePage)
        {
            /* 현재 페이지의 선택사항을 저장 */
            nowPageData = Navigation.getData(movePage);
            pageStack[pageStack.length - 1].data          = nowPageData;
            pageStack[pageStack.length - 1].data.viewMode = viewMode;

            /* 페이지의 show 함수를 실행 */
            Navigation.executeShow();
            return;
        }

        /* ---------- */
        /* 다음페이지에 전달할 파라미터를 저장 */
        /* ---------- */
        nextPageParam.viewMode = viewMode;
        nextPageParam.page     = movePage;
        let stack =
        {
            page: movePage,
            data: nextPageParam,
        };
        pageStack.push(stack);
        GGstorage.setPageStack(pageStack);

        /* ---------- */
        /* 실제적인 페이지 이동 */
        /* 페이지 이동에 대한 도큐먼트 : https://docs.google.com/spreadsheets/d/1aWIXsFjJcQ5Jqz1M6YfEBXO1itWStmLfkLnK6FO0IYg/edit#gid=2037633440 */
        /* ---------- */
        switch(viewMode)
        {
            case "page":
            {
                /* 다음 이동해야할 페이지의 viewMode이 "page"라면, 다이어로그는 숨긴다. */
                if(Navigation.getLastViewMode() == "dialog")
                    GGdialog.hide();

                $('#index-dom')[0].pushPage(Navigation.getURL(movePage), {"animation": "slide"}).then(function()
                {
                    $("#index-dom > ons-page[id!="+movePage+"]").remove();
                });
                break;
            }
            case "dialog":
            {
                GGdialog.show(Navigation.getURL(movePage));
                break;
            }
        } /* end case (viewMode) */
        console.log(pageStack);
    },

    /* ================== */
    /* 뒤로가기 : pageStack에서 현재 페이지의 배열을 삭제하고, 바로 이전 배열의 페이지로 이동한다. */
    /*
        {
            executeShowWhenClose : true/false
                > 뒤로가기한 페이지를 새로고침 할 것인지?
            moveBackTimes : int
                > 몇 개 페이지를 뒤로가기 할 것인지?
        }
    */
    /* ================== */
    moveBack(param={})
    {
        /* (모바일 전용) 혹시 러닝중이면 1초뒤에 다시 실시 */
        if($("#index-dom").attr("_is-running") == "true")
        {
            setTimeout(function()
            {
                Navigation.moveBack();
            }, 1000);
            return;
        }

        /* ---------- */
        /* 변수설정 && 복귀페이지 설정 */
        /* ---------- */
        let pageStack = GGstorage.getPageStack();
        let lastPageStack = pageStack.pop();
        let lastPageViewMode = lastPageStack.data.viewMode;

        /* 현재페이지의 데이터를 빼낸 후의 스택을 저장 */
        GGstorage.setPageStack(pageStack);

        /* 복귀해야할 페이지의 정보 (모바일에서는 pageStack.length 가 0이 되는일은 없다.) */
        let page     = null;
        let viewMode = null;
        let movePage = null;
        if(pageStack.length > 0)
        {
            page     = pageStack[pageStack.length-1];
            viewMode = page.data.viewMode;              /* 복귀해야할 페이지의 viewMode */
            movePage = page.page;                       /* 복귀해야할 페이지코드 */
        }

        /* ---------- */
        /* 실제적인 페이지 이동 */
        /* 현재 페이지의 viewMode에 따라, 뒤로가기의 액션은 달라진다. */
        /* ---------- */
        switch(lastPageViewMode)
        {
            case "page":
            {
                if(viewMode == "page")
                {
                    /* 이미 페이지가 엘리먼트로 존재하면, bringPageTop 함수를 사용 */
                    if($("#"+movePage).length > 0)
                    {
                        $('#index-dom')[0].bringPageTop(Navigation.getURL(movePage), {"animation": "lift"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                        });
                    }
                    else
                    {
                        $('#index-dom')[0].pushPage(Navigation.getURL(movePage), {"animation": "lift"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                        });
                    }
                }
                else if(viewMode == "dialog")
                {
                    GGdialog.show(Navigation.getURL(movePage));
                }
                break;
            } /* 뒤로가기를 하기 전, 현재 페이지의 viewMode이 "page" 인경우. */

            /* 뒤로가기를 하기 전, 현재의 페이지가 "dialog"의 타입일 경우 */
            case "dialog":
            {
                if(viewMode == "page")
                {
                    /* 다이어로그 숨기기 */
                    GGdialog.hide();
                }
                else if(viewMode == "dialog")
                {
                    GGdialog.moveBack(Navigation.getURL(movePage));
                }
                break;
            } /* 뒤로가기를 하기 전, 현재 페이지의 viewMode이 "dialog" 인경우. */
        } /* 현재 페이지의 viewMode에 따라, 뒤로가기의 액션은 달라진다. */
        console.log(pageStack);
    },
}