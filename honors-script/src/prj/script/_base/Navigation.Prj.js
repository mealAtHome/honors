/* ================== */
/* 함수 이름으로부터 서버의 연결지점을 가져온다. */
/* ================== */
Navigation.getApiUrlByFuncName = function(funcName="")
{
    let url = "";
    let serverHost  = ServerInfo.getServerHost();
    switch(funcName)
    {
        /* SystemBoard */               case "Api.SystemBoard.select"                               : url = serverHost + "/src/data/systemBoard/selectSystemBoard.php"; break;
        /* User */                      case "Api.User.login"                                       : url = serverHost + "/src/data/user/login.php"; break;
        /* User */                      case "Api.User.select"                                      : url = serverHost + "/src/data/user/selectUser.php"; break;
        /* User */                      case "Api.User.insert"                                      : url = serverHost + "/src/data/user/insertUser.php"; break;
        /* User */                      case "Api.User.update"                                      : url = serverHost + "/src/data/user/updateUser.php"; break;
        /* User */                      case "Api.User.deleteUserInfo"                              : url = serverHost + "/src/data/user/deleteUserInfo.php"; break;
        /* Grp  */                      case "Api.Grp.select"                                       : url = serverHost + "/src/data/grp/selectGrp.php"; break;
        /* cls  */                      case "Api.Cls.select"                                       : url = serverHost + "/src/data/cls/selectCls.php"; break;
        /* cls  */                      case "Api.Cls.update"                                       : url = serverHost + "/src/data/cls/updateCls.php"; break;
        /* clslineup2  */               case "Api.Clslineup2.select"                                : url = serverHost + "/src/data/clslineup2/selectClslineup2.php"; break;
        /* clslineup2  */               case "Api.Clslineup2.update"                                : url = serverHost + "/src/data/clslineup2/updateClslineup2.php"; break;
        /* clssettle  */                case "Api.Clssettle.select"                                 : url = serverHost + "/src/data/clssettle/selectClssettle.php"; break;
        /* clssettle  */                case "Api.Clssettle.update"                                 : url = serverHost + "/src/data/clssettle/updateClssettle.php"; break;
        /* GrpMember */                 case "Api.GrpMember.select"                                 : url = serverHost + "/src/data/grpMember/selectGrpMember.php"; break;
        /* GrpMember */                 case "Api.GrpMember.update"                                 : url = serverHost + "/src/data/grpMember/updateGrpMember.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.select"                        : url = serverHost + "/src/data/grpMemberPointhist/selectGrpMemberPointhist.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.update"                        : url = serverHost + "/src/data/grpMemberPointhist/updateGrpMemberPointhist.php"; break;
        /* Scheduleall */               case "Api.Scheduleall.select"                               : url = serverHost + "/src/data/scheduleall/selectScheduleall.php"; break;
        /* Bank */                      case "Api.Bank.select"                                      : url = serverHost + "/src/data/bank/selectBank.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.select"                               : url = serverHost + "/src/data/bankaccount/selectBankaccount.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.update"                               : url = serverHost + "/src/data/bankaccount/updateBankaccount.php"; break;
        /* System.Check */              case "Api.System.Check.check"                               : url = serverHost + "/src/data/system/check.php"; break;

    } /* end switch */
    return url;
};

Navigation.Page =
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
    G10ScheduleByYear : "GSCY",
    G20ScheduleByWeek : "GSCW",
    S10ChooseGrpMember : "CHGM",
    S11ChooseCls : "CHCL",
    Z00AppUpdateUrl : "SAPP",
    Z21SystemBoardList : "SBLI",
    Z22SystemBoardDetail : "SBDL",
};


/* ================== */
/* ページコードを利用して、URLを出す */
/* ================== */
Navigation.getURL = function(str)
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
        case Navigation.Page.G10ScheduleByYear                             : url = "./app/contents/G00-schedule/G10ScheduleByYear.html"; break;
        case Navigation.Page.G20ScheduleByWeek                             : url = "./app/contents/G00-schedule/G20ScheduleByWeek.html"; break;
        case Navigation.Page.S10ChooseGrpMember                            : url = "./app/contents/S00-common/S10-ChooseGrpMember.html"; break;
        case Navigation.Page.S11ChooseCls                                  : url = "./app/contents/S00-common/S11-ChooseCls.html"; break;
        case Navigation.Page.Z00AppUpdateUrl                               : url = "./app/contents/Z00-system/Z00-AppUpdateUrl.html"; break;
        case Navigation.Page.Z21SystemBoardList                            : url = "./app/contents/Z00-system/Z21-SystemBoardList.html"; break;
        case Navigation.Page.Z22SystemBoardDetail                          : url = "./app/contents/Z00-system/Z22-SystemBoardDetail.html"; break;
    }
    return url;
};

/* ================================== */
/* 각 페이지를 이동할 때, 데이터를 각 페이지에서 가져온다.  */
/* ================================== */
Navigation.getData = function(code)
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
        case Navigation.Page.G10ScheduleByYear                      : data = GSCY.Data; break;
        case Navigation.Page.G20ScheduleByWeek                      : data = GSCW.Data; break;
        case Navigation.Page.S10ChooseGrpMember                     : data = CHGM.Data; break;
        case Navigation.Page.S11ChooseCls                           : data = CHCL.Data; break;
        case Navigation.Page.Z00AppUpdateUrl                        : data = SAPP.Data; break;
        case Navigation.Page.Z21SystemBoardList                     : data = SBLI.Data; break;
        case Navigation.Page.Z22SystemBoardDetail                   : data = SBDL.Data; break;
    }
    rslt = data;
    return rslt;
};

/* ======================= */
/* 페이지 가장 최근 스택의 show 함수를 실행시킨다. */
/* ======================= */
Navigation.executeShow = function()
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
        case Navigation.Page.G10ScheduleByYear                        : GSCY.show(); break;
        case Navigation.Page.G20ScheduleByWeek                        : GSCW.show(); break;
        case Navigation.Page.S10ChooseGrpMember                       : CHGM.show(); break;
        case Navigation.Page.S11ChooseCls                             : CHCL.show(); break;
        case Navigation.Page.Z00AppUpdateUrl                          : SAPP.show(); break;
        case Navigation.Page.Z21SystemBoardList                       : SBLI.show(); break;
        case Navigation.Page.Z22SystemBoardDetail                     : SBDL.show(); break;
    }
};

Navigation.executeMoveBack = function()
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
        case Navigation.Page.G10ScheduleByYear                        : GSCY.close(true); break;
        case Navigation.Page.G20ScheduleByWeek                        : GSCW.close(true); break;
        case Navigation.Page.S10ChooseGrpMember                       : CHGM.close(true); break;
        case Navigation.Page.S11ChooseCls                             : CHCL.close(true); break;
        case Navigation.Page.Z00AppUpdateUrl                          : SAPP.close(true); break;
        case Navigation.Page.Z21SystemBoardList                       : SBLI.close(true); break;
        case Navigation.Page.Z22SystemBoardDetail                     : SBDL.close(true); break;
    }
};

/* ================== */
/* goto home */
/* ================== */
Navigation.moveHome = function()
{
    GGstorage.clearPageStack();
    Navigation.moveAfterLogin();
};

/* ================== */
/* goto login */
/* ================== */
Navigation.moveLogin = function()
{
    GGstorage.clearPageStack();
    Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
};
Navigation.loginIntoGrp = function(grpno) { Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno}); };

/* ================== */
/* logout */
/* ================== */
Navigation.moveLogout = function()
{
    GGstorage.clearPageStack();
    Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
};

/* ============================== */
/* 로그인 후, 메인페이지로 이동 */
/* ============================== */
Navigation.moveAfterLogin = function()
{
    GGstorage.Prj.clearGrpmtype();
    let appMode = GGstorage.getAppmode();
    switch(appMode)
    {
        case GGF.System.AppMode.CUS : Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
        case GGF.System.AppMode.MNG : Navigation.moveFrontPage(Navigation.Page.B10ManagerChooseGrp); break;
        case GGF.System.AppMode.ADM : Navigation.moveFrontPage(Navigation.Page.E10AdminMain); break;
        default: Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
    }
}