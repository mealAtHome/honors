var CommonEvent =
{
    /* ========================== */
    /* index.html 이 로드될 때, $(document).ready 에서 한 번만 실행 */
    /* ========================== */
    init()
    {
        $('body').on('keyup',  '.commonEvent-number-pricePretty',              $.proxy(CommonEvent.pricePretty, this));            /* 가격 입력 시, 정리된 가격을 표시 */
        $('body').on('click',  '.common-tag-checkbox',                         $.proxy(CommonEvent.tagCheckbox, this));
        $('body').on('click',  '.common-tr-checkTr',                           $.proxy(CommonEvent.checkTr, this));
        $('body').on('click',  '.commonEvent-btn-viewDetail',                  $.proxy(CommonEvent.pop, this));
        $('body').on('click',  '.common-th-record',                            $.proxy(CommonEvent.toastRecord, this));
        $('body').on('click',  '.subEntity-selectNavi-moveTD',                 $.proxy(CommonEvent.navigateSelect, this));
        $('body').on('click',  '.commonEvent-div-sidemenuBtn',                 $.proxy(CommonEvent.openSidemenu, this));           /* 사이드 메뉴 */
        $('body').on('click',  '.commonEvent-btn-radio',                       $.proxy(CommonEvent.radio, this));                  /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-btn-tab',                         $.proxy(CommonEvent.btntab, this));                  /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-tag-hyperlink',                   $.proxy(CommonEvent.hyperlink, this));              /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-svg-GGscore',                     $.proxy(CommonEvent.ggScore, this));                /* 리뷰점수표시 */
        $('body').on('click',  '.commonEvent-svg-GGscoreSub',                  $.proxy(CommonEvent.ggScoreSub, this));             /* 리뷰점수표시 */
        $('body').on('click',  '.commonEvent-img-viewer',                      $.proxy(CommonEvent.imgViewer, this));              /* 원본이미지 뷰어 */
        $('body').on('click',  '.commonEvent-el-action',                       $.proxy(CommonEvent.action, this));                 /* 특정 버튼에 액션부여 */
        $('body').on('click',  '.commonEvent-div-pagenationBtn',               $.proxy(CommonEvent.tapPagenationBtn, this));       /* 페이지네이션 div */
        $('body').on('click',  '.commonEvent-tbl-multitab > tbody > tr > td',  $.proxy(CommonEvent.multitab, this));               /* 멀티 탭 */
        $('body').on('click',  '.commonEvent-tbl-tab > tbody > tr > td',       $.proxy(CommonEvent.tab, this));                    /* 탭 */
        $('body').on('click',  '.commonEvent-tbl-btnGroup > tbody > tr > td',  $.proxy(CommonEvent.tab, this));                    /* 버튼그룹 */
        $('body').on('click',  '.commonEvent-tbl-sort > thead > tr > th',      $.proxy(CommonEvent.sort, this));                   /* 테이블 정렬 */
        $('body').on('click',  '.commonEvent-btn-round',                       $.proxy(CommonEvent.roundBtn, this));               /* 유저의 탭으로 plus/minus 처리를 해줌. */
        $('body').on('click',  '.commonEvent-btn-plusMinus',                   $.proxy(CommonEvent.btnPlusMinus, this));           /* 유저의 탭으로 plus/minus 처리를 해줌. */
        $('body').on('click',  '.commonEvent-tag-userStorelove',               $.proxy(CommonEvent.userStorelove, this));           /* 유저의 탭으로 plus/minus 처리를 해줌. */

        /* viberator, sound */
        // $('body').on('click', 'button', $.proxy(touch.btn, this));
    },

    getTarget(e, className="")
    {
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        return target;
    },

    sort(e)
    {
        /* get target */
        let target = $(e.target);
        try
        {
            if(target[0].tagName != "TH")
                target = target.parent("th");
        }
        catch(e)
        {
            Common.toast(e);
        }

        /* 변수정의 */
        let col      = target.attr('col');
        let able     = target.attr('sort_able');
        let datatype = target.attr('sort_datatype');
        let status   = target.attr('sort_status');
        let tbl      = target.parents('table');

        /* 소팅 불가능할 경우, 리턴 */
        if(able != "y")
            return;

        /* status : 현재 소팅방향이 asc 인지 desc 인지 */
        /*
            기존 소팅의 값이
            asc  > desc
            desc > asc
            else > asc
         */
        switch(status)
        {
            case "asc"  : status = "desc"; break;
            default     : status = "asc";  break;
        }

        /* 해당 테이블의 소팅상태를 모두 리셋 */
        tbl.find(`thead`).find(`th`).each(function() { $(this).attr("sort_status", ""); });

        /* 행에 번호 부여 && 정렬하고 싶은 값 추출 */
        let arr = []; /* 행 번호와 데이터를 함께 저장 */
        let i = 0;
        tbl.find(`tbody`).find(`tr`).each(function()
        {
            $(this).attr("sort_rownum", i);
            let colDat = $(this).find(`td[col=${col}]`).attr("sort_data");
            let dat =
            {
                ROW_NUM : i,
                COL_DAT : colDat,
            }
            arr.push(dat);
            i++;
        });

        /* DATA sorting */
        arr.sort(function(a,b)
        {
            let ori = a.COL_DAT;
            let com = b.COL_DAT;
            if(status == "asc")
            {
                switch(datatype)
                {
                    case "num": return ori - com;
                    case "str": return ori.localeCompare(com);
                }
            }
            else
            {
                switch(datatype)
                {
                    case "num": return com- ori;
                    case "str": return com.localeCompare(ori);
                }
            }
        });

        /* TR sorting */
        let tbody = tbl.find(`tbody`);
        for(let i in arr)
        {
            for(let j in arr)
            {
                if(i == arr[j].ROW_NUM)
                {
                    let el = tbody.find(`tr[sort_rownum=${j}]`);
                    if(i == 0)
                        tbody.prepend(el);
                    else
                        tbody.find(`tr[sort_rownum=${i-1}]`).after(el);
                }
            }
        }

        /* 클릭한 헤더만 소팅상태 설정 */
        target.attr("sort_status", status);
    },

    pricePretty(e)
    {
        /* get target */
        let target = this.getTarget(e, "commonEvent-number-pricePretty");

        /* 업데이트 하려는 el query */
        let price   = target.val();
        let pretty  = GGC.Common.priceHan(price);
        let elQuery = target.attr("event_pricepretty_el");
        let type    = target.attr("event_pricepretty_type");

        /* type 을 이용하여 자매 엘리먼트를 선택할 수 있다. */
        if(type != undefined)
        {
            switch(type)
            {
                case "sibling":
                {
                    target.parent().find(elQuery).html(pretty);
                    break;
                }
            }
        }
        else
        {
            $(elQuery).html(pretty);
        }
    },

    userStorelove(e)
    {
        /* dont throw event to parent */
        e.stopPropagation();

        /* get target */
        let target = this.getTarget(e, "commonEvent-tag-userStorelove");

        /* vars */
        let storeloveflg  = target.attr("storeloveflg");
        let storeno    = target.attr("storeno");

        /* update user_storelove */
        Common.showProgress();
        setTimeout(function()
        {
            switch(storeloveflg)
            {
                /* yes → no */
                case "y":
                {
                    Api.UserStorelove.delete(storeno);
                    target.css("background-image", `url('${GGutils.Img.getHeartEmpty()}`);
                    target.attr("storeloveflg", "n");
                    break;
                }
                /* no → yes */
                case "n":
                {
                    Api.UserStorelove.insert(storeno);
                    target.css("background-image", `url('${GGutils.Img.getHeartFilled()}`);
                    target.attr("storeloveflg", "y");
                    break;
                }
            }
            Common.hideProgress();
        }, ajaxDelayTime);
    },

    ggScore(e)
    {
        /* class name */
        let className = "commonEvent-svg-GGscore";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* vars */
        let clickedScore = 1 * target.attr("score"); /* 선택한 점수 습득 */
        let parent = target.parents(".GGscore-div-top");

        /* set element (gray) */
        parent.find(".commonEvent-svg-GGscore[score-type=gray]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).hide();
            else
                $(this).show();
        });

        /* set element (gold) */
        parent.find(".commonEvent-svg-GGscore[score-type=gold]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).show();
            else
                $(this).hide();
        });

        /* reset sub score */
        parent.find(".commonEvent-svg-GGscoreSub").each(function()
        {
            let scoreType = $(this).attr("score-type");
            if(scoreType == "gold")
                $(this).hide();
            else
                $(this).show();
        });

        /* set announce */
        parent.find(".GGscore-div-announce").hide();
        parent.find(".GGscore-div-announce[score="+clickedScore+"]").show();

        /* set score to parent */
        parent.attr("score", clickedScore);

        /* set score to span */
        parent.find(".GGscore-span-score").html(clickedScore);

        /* if clickedScore is 5, hide socreSub */
        if(clickedScore == 5)
            parent.find(".GGscore-div-scoreSub").hide();
        else
            parent.find(".GGscore-div-scoreSub").show();
    },

    ggScoreSub(e)
    {
        /* class name */
        let className = "commonEvent-svg-GGscoreSub";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* vars */
        let clickedScore = 1 * target.attr("score"); /* 선택한 점수 습득 */
        let parent = target.parents(".GGscore-div-top");

        /* set element (gray) */
        parent.find(".commonEvent-svg-GGscoreSub[score-type=gray]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).hide();
            else
                $(this).show();
        });

        /* set element (gold) */
        parent.find(".commonEvent-svg-GGscoreSub[score-type=gold]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).show();
            else
                $(this).hide();
        });

        /* set score to parent */
        clickedScore = Math.floor(parent.attr("score")*1) + "." + clickedScore;
        parent.attr("score", clickedScore);

        /* set score to span */
        parent.find(".GGscore-span-score").html(clickedScore);

        // /* if clickedScore is 5, hide socreSub */
        // if(clickedScore == 5)
        //     parent.find(".GGscore-div-scoreSub").hide();
        // else
        //     parent.find(".GGscore-div-scoreSub").show();
    },

    /* 특정 페이지로 이동 */
    /*
        class="commonEvent-tag-hyperlink"
        hyperlink-viewmode="page"
        hyperlink="${Navigation.Page.F02OrderDetail}"
        ${model.getPk()}
     */
    hyperlink(e)
    {
        CommonEvent.Prj.hyperlink(e);
    },

    /* ========================== */
    /* 특정 버튼에 이벤트 부여 */
    /* ========================== */
    action(e)
    {
        /* class name */
        let className = "commonEvent-el-action";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* =============== */
        /* 액션 종류에 따라서 이벤트 실행 */
        /* =============== */
        let action = target.attr("event_action");
        switch(action)
        {
            /* --------------- */
            /* 라커룸 벤 삭제 */
            /* --------------- */
            case "deleteLockerroomBan":
            {
                let ajaxData =
                {
                    OPTION  : "id",
                    ID      : target.attr("lockerroom_ban_id"),
                };
                Common.showProgress();
                setTimeout(function()
                {
                    Api.LockerroomBan.deleteByOption(ajaxData, "toast", "toast");
                    Common.hideProgress();

                    /* refresh */
                    Navigation.executeShow();
                }, ajaxDelayTime);
                break;
            }
        }
    },

    /* ========================== */
    /* 이미지뷰어 */
    /* ========================== */
    imgViewer(e)
    {
        /* class name */
        let className = "commonEvent-img-viewer";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* =============== */
        /* 액션 종류에 따라서 이벤트 실행 */
        /* =============== */
        // let imgType = target.attr("img_type");
        // let src = target.attr("img_src");
        // let endSrc = "";
        // switch(imgType)
        // {
        //     case "user"     : endSrc = GGC.Cvrt.userImgOrigin(src); break;
        //     case "team"     : endSrc = GGC.Cvrt.teamImgOrigin(src); break;
        //     case "player"   : endSrc = GGC.Cvrt.playerImgOrigin(src); break;
        //     case "league"   : endSrc = GGC.Cvrt.leagueImgOrigin(src); break;
        // }

        let picPath = target.attr("pic_path");
        let src = GGC.MenuPic.picPath(picPath, true);

        $("#index-img-image").prop("src", src);
        $("#index-div-imageViewerMask").show();
        $("#index-div-imageViewer").show();
    },

    /* ========================== */
    /* 사이드메뉴 열기 */
    /* ========================== */
    openSidemenu(e)
    {
        GGsidemenu.open();
    }, /* end tab */

    /* ========================== */
    /* 페이지네이션 탭 */
    /* ========================== */
    tapPagenationBtn(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        try
        {
            if(target[0].tagName != "DIV")
                target = target.parent("div");
        } catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
        {
            target.parent("div").find(".commonEvent-div-pagenationBtn").attr("tab", "");
            target.attr("tab", "tab");
        }
    }, /* end tab */

    /* ========================== */
    /* commonEvent-tbl-multitab 여러개의 td 를 다중으로 선택할 수 있다. */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
            - space : 공백을 담당하는 td
    */
    /* ========================== */
    multitab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);
        try
        {
            if(target[0].tagName != "TD")
                target = target.parent("td");
        }
        catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
            target.attr("tab", "tab");
        else
            target.attr("tab", "");

    }, /* end tab */


    /* ========================== */
    /* commonEvent-btn-tab : 탭 효과 */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
    */
    /* ========================== */
    btntab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* attr update */
        if(tab == undefined || tab == "")
            target.attr("tab", "tab");
        else
            target.attr("tab", "");
    },

    /* ========================== */
    /* commonEvent-tbl-common 라는 클래스를 가진 엘리먼트에 페이지 탭 효과를 부여한다. */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
            - space : 공백을 담당하는 td
    */
    /* ========================== */
    tab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        try
        {
            if(target[0].tagName != "TD")
            {
                target = target.parent("td");
            }
        } catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
        {
            target.parent("tr").parent("tbody").find("td[tab!=space]").attr("tab", "");
            target.attr("tab", "tab");
        }

        /* tag hide and show */
        let tabHide = target.attr("tab-hide");
        let tabShow = target.attr("tab-show");
        if(tabHide != undefined)
            $(tabHide).hide();
        if(tabShow != undefined)
            $(tabShow).show();

    }, /* end tab */

    /* ========================== */
    /* commonEvent-btn-radio 라는 클래스를 가진 엘리먼트에 라디오 효과를 부여함 */
    /*
        attributes
          [*] radio_name    : 그룹을 묶어줄 수 있는 키
          [*] tab           : ["tab", ""]
          [*] linked_div    : 링크된 div만 표시하고, tab div는 가린다.
    */
    /* ========================== */
    radio(e)
    {
        let target = $(e.target);
        if(!target.hasClass("commonEvent-btn-radio"))
        {
            target = target.parents(".commonEvent-btn-radio");
        }

        let radioName = target.attr("radio_name");
        let tab       = target.attr("tab");

        /* attr update */
        if(tab == "" || tab == undefined)
        {
            $(".commonEvent-btn-radio[radio_name="+radioName+"]").attr("tab", "");
            target.attr("tab", "tab");
        }

        /* do have linked_div attr */
        // if($(e.target).attr("linked_div") != undefined)
        // {
        //     let linkedDiv = $(e.target).attr("linked_div");
        //     $("."+radioName).each(function()
        //     {
        //         let loopLinkedDiv = $(this).attr("linked_div");
        //         if(loopLinkedDiv == linkedDiv)
        //             $("#"+linkedDiv).show();
        //         else if(loopLinkedDiv != undefined)
        //             $("#"+loopLinkedDiv).hide();
        //     });
        // }
    }, /* end radio */

    /* ========================== */
    /* common-tag-checkbox 라는 클래스를 가진 엘리먼트에 체크박스 효과를 부여함 */
    /*
        attributes
          [*] checkbox_mode    : ["single", "multi"]
            - single : 한 개만 선택할 수 있음 (라디오효과)
            - multi  : 여러개를 한 번에 선택할 수 있음 (체크박스효과)
          [*] checkbox_name    : 그룹을 묶어줄 수 있는 키
          [*] checkbox_checked : ["y", "n"]
          [*] code             : 페이지코드
    */
    /* ========================== */
    tagCheckbox(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass("common-tag-checkbox"))
        {
            target = target.parents(".common-tag-checkbox");
        }

        let checkboxMode    = target.attr("checkbox_mode");
        let checkboxName    = target.attr("checkbox_name");
        let checkboxChecked = target.attr("checkbox_checked");

        /* single 의 경우, 다른 모든 체크박스를 해제한다. */
        if(checkboxMode == "single")
        {
            $(".common-tag-checkbox[checkbox_name="+checkboxName+"]").attr("checkbox_checked", "n");
        }

        /* 선택된 상태라면 선택해제, 선택되지 않은상태라면 선택으로 전환 */
        if(checkboxChecked == "n")
            target.attr("checkbox_checked", "y");
        else
            target.attr("checkbox_checked", "n");

    }, /* end checkbox */

    /* ========================== */
    /* common-tr-checkTr (이)라는 클래스를 가진 엘리먼트에 체크박스 효과를 부여함 */
    /*
        attributes
          [*] checkbox_mode    : ["single", "multi"]
            - single : 한 개만 선택할 수 있음 (라디오효과)
            - multi  : 여러개를 한 번에 선택할 수 있음 (체크박스효과)
          [*] checkbox_name    : 그룹을 묶어줄 수 있는 키
          [*] checkbox_checked : ["y", "n"]
    */
    /* ========================== */
    checkTr(e)
    {
        /* class name */
        let className = "common-tr-checkTr";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
        {
            target = target.parents("."+className);
        }

        let checkboxMode    = target.attr("checkbox_mode");
        let checkboxName    = target.attr("checkbox_name");
        let checkboxChecked = target.attr("checkbox_checked");

        /* single 의 경우, 다른 모든 체크박스를 해제한다. */
        if(checkboxMode == "single")
        {
            $("."+className+"[checkbox_name="+checkboxName+"]").attr("checkbox_checked", "n");
        }

        if(checkboxChecked == "n")
            target.attr("checkbox_checked", "y");
        else
            target.attr("checkbox_checked", "n");

    }, /* end checkbox */

    /* ========================== */
    /* pop - 각 페이지의 POP 페이지로 연결 */
    /*
        attributes
            [*] type : ['user', 'team', 'player', 'league', 'game']
            userno
            team_index
            player_index
            league_index
            game_index
    */
    /* ========================== */
    pop(e)
    {
        /* class name */
        let className = "commonEvent-btn-viewDetail";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set */
        let type = target.attr("type");
        switch(type)
        {
            case "lgg" :
            {
                let pageDat =
                {
                    lggId    : target.attr("lgg_id"),
                }
                // Navigation.moveFrontPage(pageDat);
                break;
            }
        }
    }, /* end checkbox */

    /* ========================== */
    /* 기록 표시 페이지에서, 기록을 클릭할 시, 토스트로 기록이 무엇인지 표시 */
    /*
        attributes
            [*] type   : ['b','p','f','r',] 타자, 투수, 수비수, 주자를 의미
            [*] record : 각 기록의 축약형
    */
    /* ========================== */
    toastRecord(e)
    {
        /* class name */
        let className = "common-th-record";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set vars */
        let type    = target.attr("type");
        let record  = target.attr("record");
        let rslt    = GGC.Custom.recordName(type,record); /* type, record 를 이용하여 기록의 정확한 의미를 자연어로 변경 */

        /* toast to user */
        Common.toastInfo(record+" : "+rslt);

    }, /* end checkbox */

    /* ========================== */
    /* subEntity-selectNavi-table 의 select 의 선택사항을 조정 (앞의 값을 선택하거나, 뒤의 값을 선택하거나) */
    /*
        attributes
            [*] type : ['minus','plus']
                - minus : 앞의 값을 선택한다.
                - plus  : 뒤의 값을 선택한다.
            [*] select_id : 연결되어있는 select의 id
    */
    /* ========================== */
    navigateSelect(e)
    {
        /* class name */
        let className = "subEntity-selectNavi-moveTD";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let type        = target.attr("type");         /* 앞으로 갈 것인지, 뒤로 갈 것인지 */
        let selectId    = target.attr("select_id");    /* 연동되어 있는 select 엘리먼트의 id */
        let nowOption   = $("#"+selectId+" > option:selected").attr("num")*1;
        let nextOption  = 0;
        let errMsg      = "";

        /* main process */
        switch(type)
        {
            /* 앞으로 */
            case "minus":
            {
                nextOption = nowOption-1;
                errMsg = $.i18n("(common)oldest");
                break;
            }
            case "plus":
            {
                nextOption = nowOption+1;
                errMsg = $.i18n("(common)lastest");
            }
        }

        /* select nextOption */
        if($("#"+selectId+" > option[num="+nextOption+"]").length > 0)
        {
            let nextVal = $("#"+selectId+" > option[num="+(nextOption)+"]").val();
            $("#"+selectId).val(nextVal).change();
        }
        else
        {
            Common.toastInfo(errMsg);
        }

    }, /* end checkbox */


    /* ========================== */
    /* commonEvent-btn-plusMinus : 특정span의 값에 +1/-1 을 해줌. */
    /*
        attributes
            [*] event_plusminus_type : ['minus','plus']
            [*] event_plusminus_unit : int

        commonEvent-span-plusMinus
            [*] event_plusminus_min :
            [*] event_plusminus_now :
            [*] event_plusminus_max :

        코드 예

        <td>
            <span>선택할 수 있는 사이드메뉴 개수</span>
            <button
                class="commonEvent-btn-plusMinus common-btn-outline borderRound"
                event_plusminus_type="minus"
                event_plusminus_unit="1"
            >－</button>
            <span
                class="commonEvent-span-plusMinus"
                event_plusminus_min="1"
                event_plusminus_now="1"
                event_plusminus_max="2"
            >1</span>개
            <button
                class="commonEvent-btn-plusMinus common-btn-outline borderRound"
                event_plusminus_type="plus"
                event_plusminus_unit="1"
            >＋</button>
        </td>
    */
    /* ========================== */
    btnPlusMinus(e)
    {
        /* class name */
        let className = "commonEvent-btn-plusMinus";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let type    = target.attr("event_plusminus_type");
        let unit    = target.attr("event_plusminus_unit")*1;
        let spanEl  = target.parent().find(".commonEvent-span-plusMinus");
        let min     = spanEl.attr("event_plusminus_min")*1;
        let now     = spanEl.attr("event_plusminus_now")*1;
        let max     = spanEl.attr("event_plusminus_max")*1;
        let minFailedMsg = spanEl.attr("event_plusminus_min_failedmsg");
        let maxFailedMsg = spanEl.attr("event_plusminus_max_failedmsg");

        /* validate spenEl */
        if(spanEl.length != 1)
        {
            Common.toastInfo("button s target is not exists");
            return;
        }

        /* main process */
        switch(type)
        {
            case "minus":
            {
                if(now - unit < min)
                {
                    if(minFailedMsg == null)
                        errMsg = $.i18n("(common)it is min");
                    else
                        errMsg = minFailedMsg;

                    Common.toast(errMsg);
                    now = min;
                }
                else
                    now -= unit;

                break;
            }
            case "plus":
            {
                if(now + unit > max)
                {
                    if(maxFailedMsg == null)
                        errMsg = $.i18n("(common)it is max");
                    else
                        errMsg = maxFailedMsg;

                    Common.toast(errMsg);
                    now = max;
                }
                else
                    now += unit;
            }
        }

        /* select nextOption */
        if(now >= min && now <= max)
        {
            spanEl.attr("event_plusminus_now", now);
            spanEl.html(now);
        }
    },

    /* ========================== */
    /* commonEvent-btn-round : 특정span의 값에 +1/-1 을 해줌. */
    /*
        attributes
            [*] btn_type : ['minus','plus']
                - minus : -1
                - plus  : +1
            [*] linked_span : 연결되어있는 span

        $(linked_span)
            [*] min :
            [*] max :
            [*] field : span에 값을 표현하기 전에, 특정 필드값에 맞춰서 변환.
    */
    /* ========================== */
    roundBtn(e)
    {
        /* class name */
        let className = "commonEvent-btn-round";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let eventType   = target.attr("event_type");    /* 앞으로 갈 것인지, 뒤로 갈 것인지 */
        let spanEl      = target.attr("linked_span")    /* 연동되어있는 span */
            spanEl      = $(spanEl);
        let min         = spanEl.attr("min")*1;
        let max         = spanEl.attr("max")*1;
        let nowVal      = spanEl.attr("now_value")*1;
        let field       = spanEl.attr("field");

        /* main process */
        switch(eventType)
        {
            case "minus":
            {
                nowVal -= 1;
                errMsg = $.i18n("(common)it is min");
                break;
            }
            case "plus":
            {
                nowVal += 1;
                errMsg = $.i18n("(common)it is max");
            }
        }

        /* select nextOption */
        if(nowVal >= min && nowVal <= max)
        {
            spanEl.attr("now_value", nowVal);
            switch(field)
            {
                case "MLeagueRank.lr_rank": spanEl.html(GGC.Cvrt.lr_rank(nowVal)); break;
            }
        }
        else
        {
            Common.toastInfo(errMsg);
        }
    },

    /* ========================== */
    /* commonEvent-tag-action */
    /*
        [*] action : 어떤 액션을 실행하는지?
            - deleteLeagueAttendance
                [*] TEAM_INDEX
                [*] LEAGUE_INDEX
    */
    /* ========================== */
    commonEventTagAction(e)
    {
        /* class name */
        let className = "commonEvent-tag-action";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* action by tag */
        let process = function()
        {
            Common.showProgress();
            setTimeout(function()
            {
                let action = target.attr("action");
                switch(action)
                {
                    case "deleteLeagueAttendance":
                    {
                        let ajaxData =
                        {
                            TEAM_INDEX: target.attr("TEAM_INDEX"),
                            LEAGUE_INDEX: target.attr("LEAGUE_INDEX"),
                        }
                        let ajax = Api.LeagueAttendance.delete(ajaxData, "toast", "toast");
                        LD.show();
                        break;
                    }
                } /* end switch */
                Common.showProgress();
            }, ajaxDelayTime);
        }

        /* 참가팀을 정말로 삭제할 것인지 확인 */
        let teamName = target.attr("team_name");
        Common.confirm2("실행하시겠습니까?", process);

    }, /* end function */
}