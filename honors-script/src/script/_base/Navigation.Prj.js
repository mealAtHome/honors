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
        /* Grp */                       case "Api.Grp.select"                                       : url = serverHost + "/src/data/grp/selectGrp.php"; break;
        /* Grp */                       case "Api.Grp.update"                                       : url = serverHost + "/src/data/grp/updateGrp.php"; break;
        /* GrpfncSponsorship */         case "Api.GrpfncSponsorship.select"                         : url = serverHost + "/src/data/grpfncSponsorship/selectGrpfncSponsorship.php"; break;
        /* GrpfncSponsorship */         case "Api.GrpfncSponsorship.update"                         : url = serverHost + "/src/data/grpfncSponsorship/updateGrpfncSponsorship.php"; break;
        /* GrpfncPurchase */            case "Api.GrpfncPurchase.select"                            : url = serverHost + "/src/data/grpfncPurchase/selectGrpfncPurchase.php"; break;
        /* GrpfncPurchase */            case "Api.GrpfncPurchase.update"                            : url = serverHost + "/src/data/grpfncPurchase/updateGrpfncPurchase.php"; break;
        /* GrpfncLoss */                case "Api.GrpfncLoss.select"                                : url = serverHost + "/src/data/grpfncLoss/selectGrpfncLoss.php"; break;
        /* GrpfncLoss */                case "Api.GrpfncLoss.update"                                : url = serverHost + "/src/data/grpfncLoss/updateGrpfncLoss.php"; break;
        /* Grpfnca */                   case "Api.Grpfnca.select"                                   : url = serverHost + "/src/data/grpfnca/selectGrpfnca.php"; break;
        /* Grpfnca */                   case "Api.Grpfnca.update"                                   : url = serverHost + "/src/data/grpfnca/updateGrpfnca.php"; break;
        /* Grpfnclog */                 case "Api.Grpfnclog.select"                                 : url = serverHost + "/src/data/grpfnclog/selectGrpfnclog.php"; break;
        /* cls */                       case "Api.Cls.select"                                       : url = serverHost + "/src/data/cls/selectCls.php"; break;
        /* cls */                       case "Api.Cls.update"                                       : url = serverHost + "/src/data/cls/updateCls.php"; break;
        /* clslineupa */                case "Api.Clslineupa.select"                                : url = serverHost + "/src/data/clslineupa/selectClslineupa.php"; break;
        /* clslineupa */                case "Api.Clslineupa.update"                                : url = serverHost + "/src/data/clslineupa/updateClslineupa.php"; break;
        /* clslineupb */                case "Api.Clslineupb.select"                                : url = serverHost + "/src/data/clslineupb/selectClslineupb.php"; break;
        /* clslineupb */                case "Api.Clslineupb.update"                                : url = serverHost + "/src/data/clslineupb/updateClslineupb.php"; break;
        /* clslineuptmpa */             case "Api.Clslineuptmpa.select"                             : url = serverHost + "/src/data/clslineuptmpa/selectClslineuptmpa.php"; break;
        /* clslineuptmpa */             case "Api.Clslineuptmpa.update"                             : url = serverHost + "/src/data/clslineuptmpa/updateClslineuptmpa.php"; break;
        /* clslineuptmpb */             case "Api.Clslineuptmpb.select"                             : url = serverHost + "/src/data/clslineuptmpb/selectClslineuptmpb.php"; break;
        /* clslineuptmpb */             case "Api.Clslineuptmpb.update"                             : url = serverHost + "/src/data/clslineuptmpb/updateClslineuptmpb.php"; break;
        /* clslineuptmpc */             case "Api.Clslineuptmpc.select"                             : url = serverHost + "/src/data/clslineuptmpc/selectClslineuptmpc.php"; break;
        /* clspurchase */               case "Api.Clspurchase.select"                               : url = serverHost + "/src/data/clspurchase/selectClspurchase.php"; break;
        /* clspurchase */               case "Api.Clspurchase.update"                               : url = serverHost + "/src/data/clspurchase/updateClspurchase.php"; break;
        /* clspurchasehist */           case "Api.Clspurchasehist.select"                           : url = serverHost + "/src/data/clspurchasehist/selectClspurchasehist.php"; break;
        /* clssettle */                 case "Api.Clssettle.select"                                 : url = serverHost + "/src/data/clssettle/selectClssettle.php"; break;
        /* clssettle */                 case "Api.Clssettle.update"                                 : url = serverHost + "/src/data/clssettle/updateClssettle.php"; break;
        /* clssettlehist */             case "Api.Clssettlehist.select"                             : url = serverHost + "/src/data/clssettlehist/selectClssettlehist.php"; break;
        /* clssettletmp */              case "Api.Clssettletmp.select"                              : url = serverHost + "/src/data/clssettletmp/selectClssettletmp.php"; break;
        /* clssettletmp */              case "Api.Clssettletmp.update"                              : url = serverHost + "/src/data/clssettletmp/updateClssettletmp.php"; break;
        /* Addrcode */                  case "Api.Addrcode.select"                                  : url = serverHost + "/src/data/address/selectAddrcode.php"; break;
        /* GrpIntro */                  case "Api.GrpIntro.select"                                  : url = serverHost + "/src/data/grpIntro/selectGrpIntro.php"; break;
        /* GrpIntro */                  case "Api.GrpIntro.update"                                  : url = serverHost + "/src/data/grpIntro/updateGrpIntro.php"; break;
        /* UserAddr */                  case "Api.UserAddr.select"                                  : url = serverHost + "/src/data/userAddr/selectUserAddr.php"; break;
        /* UserAddr */                  case "Api.UserAddr.update"                                  : url = serverHost + "/src/data/userAddr/updateUserAddr.php"; break;
        /* Grpmtaga */                  case "Api.Grpmtaga.select"                                  : url = serverHost + "/src/data/grpmtaga/selectGrpmtaga.php"; break;
        /* Grpmtaga */                  case "Api.Grpmtaga.update"                                  : url = serverHost + "/src/data/grpmtaga/updateGrpmtaga.php"; break;
        /* Grpmtagb */                  case "Api.Grpmtagb.select"                                  : url = serverHost + "/src/data/grpmtagb/selectGrpmtagb.php"; break;
        /* Grpmtagb */                  case "Api.Grpmtagb.update"                                  : url = serverHost + "/src/data/grpmtagb/updateGrpmtagb.php"; break;
        /* GrpMember */                 case "Api.GrpMember.select"                                 : url = serverHost + "/src/data/grpMember/selectGrpMember.php"; break;
        /* GrpMember */                 case "Api.GrpMember.update"                                 : url = serverHost + "/src/data/grpMember/updateGrpMember.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.select"                        : url = serverHost + "/src/data/grpMemberPointhist/selectGrpMemberPointhist.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.update"                        : url = serverHost + "/src/data/grpMemberPointhist/updateGrpMemberPointhist.php"; break;
        /* Scheduleall */               case "Api.Scheduleall.select"                               : url = serverHost + "/src/data/scheduleall/selectScheduleall.php"; break;
        /* Schedulebyweek */            case "Api.Schedulebyweek.select"                            : url = serverHost + "/src/data/schedulebyweek/selectSchedulebyweek.php"; break;
        /* Bank */                      case "Api.Bank.select"                                      : url = serverHost + "/src/data/bank/selectBank.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.select"                               : url = serverHost + "/src/data/bankaccount/selectBankaccount.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.update"                               : url = serverHost + "/src/data/bankaccount/updateBankaccount.php"; break;
        /* System.Check */              case "Api.System.Check.check"                               : url = serverHost + "/src/data/system/check.php"; break;

    } /* end switch */
    return url;
};

Navigation.Page =
{
    /* A00-user */          A00UserLogin : "ULGN",
    /* A00-user */          A01UserInsert : "UINT",
    /* A00-user */          A02UserPrivacyDelete : "UPRD",
    /* A00-user */          A11UserMainHome : "UMHM",
    /* A00-user */          A12UserMainGrp : "UMGP",
    /* A00-user */          A13UserMainCls : "UMCL",
    /* A00-user */          A14UserMainSettle : "UMST",
    /* A00-user */          A15UserMainManage : "UMMG",
    /* A00-user */          A81UserBankacctList : "UBKL",
    /* A00-user */          A82UserBankacctUpdate : "UBKU",
    /* A10-userManage */    A1011ManagePhonePrivacy : "UMPP",
    /* A10-userManage */    A1021UserAddrList : "UALI",
    /* A10-userManage */    A1022UserAddrUpdate : "UAUP",
    /* A10-userManage */    A1023UserGrpAddrManage : "UGAM",
    /* B00-manager */       B10ManagerChooseGrp : "MGCG",
    /* B00-manager */       B11ManagerMainHome : "MMHM",
    /* B00-manager */       B12ManagerMainClasses : "MMCL",
    /* B00-manager */       B13ManagerMainMembers : "MMMB",
    /* B00-manager */       B14ManagerMainSettle : "MMST",
    /* B00-manager */       B80GrpManageHome : "MMMG",
    /* B00-manager */       B81ManagerBankacctList : "MBKL",
    /* B00-manager */       B82ManagerBankacctUpdate : "MBKU",
    /* B00-manager */       B85ManagerMemberLayering : "MMLY",
    /* B00-manager */       B86ManagerUpdateBacknumberlength : "MUBL",
    /* B00-manager */       B87ManagerUpdateBasecamp : "MUBC",
    /* B00-manager */       B88ManagerUpdateGrpIntro : "MUGI",
    /* B10-grpfnc */        B1000GrpFinanceHome : "GFHM",
    /* B10-grpfnc */        B1010GrpFinanceCapitalList : "GFCL",
    /* B10-grpfnc */        B1011GrpFinanceCapitalUpdate : "GFCU",
    /* B10-grpfnc */        B1020GrpFinanceSponsorList : "GFSL",
    /* B10-grpfnc */        B1021GrpFinanceSponsorUpdate : "GFSU",
    /* B10-grpfnc */        B1030GrpFinancePurchaseList : "GFPL",
    /* B10-grpfnc */        B1031GrpFinancePurchaseUpdate : "GFPU",
    /* B10-grpfnc */        B1040GrpFinanceLossList : "GFLL",
    /* B10-grpfnc */        B1041GrpFinanceLossUpdate : "GFLU",
    /* B70-grpm */          B71GrpMemberDetail : "GMDT",
    /* B70-grpm */          B72GrpMemberMergeTemp : "GMMT",
    /* B70-grpm */          B72GrpMemberTagList : "GMTL",
    /* B70-grpm */          B75GrpMemberTagUpdate : "GMTU",
    /* B70-grpm */          B76GrpMemberTagBulk : "GMTB",
    /* C00-admin */         C00AdminChooseUser : "AACU",
    /* D00-detail */        D10DetailGrp : "DGRP",
    /* D00-detail */        D21DetailClssettle : "DCLS",
    /* D00-detail */        D22DetailClssettleByClsno : "DCSC",
    /* F00-class */         F00Class000Detail : "CLSD",
    /* F00-class */         F00Class001DetailApplyDialog : "CLSA",
    /* F00-class */         F00Class080TextCls : "CLDC",
    /* F00-class */         F00Class081TextApply : "CLDA",
    /* F00-class */         F00Class082TextSettle : "CLDS",
    /* F00-class */         F10ClassUpdate000Default : "CUDE",
    /* F00-class */         F10ClassUpdate010LineupUpdate : "CUTF",
    /* F00-class */         F10ClassUpdate011Lineuptmp : "CULT",
    /* F00-class */         F10ClassUpdate020SettleEdit : "CUST",
    /* F00-class */         F10ClassUpdate021SettleSend : "CUSS",
    /* F00-class */         F10ClassUpdate026Purchase : "CUPU",
    /* F00-class */         F10ClassUpdate030Cancel : "CUCC",
    /* G00-schedule */      G10ScheduleByYear : "GSCY",
    /* G00-schedule */      G20ScheduleByWeek : "GSCW",
    /* S00-choose */        S10ChooseGrpMember : "CHGM",
    /* S00-choose */        S11ChooseCls : "CHCL",
    /* Z00-system */        Z00AppUpdateUrl : "SAPP",
    /* Z00-system */        Z21SystemBoardList : "SBLI",
    /* Z00-system */        Z22SystemBoardDetail : "SBDL",
};


/* ================== */
/* ページコードを利用して、URLを出す */
/* ================== */
Navigation.getURL = function(str)
{
    let host = ServerInfo.getAppHost();
    let url = "";
    switch(str)
    {
        /* A00-user */          case Navigation.Page.A00UserLogin                                  : url = `${host}/app/A00-user/A00UserLogin.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A01UserInsert                                 : url = `${host}/app/A00-user/A01-UserInsert.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A02UserPrivacyDelete                          : url = `${host}/app/A00-user/A02-UserPrivacyDelete.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A11UserMainHome                               : url = `${host}/app/A00-user/A11-UserMainHome.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A12UserMainGrp                                : url = `${host}/app/A00-user/A12-UserMainGrp.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A13UserMainCls                                : url = `${host}/app/A00-user/A13-UserMainCls.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A14UserMainSettle                             : url = `${host}/app/A00-user/A14-UserMainSettle.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A15UserMainManage                             : url = `${host}/app/A00-user/A15-UserMainManage.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A81UserBankacctList                           : url = `${host}/app/A00-user/A81-UserBankacctList.html?${scriptVersion}`; break;
        /* A00-user */          case Navigation.Page.A82UserBankacctUpdate                         : url = `${host}/app/A00-user/A82-UserBankacctUpdate.html?${scriptVersion}`; break;
        /* A10-userManage */    case Navigation.Page.A1011ManagePhonePrivacy                       : url = `${host}/app/A10-userManage/A1011ManagePhonePrivacy.html?${scriptVersion}`; break;
        /* A10-userManage */    case Navigation.Page.A1021UserAddrList                             : url = `${host}/app/A10-userManage/A1021UserAddrList.html?${scriptVersion}`; break;
        /* A10-userManage */    case Navigation.Page.A1022UserAddrUpdate                           : url = `${host}/app/A10-userManage/A1022UserAddrUpdate.html?${scriptVersion}`; break;
        /* A10-userManage */    case Navigation.Page.A1023UserGrpAddrManage                        : url = `${host}/app/A10-userManage/A1023UserGrpAddrManage.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B10ManagerChooseGrp                           : url = `${host}/app/B00-manager/B10-ManagerChooseGrp.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B11ManagerMainHome                            : url = `${host}/app/B00-manager/B11-ManagerMainHome.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B12ManagerMainClasses                         : url = `${host}/app/B00-manager/B12-ManagerMainClasses.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B13ManagerMainMembers                         : url = `${host}/app/B00-manager/B13-ManagerMainMembers.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B14ManagerMainSettle                          : url = `${host}/app/B00-manager/B14-ManagerMainSettle.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B71GrpMemberDetail                            : url = `${host}/app/B00-manager/B71-GrpMemberDetail.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B72GrpMemberMergeTemp                         : url = `${host}/app/B00-manager/B72-GrpMemberMergeTemp.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B80GrpManageHome                              : url = `${host}/app/B00-manager/B80GrpManageHome.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B81ManagerBankacctList                        : url = `${host}/app/B00-manager/B81-ManagerBankacctList.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B82ManagerBankacctUpdate                      : url = `${host}/app/B00-manager/B82-ManagerBankacctUpdate.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B85ManagerMemberLayering                      : url = `${host}/app/B00-manager/B85-ManagerMemberLayering.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B86ManagerUpdateBacknumberlength              : url = `${host}/app/B00-manager/B86ManagerUpdateBacknumberlength.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B87ManagerUpdateBasecamp                      : url = `${host}/app/B00-manager/B87ManagerUpdateBasecamp.html?${scriptVersion}`; break;
        /* B00-manager */       case Navigation.Page.B88ManagerUpdateGrpIntro                      : url = `${host}/app/B00-manager/B88ManagerUpdateGrpIntro.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1000GrpFinanceHome                           : url = `${host}/app/B10-grpfnc/B1000GrpFinanceHome.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1010GrpFinanceCapitalList                    : url = `${host}/app/B10-grpfnc/B1010GrpFinanceCapitalList.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1011GrpFinanceCapitalUpdate                  : url = `${host}/app/B10-grpfnc/B1011GrpFinanceCapitalUpdate.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1020GrpFinanceSponsorList                    : url = `${host}/app/B10-grpfnc/B1020GrpFinanceSponsorList.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1021GrpFinanceSponsorUpdate                  : url = `${host}/app/B10-grpfnc/B1021GrpFinanceSponsorUpdate.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1030GrpFinancePurchaseList                   : url = `${host}/app/B10-grpfnc/B1030GrpFinancePurchaseList.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1031GrpFinancePurchaseUpdate                 : url = `${host}/app/B10-grpfnc/B1031GrpFinancePurchaseUpdate.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1040GrpFinanceLossList                       : url = `${host}/app/B10-grpfnc/B1040GrpFinanceLossList.html?${scriptVersion}`; break;
        /* B10-grpfnc */        case Navigation.Page.B1041GrpFinanceLossUpdate                     : url = `${host}/app/B10-grpfnc/B1041GrpFinanceLossUpdate.html?${scriptVersion}`; break;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberTagList                           : url = `${host}/app/B70-grpm/B72GrpMemberTagList.html?${scriptVersion}`; break;
        /* B70-grpm */          case Navigation.Page.B75GrpMemberTagUpdate                         : url = `${host}/app/B70-grpm/B75GrpMemberTagUpdate.html?${scriptVersion}`; break;
        /* B70-grpm */          case Navigation.Page.B76GrpMemberTagBulk                           : url = `${host}/app/B70-grpm/B76GrpMemberTagBulk.html?${scriptVersion}`; break;
        /* C00-admin */         case Navigation.Page.C00AdminChooseUser                            : url = `${host}/app/C00-admin/C00-AdminChooseUser.html?${scriptVersion}`; break;
        /* D00-detail */        case Navigation.Page.D10DetailGrp                                  : url = `${host}/app/D00-detail/D10-DetailGrp.html?${scriptVersion}`; break;
        /* D00-detail */        case Navigation.Page.D21DetailClssettle                            : url = `${host}/app/D00-detail/D21DetailClssettle.html?${scriptVersion}`; break;
        /* D00-detail */        case Navigation.Page.D22DetailClssettleByClsno                     : url = `${host}/app/D00-detail/D22-DetailClssettleByClsno.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F00Class000Detail                             : url = `${host}/app/F00-class/F00-Class000Detail.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F00Class001DetailApplyDialog                  : url = `${host}/app/F00-class/F00-Class001DetailApplyDialog.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F00Class080TextCls                            : url = `${host}/app/F00-class/F00-Class080TextCls.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F00Class081TextApply                          : url = `${host}/app/F00-class/F00-Class081TextApply.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F00Class082TextSettle                         : url = `${host}/app/F00-class/F00-Class082TextSettle.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate000Default                      : url = `${host}/app/F00-class/F10-ClassUpdate000Default.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate010LineupUpdate                 : url = `${host}/app/F00-class/F10-ClassUpdate010LineupUpdate.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate011Lineuptmp                    : url = `${host}/app/F00-class/F10-ClassUpdate011Lineuptmp.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate020SettleEdit                   : url = `${host}/app/F00-class/F10-ClassUpdate020SettleEdit.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate021SettleSend                   : url = `${host}/app/F00-class/F10-ClassUpdate021SettleSend.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate026Purchase                     : url = `${host}/app/F00-class/F10-ClassUpdate026Purchase.html?${scriptVersion}`; break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate030Cancel                       : url = `${host}/app/F00-class/F10-ClassUpdate030Cancel.html?${scriptVersion}`; break;
        /* G00-schedule */      case Navigation.Page.G10ScheduleByYear                             : url = `${host}/app/G00-schedule/G10ScheduleByYear.html?${scriptVersion}`; break;
        /* G00-schedule */      case Navigation.Page.G20ScheduleByWeek                             : url = `${host}/app/G00-schedule/G20ScheduleByWeek.html?${scriptVersion}`; break;
        /* S00-common */        case Navigation.Page.S10ChooseGrpMember                            : url = `${host}/app/S00-common/S10-ChooseGrpMember.html?${scriptVersion}`; break;
        /* S00-common */        case Navigation.Page.S11ChooseCls                                  : url = `${host}/app/S00-common/S11-ChooseCls.html?${scriptVersion}`; break;
        /* Z00-system */        case Navigation.Page.Z00AppUpdateUrl                               : url = `${host}/app/Z00-system/Z00-AppUpdateUrl.html?${scriptVersion}`; break;
        /* Z00-system */        case Navigation.Page.Z21SystemBoardList                            : url = `${host}/app/Z00-system/Z21-SystemBoardList.html?${scriptVersion}`; break;
        /* Z00-system */        case Navigation.Page.Z22SystemBoardDetail                          : url = `${host}/app/Z00-system/Z22-SystemBoardDetail.html?${scriptVersion}`; break;
    }
    return url;
};

/* ================================== */
/* 각 페이지를 이동할 때, 데이터를 각 페이지에서 가져온다.  */
/* ================================== */
Navigation.getData = function(code)
{
    switch(code)
    {
        /* A00-user */          case Navigation.Page.A00UserLogin : return data = ULGN.Data;
        /* A00-user */          case Navigation.Page.A01UserInsert : return data = UINT.Data;
        /* A00-user */          case Navigation.Page.A02UserPrivacyDelete : return data = UPRD.Data;
        /* A00-user */          case Navigation.Page.A11UserMainHome : return data = UMHM.Data;
        /* A00-user */          case Navigation.Page.A12UserMainGrp : return data = UMGP.Data;
        /* A00-user */          case Navigation.Page.A13UserMainCls : return data = UMCL.Data;
        /* A00-user */          case Navigation.Page.A14UserMainSettle : return data = UMST.Data;
        /* A00-user */          case Navigation.Page.A15UserMainManage : return data = UMMG.Data;
        /* A00-user */          case Navigation.Page.A81UserBankacctList : return data = UBKL.Data;
        /* A00-user */          case Navigation.Page.A82UserBankacctUpdate : return data = UBKU.Data;
        /* A10-userManage */    case Navigation.Page.A1011ManagePhonePrivacy : return data = UMPP.Data;
        /* A10-userManage */    case Navigation.Page.A1021UserAddrList : return data = UALI.Data;
        /* A10-userManage */    case Navigation.Page.A1022UserAddrUpdate : return data = UAUP.Data;
        /* A10-userManage */    case Navigation.Page.A1023UserGrpAddrManage : return data = UGAM.Data;
        /* B00-manager */       case Navigation.Page.B10ManagerChooseGrp : return data = MGCG.Data;
        /* B00-manager */       case Navigation.Page.B11ManagerMainHome : return data = MMHM.Data;
        /* B00-manager */       case Navigation.Page.B12ManagerMainClasses : return data = MMCL.Data;
        /* B00-manager */       case Navigation.Page.B13ManagerMainMembers : return data = MMMB.Data;
        /* B00-manager */       case Navigation.Page.B14ManagerMainSettle : return data = MMST.Data;
        /* B00-manager */       case Navigation.Page.B80GrpManageHome : return data = MMMG.Data;
        /* B00-manager */       case Navigation.Page.B81ManagerBankacctList : return data = MBKL.Data;
        /* B00-manager */       case Navigation.Page.B82ManagerBankacctUpdate : return data = MBKU.Data;
        /* B00-manager */       case Navigation.Page.B85ManagerMemberLayering : return data = MMLY.Data;
        /* B00-manager */       case Navigation.Page.B86ManagerUpdateBacknumberlength : return data = MUBL.Data;
        /* B00-manager */       case Navigation.Page.B87ManagerUpdateBasecamp : return data = MUBC.Data;
        /* B00-manager */       case Navigation.Page.B88ManagerUpdateGrpIntro : return data = MUGI.Data;
        /* B10-grpfnc */        case Navigation.Page.B1000GrpFinanceHome : return data = GFHM.Data;
        /* B10-grpfnc */        case Navigation.Page.B1010GrpFinanceCapitalList : return data = GFCL.Data;
        /* B10-grpfnc */        case Navigation.Page.B1011GrpFinanceCapitalUpdate : return data = GFCU.Data;
        /* B10-grpfnc */        case Navigation.Page.B1020GrpFinanceSponsorList : return data = GFSL.Data;
        /* B10-grpfnc */        case Navigation.Page.B1021GrpFinanceSponsorUpdate : return data = GFSU.Data;
        /* B10-grpfnc */        case Navigation.Page.B1030GrpFinancePurchaseList : return data = GFPL.Data;
        /* B10-grpfnc */        case Navigation.Page.B1031GrpFinancePurchaseUpdate : return data = GFPU.Data;
        /* B10-grpfnc */        case Navigation.Page.B1040GrpFinanceLossList : return data = GFLL.Data;
        /* B10-grpfnc */        case Navigation.Page.B1041GrpFinanceLossUpdate : return data = GFLU.Data;
        /* B70-grpm */          case Navigation.Page.B71GrpMemberDetail : return data = GMDT.Data;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberMergeTemp : return data = GMMT.Data;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberTagList : return data = GMTL.Data;
        /* B70-grpm */          case Navigation.Page.B75GrpMemberTagUpdate : return data = GMTU.Data;
        /* B70-grpm */          case Navigation.Page.B76GrpMemberTagBulk : return data = GMTB.Data;
        /* C00-admin */         case Navigation.Page.C00AdminChooseUser : return data = AACU.Data;
        /* D00-detail */        case Navigation.Page.D10DetailGrp : return data = DGRP.Data;
        /* D00-detail */        case Navigation.Page.D21DetailClssettle : return data = DCLS.Data;
        /* D00-detail */        case Navigation.Page.D22DetailClssettleByClsno : return data = DCSC.Data;
        /* F00-class */         case Navigation.Page.F00Class000Detail : return data = CLSD.Data;
        /* F00-class */         case Navigation.Page.F00Class001DetailApplyDialog : return data = CLSA.Data;
        /* F00-class */         case Navigation.Page.F00Class080TextCls : return data = CLDC.Data;
        /* F00-class */         case Navigation.Page.F00Class081TextApply : return data = CLDA.Data;
        /* F00-class */         case Navigation.Page.F00Class082TextSettle : return data = CLDS.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate000Default : return data = CUDE.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate010LineupUpdate : return data = CUTF.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate011Lineuptmp : return data = CULT.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate020SettleEdit : return data = CUST.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate021SettleSend : return data = CUSS.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate026Purchase : return data = CUPU.Data;
        /* F00-class */         case Navigation.Page.F10ClassUpdate030Cancel : return data = CUCC.Data;
        /* G00-schedule */      case Navigation.Page.G10ScheduleByYear : return data = GSCY.Data;
        /* G00-schedule */      case Navigation.Page.G20ScheduleByWeek : return data = GSCW.Data;
        /* S00-choose */        case Navigation.Page.S10ChooseGrpMember : return data = CHGM.Data;
        /* S00-choose */        case Navigation.Page.S11ChooseCls : return data = CHCL.Data;
        /* Z00-system */        case Navigation.Page.Z00AppUpdateUrl : return data = SAPP.Data;
        /* Z00-system */        case Navigation.Page.Z21SystemBoardList : return data = SBLI.Data;
        /* Z00-system */        case Navigation.Page.Z22SystemBoardDetail : return data = SBDL.Data;
        default:
        {
            console.log("Navigation.getData : 해당 페이지 코드에 대한 데이터를 찾을 수 없습니다. code = " + code);
            return null;
        }
    }
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

    /* 파라미터 추가설정 > 뒤로가기를 한 후, 초기화를 실행할 것인지? */
    let data = Navigation.getData(lastPage.page);
    if(data.executeShowWhenClose != undefined)
    {
        if(data.executeShowWhenClose == false)
        {
            console.log("executeShowWhenClose가 false로 설정되어 있습니다.");
            data.executeShowWhenClose = true;
            return;
        }
    }

    /* 페이지에 대한 show 실행 */
    let lastPageCode = lastPage['page'];
    switch(lastPageCode)
    {
        /* A00-user */          case Navigation.Page.A00UserLogin : ULGN.show(); break;
        /* A00-user */          case Navigation.Page.A01UserInsert : UINT.show(); break;
        /* A00-user */          case Navigation.Page.A02UserPrivacyDelete : UPRD.show(); break;
        /* A00-user */          case Navigation.Page.A11UserMainHome : UMHM.show(); break;
        /* A00-user */          case Navigation.Page.A12UserMainGrp : UMGP.show(); break;
        /* A00-user */          case Navigation.Page.A13UserMainCls : UMCL.show(); break;
        /* A00-user */          case Navigation.Page.A14UserMainSettle : UMST.show(); break;
        /* A00-user */          case Navigation.Page.A15UserMainManage : UMMG.show(); break;
        /* A00-user */          case Navigation.Page.A81UserBankacctList : UBKL.show(); break;
        /* A00-user */          case Navigation.Page.A82UserBankacctUpdate : UBKU.show(); break;
        /* A10-userManage */    case Navigation.Page.A1011ManagePhonePrivacy : UMPP.show(); break;
        /* A10-userManage */    case Navigation.Page.A1021UserAddrList : UALI.show(); break;
        /* A10-userManage */    case Navigation.Page.A1022UserAddrUpdate : UAUP.show(); break;
        /* A10-userManage */    case Navigation.Page.A1023UserGrpAddrManage : UGAM.show(); break;
        /* B00-manager */       case Navigation.Page.B10ManagerChooseGrp : MGCG.show(); break;
        /* B00-manager */       case Navigation.Page.B11ManagerMainHome : MMHM.show(); break;
        /* B00-manager */       case Navigation.Page.B12ManagerMainClasses : MMCL.show(); break;
        /* B00-manager */       case Navigation.Page.B13ManagerMainMembers : MMMB.show(); break;
        /* B00-manager */       case Navigation.Page.B14ManagerMainSettle : MMST.show(); break;
        /* B00-manager */       case Navigation.Page.B80GrpManageHome : MMMG.show(); break;
        /* B00-manager */       case Navigation.Page.B81ManagerBankacctList : MBKL.show(); break;
        /* B00-manager */       case Navigation.Page.B82ManagerBankacctUpdate : MBKU.show(); break;
        /* B00-manager */       case Navigation.Page.B85ManagerMemberLayering : MMLY.show(); break;
        /* B00-manager */       case Navigation.Page.B86ManagerUpdateBacknumberlength : MUBL.show(); break;
        /* B00-manager */       case Navigation.Page.B87ManagerUpdateBasecamp : MUBC.show(); break;
        /* B00-manager */       case Navigation.Page.B88ManagerUpdateGrpIntro : MUGI.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1000GrpFinanceHome : GFHM.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1010GrpFinanceCapitalList : GFCL.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1011GrpFinanceCapitalUpdate : GFCU.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1020GrpFinanceSponsorList : GFSL.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1021GrpFinanceSponsorUpdate : GFSU.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1030GrpFinancePurchaseList : GFPL.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1031GrpFinancePurchaseUpdate : GFPU.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1040GrpFinanceLossList : GFLL.show(); break;
        /* B10-grpfnc */        case Navigation.Page.B1041GrpFinanceLossUpdate : GFLU.show(); break;
        /* B70-grpm */          case Navigation.Page.B71GrpMemberDetail : GMDT.show(); break;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberMergeTemp : GMMT.show(); break;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberTagList : GMTL.show(); break;
        /* B70-grpm */          case Navigation.Page.B75GrpMemberTagUpdate : GMTU.show(); break;
        /* B70-grpm */          case Navigation.Page.B76GrpMemberTagBulk : GMTB.show(); break;
        /* C00-admin */         case Navigation.Page.C00AdminChooseUser : AACU.show(); break;
        /* D00-detail */        case Navigation.Page.D10DetailGrp : DGRP.show(); break;
        /* D00-detail */        case Navigation.Page.D21DetailClssettle : DCLS.show(); break;
        /* D00-detail */        case Navigation.Page.D22DetailClssettleByClsno : DCSC.show(); break;
        /* F00-class */         case Navigation.Page.F00Class000Detail : CLSD.show(); break;
        /* F00-class */         case Navigation.Page.F00Class001DetailApplyDialog : CLSA.show(); break;
        /* F00-class */         case Navigation.Page.F00Class080TextCls : CLDC.show(); break;
        /* F00-class */         case Navigation.Page.F00Class081TextApply : CLDA.show(); break;
        /* F00-class */         case Navigation.Page.F00Class082TextSettle : CLDS.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate000Default : CUDE.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate010LineupUpdate : CUTF.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate011Lineuptmp : CULT.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate020SettleEdit : CUST.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate021SettleSend : CUSS.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate026Purchase : CUPU.show(); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate030Cancel : CUCC.show(); break;
        /* G00-schedule */      case Navigation.Page.G10ScheduleByYear : GSCY.show(); break;
        /* G00-schedule */      case Navigation.Page.G20ScheduleByWeek : GSCW.show(); break;
        /* S00-choose */        case Navigation.Page.S10ChooseGrpMember : CHGM.show(); break;
        /* S00-choose */        case Navigation.Page.S11ChooseCls : CHCL.show(); break;
        /* Z00-system */        case Navigation.Page.Z00AppUpdateUrl : SAPP.show(); break;
        /* Z00-system */        case Navigation.Page.Z21SystemBoardList : SBLI.show(); break;
        /* Z00-system */        case Navigation.Page.Z22SystemBoardDetail : SBDL.show(); break;
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
        /* A00-user */          case Navigation.Page.A00UserLogin : ULGN.close(true); break;
        /* A00-user */          case Navigation.Page.A01UserInsert : UINT.close(true); break;
        /* A00-user */          case Navigation.Page.A02UserPrivacyDelete : UPRD.close(true); break;
        /* A00-user */          case Navigation.Page.A11UserMainHome : UMHM.close(true); break;
        /* A00-user */          case Navigation.Page.A12UserMainGrp : UMGP.close(true); break;
        /* A00-user */          case Navigation.Page.A13UserMainCls : UMCL.close(true); break;
        /* A00-user */          case Navigation.Page.A14UserMainSettle : UMST.close(true); break;
        /* A00-user */          case Navigation.Page.A15UserMainManage : UMMG.close(true); break;
        /* A00-user */          case Navigation.Page.A81UserBankacctList : UBKL.close(true); break;
        /* A00-user */          case Navigation.Page.A82UserBankacctUpdate : UBKU.close(true); break;
        /* A10-userManage */    case Navigation.Page.A1011ManagePhonePrivacy : UMPP.close(true); break;
        /* A10-userManage */    case Navigation.Page.A1021UserAddrList : UALI.close(true); break;
        /* A10-userManage */    case Navigation.Page.A1022UserAddrUpdate : UAUP.close(true); break;
        /* A10-userManage */    case Navigation.Page.A1023UserGrpAddrManage : UGAM.close(true); break;
        /* B00-manager */       case Navigation.Page.B10ManagerChooseGrp : MGCG.close(true); break;
        /* B00-manager */       case Navigation.Page.B11ManagerMainHome : MMHM.close(true); break;
        /* B00-manager */       case Navigation.Page.B12ManagerMainClasses : MMCL.close(true); break;
        /* B00-manager */       case Navigation.Page.B13ManagerMainMembers : MMMB.close(true); break;
        /* B00-manager */       case Navigation.Page.B14ManagerMainSettle : MMST.close(true); break;
        /* B00-manager */       case Navigation.Page.B80GrpManageHome : MMMG.close(true); break;
        /* B00-manager */       case Navigation.Page.B81ManagerBankacctList : MBKL.close(true); break;
        /* B00-manager */       case Navigation.Page.B82ManagerBankacctUpdate : MBKU.close(true); break;
        /* B00-manager */       case Navigation.Page.B85ManagerMemberLayering : MMLY.close(true); break;
        /* B00-manager */       case Navigation.Page.B86ManagerUpdateBacknumberlength : MUBL.close(true); break;
        /* B00-manager */       case Navigation.Page.B87ManagerUpdateBasecamp : MUBC.close(true); break;
        /* B00-manager */       case Navigation.Page.B88ManagerUpdateGrpIntro : MUGI.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1000GrpFinanceHome : GFHM.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1010GrpFinanceCapitalList : GFCL.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1011GrpFinanceCapitalUpdate : GFCU.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1020GrpFinanceSponsorList : GFSL.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1021GrpFinanceSponsorUpdate : GFSU.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1030GrpFinancePurchaseList : GFPL.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1031GrpFinancePurchaseUpdate : GFPU.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1040GrpFinanceLossList : GFLL.close(true); break;
        /* B10-grpfnc */        case Navigation.Page.B1041GrpFinanceLossUpdate : GFLU.close(true); break;
        /* B70-grpm */          case Navigation.Page.B71GrpMemberDetail : GMDT.close(true); break;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberMergeTemp : GMMT.close(true); break;
        /* B70-grpm */          case Navigation.Page.B72GrpMemberTagList : GMTL.close(true); break;
        /* B70-grpm */          case Navigation.Page.B75GrpMemberTagUpdate : GMTU.close(true); break;
        /* B70-grpm */          case Navigation.Page.B76GrpMemberTagBulk : GMTB.close(true); break;
        /* C00-admin */         case Navigation.Page.C00AdminChooseUser : AACU.close(true); break;
        /* D00-detail */        case Navigation.Page.D10DetailGrp : DGRP.close(true); break;
        /* D00-detail */        case Navigation.Page.D21DetailClssettle : DCLS.close(true); break;
        /* D00-detail */        case Navigation.Page.D22DetailClssettleByClsno : DCSC.close(true); break;
        /* F00-class */         case Navigation.Page.F00Class000Detail : CLSD.close(true); break;
        /* F00-class */         case Navigation.Page.F00Class001DetailApplyDialog : CLSA.close(true); break;
        /* F00-class */         case Navigation.Page.F00Class080TextCls : CLDC.close(true); break;
        /* F00-class */         case Navigation.Page.F00Class081TextApply : CLDA.close(true); break;
        /* F00-class */         case Navigation.Page.F00Class082TextSettle : CLDS.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate000Default : CUDE.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate010LineupUpdate : CUTF.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate011Lineuptmp : CULT.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate020SettleEdit : CUST.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate021SettleSend : CUSS.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate026Purchase : CUPU.close(true); break;
        /* F00-class */         case Navigation.Page.F10ClassUpdate030Cancel : CUCC.close(true); break;
        /* G00-schedule */      case Navigation.Page.G10ScheduleByYear : GSCY.close(true); break;
        /* G00-schedule */      case Navigation.Page.G20ScheduleByWeek : GSCW.close(true); break;
        /* S00-choose */        case Navigation.Page.S10ChooseGrpMember : CHGM.close(true); break;
        /* S00-choose */        case Navigation.Page.S11ChooseCls : CHCL.close(true); break;
        /* Z00-system */        case Navigation.Page.Z00AppUpdateUrl : SAPP.close(true); break;
        /* Z00-system */        case Navigation.Page.Z21SystemBoardList : SBLI.close(true); break;
        /* Z00-system */        case Navigation.Page.Z22SystemBoardDetail : SBDL.close(true); break;
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