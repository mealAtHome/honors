var Navigation =
{
    /* ================== */
    /* 함수 이름으로부터 서버의 연결지점을 가져온다. */
    /* ================== */
    getApiUrlByFuncName(funcName="")
    {
        let url = "";
        let serverHost  = ServerInfo.getServerHost();
        switch(funcName)
        {
            /* SystemBoard */                                   case "Api.SystemBoard.select"                               : url = serverHost + "/src/data/systemBoard/selectSystemBoard.php"; break;
            /* User */                                          case "Api.User.login"                                       : url = serverHost + "/src/data/user/login.php"; break;
            /* User */                                          case "Api.User.select"                                      : url = serverHost + "/src/data/user/selectUser.php"; break;
            /* User */                                          case "Api.User.insert"                                      : url = serverHost + "/src/data/user/insertUser.php"; break;
            /* User */                                          case "Api.User.update"                                      : url = serverHost + "/src/data/user/updateUser.php"; break;
            /* User */                                          case "Api.User.deleteUserInfo"                              : url = serverHost + "/src/data/user/deleteUserInfo.php"; break;
            /* Grp  */                                          case "Api.Grp.select"                                       : url = serverHost + "/src/data/grp/selectGrp.php"; break;
            /* cls  */                                          case "Api.Cls.select"                                       : url = serverHost + "/src/data/cls/selectCls.php"; break;
            /* cls  */                                          case "Api.Cls.update"                                       : url = serverHost + "/src/data/cls/updateCls.php"; break;
            /* clslineup2  */                                   case "Api.Clslineup2.select"                                : url = serverHost + "/src/data/clslineup2/selectClslineup2.php"; break;
            /* clslineup2  */                                   case "Api.Clslineup2.update"                                : url = serverHost + "/src/data/clslineup2/updateClslineup2.php"; break;
            /* clssettle  */                                    case "Api.Clssettle.select"                                 : url = serverHost + "/src/data/clssettle/selectClssettle.php"; break;
            /* clssettle  */                                    case "Api.Clssettle.update"                                 : url = serverHost + "/src/data/clssettle/updateClssettle.php"; break;
            /* GrpMember */                                     case "Api.GrpMember.select"                                 : url = serverHost + "/src/data/grpMember/selectGrpMember.php"; break;
            /* GrpMember */                                     case "Api.GrpMember.update"                                 : url = serverHost + "/src/data/grpMember/updateGrpMember.php"; break;
            /* GrpMemberPointhist */                            case "Api.GrpMemberPointhist.select"                        : url = serverHost + "/src/data/grpMemberPointhist/selectGrpMemberPointhist.php"; break;
            /* GrpMemberPointhist */                            case "Api.GrpMemberPointhist.update"                        : url = serverHost + "/src/data/grpMemberPointhist/updateGrpMemberPointhist.php"; break;
            /* Bank */                                          case "Api.Bank.select"                                      : url = serverHost + "/src/data/bank/selectBank.php"; break;
            /* Bankaccount */                                   case "Api.Bankaccount.select"                               : url = serverHost + "/src/data/bankaccount/selectBankaccount.php"; break;
            /* Bankaccount */                                   case "Api.Bankaccount.update"                               : url = serverHost + "/src/data/bankaccount/updateBankaccount.php"; break;
            /* System.Check */                                  case "Api.System.Check.check"                               : url = serverHost + "/src/data/system/check.php"; break;

        } /* end switch */
        return url;
    },

    Page:
    {
        A00UserLogin : "ULGN",
        A01UserInsert : "UINT",
        A02UserPrivacyDelete : "UPRD",
        A11UserMainHome : "UMHM",
        A12UserMainGrp : "UMGP",
        A13UserMainCls : "UMCL",
        A14UserMainSettle : "UMST",
        A15UserMainManage : "UMMG",
        A81UserBankacctList : "UBKL",
        A82UserBankacctUpdate : "UBKU",
        B10ManagerChooseGrp : "MGCG",
        B11ManagerMainHome : "MMHM",
        B12ManagerMainClasses : "MMCL",
        B13ManagerMainMembers : "MMMB",
        B14ManagerMainSettle : "MMST",
        B15ManagerMainManage : "MMMG",
        B85ManagerMemberLayering : "MMLY",
        B71GrpMemberDetail : "GMDT",
        B72GrpMemberMergeTemp : "GMMT",
        B81ManagerBankacctList : "MBKL",
        B82ManagerBankacctUpdate : "MBKU",
        C00AdminChooseUser : "AACU",
        D10DetailGrp : "DGRP",
        F00Class000Detail : "CLSD",
        F00Class001DetailApplyDialog : "CLSA",
        F00Class080TextCls : "CLDC",
        F00Class081TextApply : "CLDA",
        F00Class082TextSettle : "CLDS",
        F10ClassUpdate000Default : "CUDE",
        F10ClassUpdate010TypeLineup : "CUTF",
        F10ClassUpdate020Settle : "CUST",
        F10ClassUpdate030Cancel : "CUCC",
        S10ChooseGrpMember : "CHGM",
        S11ChooseCls : "CHCL",
        Z00AppUpdateUrl : "SAPP",
        Z21SystemBoardList : "SBLI",
        Z22SystemBoardDetail : "SBDL",

        /* 웹에만 존재하는 */
        Web :
        {
            INDEX       : "web.index",
            STORE       : "web.store",
            ORDER       : "web.cls",
            MENU        : "web.menu",
            SUMMARY     : "web.summary",
            SETTINGS    : "web.settings",
            LOGIN       : "web.login",
        }
    },


    /* ================== */
    /* ページコードを利用して、URLを出す */
    /* ================== */
    getURL(str)
    {
        let url = "";
        switch(str)
        {
            case Navigation.Page.A00UserLogin                                  : url = "./app/contents/A00-user/A00-UserLogin.html"; break;
            case Navigation.Page.A01UserInsert                                 : url = "./app/contents/A00-user/A01-UserInsert.html"; break;
            case Navigation.Page.A02UserPrivacyDelete                          : url = "./app/contents/A00-user/A02-UserPrivacyDelete.html"; break;
            case Navigation.Page.A11UserMainHome                               : url = "./app/contents/A00-user/A11-UserMainHome.html"; break;
            case Navigation.Page.A12UserMainGrp                                : url = "./app/contents/A00-user/A12-UserMainGrp.html"; break;
            case Navigation.Page.A13UserMainCls                                : url = "./app/contents/A00-user/A13-UserMainCls.html"; break;
            case Navigation.Page.A14UserMainSettle                             : url = "./app/contents/A00-user/A14-UserMainSettle.html"; break;
            case Navigation.Page.A15UserMainManage                             : url = "./app/contents/A00-user/A15-UserMainManage.html"; break;
            case Navigation.Page.A81UserBankacctList                           : url = "./app/contents/A00-user/A81-UserBankacctList.html"; break;
            case Navigation.Page.A82UserBankacctUpdate                         : url = "./app/contents/A00-user/A82-UserBankacctUpdate.html"; break;
            case Navigation.Page.B10ManagerChooseGrp                           : url = "./app/contents/B00-manager/B10-ManagerChooseGrp.html"; break;
            case Navigation.Page.B11ManagerMainHome                            : url = "./app/contents/B00-manager/B11-ManagerMainHome.html"; break;
            case Navigation.Page.B12ManagerMainClasses                         : url = "./app/contents/B00-manager/B12-ManagerMainClasses.html"; break;
            case Navigation.Page.B13ManagerMainMembers                         : url = "./app/contents/B00-manager/B13-ManagerMainMembers.html"; break;
            case Navigation.Page.B14ManagerMainSettle                          : url = "./app/contents/B00-manager/B14-ManagerMainSettle.html"; break;
            case Navigation.Page.B15ManagerMainManage                          : url = "./app/contents/B00-manager/B15-ManagerMainManage.html"; break;
            case Navigation.Page.B85ManagerMemberLayering                      : url = "./app/contents/B00-manager/B85-ManagerMemberLayering.html"; break;
            case Navigation.Page.B71GrpMemberDetail                            : url = "./app/contents/B00-manager/B71-GrpMemberDetail.html"; break;
            case Navigation.Page.B72GrpMemberMergeTemp                         : url = "./app/contents/B00-manager/B72-GrpMemberMergeTemp.html"; break;
            case Navigation.Page.B81ManagerBankacctList                        : url = "./app/contents/B00-manager/B81-ManagerBankacctList.html"; break;
            case Navigation.Page.B82ManagerBankacctUpdate                      : url = "./app/contents/B00-manager/B82-ManagerBankacctUpdate.html"; break;
            case Navigation.Page.C00AdminChooseUser                            : url = "./app/contents/C00-admin/C00-AdminChooseUser.html"; break;
            case Navigation.Page.D10DetailGrp                                  : url = "./app/contents/D00-detail/D10-DetailGrp.html"; break;
            case Navigation.Page.F00Class000Detail                             : url = "./app/contents/F00-class/F00-Class000Detail.html"; break;
            case Navigation.Page.F00Class001DetailApplyDialog                  : url = "./app/contents/F00-class/F00-Class001DetailApplyDialog.html"; break;
            case Navigation.Page.F00Class080TextCls                            : url = "./app/contents/F00-class/F00-Class080TextCls.html"; break;
            case Navigation.Page.F00Class081TextApply                          : url = "./app/contents/F00-class/F00-Class081TextApply.html"; break;
            case Navigation.Page.F00Class082TextSettle                         : url = "./app/contents/F00-class/F00-Class082TextSettle.html"; break;
            case Navigation.Page.F10ClassUpdate000Default                      : url = "./app/contents/F00-class/F10-ClassUpdate000Default.html"; break;
            case Navigation.Page.F10ClassUpdate010TypeLineup                   : url = "./app/contents/F00-class/F10-ClassUpdate010TypeLineup.html"; break;
            case Navigation.Page.F10ClassUpdate020Settle                       : url = "./app/contents/F00-class/F10-ClassUpdate020Settle.html"; break;
            case Navigation.Page.F10ClassUpdate030Cancel                       : url = "./app/contents/F00-class/F10-ClassUpdate030Cancel.html"; break;
            case Navigation.Page.S10ChooseGrpMember                            : url = "./app/contents/S00-common/S10-ChooseGrpMember.html"; break;
            case Navigation.Page.S11ChooseCls                                  : url = "./app/contents/S00-common/S11-ChooseCls.html"; break;
            case Navigation.Page.Z00AppUpdateUrl                               : url = "./app/contents/Z00-system/Z00-AppUpdateUrl.html"; break;
            case Navigation.Page.Z21SystemBoardList                            : url = "./app/contents/Z00-system/Z21-SystemBoardList.html"; break;
            case Navigation.Page.Z22SystemBoardDetail                          : url = "./app/contents/Z00-system/Z22-SystemBoardDetail.html"; break;
        }
        return url;
    },

    /* ================================== */
    /* 각 페이지를 이동할 때, 데이터를 각 페이지에서 가져온다.  */
    /* ================================== */
    getData(code)
    {
        let rslt = null;
        let data = {};
        switch(code)
        {
            case Navigation.Page.A00UserLogin                           : data = ULGN.Data; break;
            case Navigation.Page.A01UserInsert                          : data = UINT.Data; break;
            case Navigation.Page.A02UserPrivacyDelete                   : data = UPRD.Data; break;
            case Navigation.Page.A11UserMainHome                        : data = UMHM.Data; break;
            case Navigation.Page.A12UserMainGrp                         : data = UMGP.Data; break;
            case Navigation.Page.A13UserMainCls                         : data = UMCL.Data; break;
            case Navigation.Page.A14UserMainSettle                      : data = UMST.Data; break;
            case Navigation.Page.A15UserMainManage                      : data = UMMG.Data; break;
            case Navigation.Page.A81UserBankacctList                    : data = UBKL.Data; break;
            case Navigation.Page.A82UserBankacctUpdate                  : data = UBKU.Data; break;
            case Navigation.Page.B10ManagerChooseGrp                    : data = MGCG.Data; break;
            case Navigation.Page.B11ManagerMainHome                     : data = MMHM.Data; break;
            case Navigation.Page.B12ManagerMainClasses                  : data = MMCL.Data; break;
            case Navigation.Page.B13ManagerMainMembers                  : data = MMMB.Data; break;
            case Navigation.Page.B14ManagerMainSettle                   : data = MMST.Data; break;
            case Navigation.Page.B15ManagerMainManage                   : data = MMMG.Data; break;
            case Navigation.Page.B85ManagerMemberLayering               : data = MMLY.Data; break;
            case Navigation.Page.B71GrpMemberDetail                     : data = GMDT.Data; break;
            case Navigation.Page.B72GrpMemberMergeTemp                  : data = GMMT.Data; break;
            case Navigation.Page.B81ManagerBankacctList                 : data = MBKL.Data; break;
            case Navigation.Page.B82ManagerBankacctUpdate               : data = MBKU.Data; break;
            case Navigation.Page.C00AdminChooseUser                     : data = AACU.Data; break;
            case Navigation.Page.D10DetailGrp                           : data = DGRP.Data; break;
            case Navigation.Page.F00Class000Detail                      : data = CLSD.Data; break;
            case Navigation.Page.F00Class001DetailApplyDialog           : data = CLSA.Data; break;
            case Navigation.Page.F00Class080TextCls                     : data = CLDC.Data; break;
            case Navigation.Page.F00Class081TextApply                   : data = CLDA.Data; break;
            case Navigation.Page.F00Class082TextSettle                  : data = CLDS.Data; break;
            case Navigation.Page.F10ClassUpdate000Default               : data = CUDE.Data; break;
            case Navigation.Page.F10ClassUpdate010TypeLineup            : data = CUTF.Data; break;
            case Navigation.Page.F10ClassUpdate020Settle                : data = CUST.Data; break;
            case Navigation.Page.F10ClassUpdate030Cancel                : data = CUCC.Data; break;
            case Navigation.Page.S10ChooseGrpMember                     : data = CHGM.Data; break;
            case Navigation.Page.S11ChooseCls                           : data = CHCL.Data; break;
            case Navigation.Page.Z00AppUpdateUrl                        : data = SAPP.Data; break;
            case Navigation.Page.Z21SystemBoardList                     : data = SBLI.Data; break;
            case Navigation.Page.Z22SystemBoardDetail                   : data = SBDL.Data; break;
        }
        rslt = data;
        return rslt;
    },

    showLastPage()
    {
        Navigation.executeShow();
    },


    /* ======================= */
    /* 페이지 가장 최근 스택의 show 함수를 실행시킨다. */
    /* ======================= */
    executeShow()
    {
        /* --------------- */
        /* reload if web */
        /* --------------- */
        if(GGstorage.isWeb())
        {
            window.location.reload();
            return;
        }

        /* ------------- */
        /* get pageStack */
        /* ------------- */
        let pageStack = GGstorage.getPageStack();
        let lastPage = null;
        if(pageStack == undefined || pageStack.length == 0)
        {
            console.log("pageStack이 정의되지 않았거나, 길이가 0 입니다.");
            return;
        }
        else
        {
            lastPage = pageStack[pageStack.length-1];
        }
        let lastPageCode = lastPage['page'];

        /* 파라미터 추가설정 > 뒤로가기를 한 후, 초기화를 실행할 것인지? */
        if(lastPage.data.executeShowWhenClose != undefined)
        {
            if(lastPage.data.executeShowWhenClose == false)
            {
                pageStack[pageStack.length-1].data.executeShowWhenClose = true;
                GGstorage.setPageStack(pageStack);
                return;
            }
        }

        /* 페이지에 대한 show 실행 */
        switch(lastPageCode)
        {
            case Navigation.Page.A00UserLogin                             : ULGN.show(); break;
            case Navigation.Page.A01UserInsert                            : UINT.show(); break;
            case Navigation.Page.A02UserPrivacyDelete                     : UPRD.show(); break;
            case Navigation.Page.A11UserMainHome                          : UMHM.show(); break;
            case Navigation.Page.A12UserMainGrp                           : UMGP.show(); break;
            case Navigation.Page.A13UserMainCls                           : UMCL.show(); break;
            case Navigation.Page.A14UserMainSettle                        : UMST.show(); break;
            case Navigation.Page.A15UserMainManage                        : UMMG.show(); break;
            case Navigation.Page.A81UserBankacctList                      : UBKL.show(); break;
            case Navigation.Page.A82UserBankacctUpdate                    : UBKU.show(); break;
            case Navigation.Page.B10ManagerChooseGrp                      : MGCG.show(); break;
            case Navigation.Page.B11ManagerMainHome                       : MMHM.show(); break;
            case Navigation.Page.B12ManagerMainClasses                    : MMCL.show(); break;
            case Navigation.Page.B13ManagerMainMembers                    : MMMB.show(); break;
            case Navigation.Page.B14ManagerMainSettle                     : MMST.show(); break;
            case Navigation.Page.B15ManagerMainManage                     : MMMG.show(); break;
            case Navigation.Page.B85ManagerMemberLayering                 : MMLY.show(); break;
            case Navigation.Page.B71GrpMemberDetail                       : GMDT.show(); break;
            case Navigation.Page.B72GrpMemberMergeTemp                    : GMMT.show(); break;
            case Navigation.Page.B81ManagerBankacctList                   : MBKL.show(); break;
            case Navigation.Page.B82ManagerBankacctUpdate                 : MBKU.show(); break;
            case Navigation.Page.C00AdminChooseUser                       : AACU.show(); break;
            case Navigation.Page.D10DetailGrp                             : DGRP.show(); break;
            case Navigation.Page.F00Class000Detail                        : CLSD.show(); break;
            case Navigation.Page.F00Class001DetailApplyDialog             : CLSA.show(); break;
            case Navigation.Page.F00Class080TextCls                       : CLDC.show(); break;
            case Navigation.Page.F00Class081TextApply                     : CLDA.show(); break;
            case Navigation.Page.F00Class082TextSettle                    : CLDS.show(); break;
            case Navigation.Page.F10ClassUpdate000Default                 : CUDE.show(); break;
            case Navigation.Page.F10ClassUpdate010TypeLineup              : CUTF.show(); break;
            case Navigation.Page.F10ClassUpdate020Settle                  : CUST.show(); break;
            case Navigation.Page.F10ClassUpdate030Cancel                  : CUCC.show(); break;
            case Navigation.Page.S10ChooseGrpMember                       : CHGM.show(); break;
            case Navigation.Page.S11ChooseCls                             : CHCL.show(); break;
            case Navigation.Page.Z00AppUpdateUrl                          : SAPP.show(); break;
            case Navigation.Page.Z21SystemBoardList                       : SBLI.show(); break;
            case Navigation.Page.Z22SystemBoardDetail                     : SBDL.show(); break;
        }
    },

    executeMoveBack()
    {
        /* ------------- */
        /* get pageStack */
        /* ------------- */
        let pageStack = GGstorage.getPageStack();
        let lastPage = null;
        if(pageStack == undefined || pageStack.length == 0)
        {
            console.log("pageStack이 정의되지 않았거나, 길이가 0 입니다.");
            return;
        }
        else
        {
            lastPage = pageStack[pageStack.length-1];
        }
        let lastPageCode = lastPage['page'];

        /* 페이지에 대한 show 실행 */
        switch(lastPageCode)
        {
            case Navigation.Page.A00UserLogin                             : ULGN.close(true); break;
            case Navigation.Page.A01UserInsert                            : UINT.close(true); break;
            case Navigation.Page.A02UserPrivacyDelete                     : UPRD.close(true); break;
            case Navigation.Page.A11UserMainHome                          : UMHM.close(true); break;
            case Navigation.Page.A12UserMainGrp                           : UMGP.close(true); break;
            case Navigation.Page.A13UserMainCls                           : UMCL.close(true); break;
            case Navigation.Page.A14UserMainSettle                        : UMST.close(true); break;
            case Navigation.Page.A15UserMainManage                        : UMMG.close(true); break;
            case Navigation.Page.A81UserBankacctList                      : UBKL.close(true); break;
            case Navigation.Page.A82UserBankacctUpdate                    : UBKU.close(true); break;
            case Navigation.Page.B10ManagerChooseGrp                      : MGCG.close(true); break;
            case Navigation.Page.B11ManagerMainHome                       : MMHM.close(true); break;
            case Navigation.Page.B12ManagerMainClasses                    : MMCL.close(true); break;
            case Navigation.Page.B13ManagerMainMembers                    : MMMB.close(true); break;
            case Navigation.Page.B14ManagerMainSettle                     : MMST.close(true); break;
            case Navigation.Page.B15ManagerMainManage                     : MMMG.close(true); break;
            case Navigation.Page.B85ManagerMemberLayering                 : MMLY.close(true); break;
            case Navigation.Page.B71GrpMemberDetail                       : GMDT.close(true); break;
            case Navigation.Page.B72GrpMemberMergeTemp                    : GMMT.close(true); break;
            case Navigation.Page.B81ManagerBankacctList                   : MBKL.close(true); break;
            case Navigation.Page.B82ManagerBankacctUpdate                 : MBKU.close(true); break;
            case Navigation.Page.C00AdminChooseUser                       : AACU.close(true); break;
            case Navigation.Page.D10DetailGrp                             : DGRP.close(true); break;
            case Navigation.Page.F00Class000Detail                        : CLSD.close(true); break;
            case Navigation.Page.F00Class001DetailApplyDialog             : CLSA.close(true); break;
            case Navigation.Page.F00Class080TextCls                       : CLDC.close(true); break;
            case Navigation.Page.F00Class081TextApply                     : CLDA.close(true); break;
            case Navigation.Page.F00Class082TextSettle                    : CLDS.close(true); break;
            case Navigation.Page.F10ClassUpdate000Default                 : CUDE.close(true); break;
            case Navigation.Page.F10ClassUpdate010TypeLineup              : CUTF.close(true); break;
            case Navigation.Page.F10ClassUpdate020Settle                  : CUST.close(true); break;
            case Navigation.Page.F10ClassUpdate030Cancel                  : CUCC.close(true); break;
            case Navigation.Page.S10ChooseGrpMember                       : CHGM.close(true); break;
            case Navigation.Page.S11ChooseCls                             : CHCL.close(true); break;
            case Navigation.Page.Z00AppUpdateUrl                          : SAPP.close(true); break;
            case Navigation.Page.Z21SystemBoardList                       : SBLI.close(true); break;
            case Navigation.Page.Z22SystemBoardDetail                     : SBDL.close(true); break;
        }
    },

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
        if(
            GGstorage.isWeb() == false &&
            $("#index-dom").attr("_is-running") == "true")
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
                /* console.log(GGstorage.isWeb(), GGstorage.getDeviceKind()); */
                if(GGstorage.isWeb())
                {
                    /* make get */
                    let param = GGutils.Navigation.makeGetFromObject(nextPageParam);

                    /* return url only */
                    if(url == true)
                        return Navigation.getURL(movePage) + param;

                    /* move page */
                    window.location.href = Navigation.getURL(movePage) + param;
                }
                else
                {
                    /* 다음 이동해야할 페이지의 viewMode이 "page"라면, 다이어로그는 숨긴다. */
                    if(Navigation.getLastViewMode() == "dialog")
                        GGdialog.hide();

                    /* 이미 페이지가 엘리먼트로 존재하면, bringPageTop 함수를 사용 */
                    if($("#"+movePage).length > 0)
                    {
                        $('#index-dom')[0].bringPageTop(Navigation.getURL(movePage), {"animation": "slide"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                        });
                    }
                    else
                    {
                        $('#index-dom')[0].pushPage(Navigation.getURL(movePage), {"animation": "slide"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                        });
                    }
                }
                break;
            }
            case "dialog":
            {
                if(GGstorage.isWeb())
                {
                    nextPageParam.page     = movePage;
                    nextPageParam.viewMode = viewMode;
                    let param = GGutils.Navigation.makeGetFromObject(nextPageParam);
                    GGdialog.show(Navigation.getURL(movePage) + param);
                }
                else
                {
                    GGdialog.show(Navigation.getURL(movePage));
                }
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
        if(GGstorage.isWeb() == false && $("#index-dom").attr("_is-running") == "true")
        {
            setTimeout(function()
            {
                Navigation.moveBack();
            }, 1000);
            return;
        }

        /* ---------- */
        /* 파라미터 세팅 */
        /* ---------- */

        /* 이전 페이지에서 show 함수를 실행하지 않도록 설정했다면, executeShow를 덮어쓴다. */
        let executeShowWhenClose = true;
        if(param.executeShowWhenClose != undefined)
            executeShowWhenClose = param.executeShowWhenClose;

        /* 뒤로가기를 할 때, 몇 개 페이지를 뒤로가기 할 것인지? */
        let moveBackTimes = 1;
        if(param.moveBackTimes != undefined)
            moveBackTimes = param.moveBackTimes;

        /* ---------- */
        /* 변수설정 && 복귀페이지 설정 */
        /* ---------- */
        let pageStack = GGstorage.getPageStack();

        /* 웹 상태에서 pageStack의 길이가 0 이라면, 뒤로가기를 해야함. */
        if(GGstorage.isWeb() && pageStack.length == 0)
        {
            window.history.back();
            return;
        }

        /* moveBackTimes 만큼 pop 실시 */
        let lastPageStack = null;
        for(let i = 0; i < moveBackTimes; i++)
            lastPageStack = pageStack.pop();

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
        else if(GGstorage.isWeb())
        {
            /* pop한 pageStack 에 데이터가 없다는 것은 페이지에서 다이어로그를 띄운 상태임 */
            GGdialog.hide();
            return;
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

    /* ================== */
    /* goto home */
    /* ================== */
    moveHome()
    {
        GGstorage.clearPageStack();
        Navigation.moveAfterLogin();
    },

    /* ================== */
    /* goto login */
    /* ================== */
    moveLogin()
    {
        GGstorage.clearPageStack();
        Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
    },
    loginIntoGrp(grpno) { Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno}); },

    /* ================== */
    /* logout */
    /* ================== */
    moveLogout()
    {
        GGstorage.clearPageStack();
        Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
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

    /* ============================== */
    /* 로그인 후, 메인페이지로 이동 */
    /* ============================== */
    moveAfterLogin()
    {
        GGstorage.clearGrpmtype();
        let appMode = GGstorage.getAppmode();
        switch(appMode)
        {
            case GGF.System.AppMode.CUS : Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
            case GGF.System.AppMode.MNG : Navigation.moveFrontPage(Navigation.Page.B10ManagerChooseGrp); break;
            case GGF.System.AppMode.ADM : Navigation.moveFrontPage(Navigation.Page.E10AdminMain); break;
            default: Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
        }
    },
}