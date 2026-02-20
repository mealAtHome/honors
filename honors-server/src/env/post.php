<?php

    function get($field, $ifnull="")
    {
        $rslt = "";
        if(isset($_POST[$field]))
            $rslt = $_POST[$field];
        else
            $rslt = $ifnull;
        return $rslt;
    }

    $options = array();
    $options["EXECUTOR"] = isset($EXECUTOR) ? $EXECUTOR : "";

    /* defaults */
    $options["ACCOUNT_BANKCODE"]                                    = get("ACCOUNT_BANKCODE");
    $options["ADDR"]                                                = get("ADDR");
    $options["ADDRESS"]                                             = get("ADDRESS");
    $options["ADRCVFLG"]                                            = get("ADRCVFLG");
    $options["APIKEY"]                                              = get("APIKEY");
    $options["ARR"]                                                 = get("ARR"); /* abstract */
    $options["AUTOLOGIN"]                                           = get("AUTOLOGIN");
    $options["BACCACCT"]                                            = get("BACCACCT");
    $options["BACCCODE"]                                            = get("BACCCODE");
    $options["BACCKEY"]                                             = get("BACCKEY");
    $options["BACCNAME"]                                            = get("BACCNAME");
    $options["BACCNICKNAME"]                                        = get("BACCNICKNAME");
    $options["BACCNO"]                                              = get("BACCNO");
    $options["BACCTYPE"]                                            = get("BACCTYPE");
    $options["BATTINGFLG"]                                          = get("BATTINGFLG");
    $options["BILL"]                                                = get("BILL");
    $options["BIRTHYEAR"]                                           = get("BIRTHYEAR");
    $options["CLSAPPLYCLOSEDT"]                                     = get("CLSAPPLYCLOSEDT");
    $options["CLSAPPLYSTARTDT"]                                     = get("CLSAPPLYSTARTDT");
    $options["CLSBILLAPPLYPRICE"]                                   = get("CLSBILLAPPLYPRICE");
    $options["CLSBILLAPPLYUNIT"]                                    = get("CLSBILLAPPLYUNIT");
    $options["CLSCANCELREASON"]                                     = get("CLSCANCELREASON");
    $options["CLSCLOSEDT"]                                          = get("CLSCLOSEDT");
    $options["CLSCONTENT"]                                          = get("CLSCONTENT");
    $options["CLSGROUND"]                                           = get("CLSGROUND");
    $options["CLSGROUNDADDR"]                                       = get("CLSGROUNDADDR");
    $options["CLSMODIDT"]                                           = get("CLSMODIDT");
    $options["CLSNO"]                                               = get("CLSNO");
    $options["CLSREGDT"]                                            = get("CLSREGDT");
    $options["CLSSETTLEFLG"]                                        = get("CLSSETTLEFLG");
    $options["CLSSTARTDT"]                                          = get("CLSSTARTDT");
    $options["CLSSTATUS"]                                           = get("CLSSTATUS");
    $options["CLSTITLE"]                                            = get("CLSTITLE");
    $options["CLSTYPE"]                                             = get("CLSTYPE");
    $options["CLSUSERNOADM"]                                        = get("CLSUSERNOADM");
    $options["CLSUSERNOSUB"]                                        = get("CLSUSERNOSUB");
    $options["COMMENT"]                                             = get("COMMENT"); /* abstract */
    $options["COUNT"]                                               = get("COUNT"); /* abstract */
    $options["DELETED"]                                             = get("DELETED");
    $options["EMAIL"]                                               = get("EMAIL");
    $options["ETC"]                                                 = get("ETC");
    $options["GRPFNC_ALLTOTAL"]                                     = get("GRPFNC_ALLTOTAL");
    $options["GRPFNC_CAPITALTOTAL"]                                 = get("GRPFNC_CAPITALTOTAL");
    $options["GRPFNC_CLSPURCHASETOTAL"]                             = get("GRPFNC_CLSPURCHASETOTAL");
    $options["GRPFNC_CLSSALESLOSSTOTAL"]                            = get("GRPFNC_CLSSALESLOSSTOTAL");
    $options["GRPFNC_CLSSALESTOTAL"]                                = get("GRPFNC_CLSSALESTOTAL");
    $options["GRPFNC_CLSSALESUNPAIDTOTAL"]                          = get("GRPFNC_CLSSALESUNPAIDTOTAL");
    $options["GRPFNC_LOSSTOTAL"]                                    = get("GRPFNC_LOSSTOTAL");
    $options["GRPFNC_PURCHASETOTAL"]                                = get("GRPFNC_PURCHASETOTAL");
    $options["GRPFNC_SPONSORSHIPTOTAL"]                             = get("GRPFNC_SPONSORSHIPTOTAL");
    $options["GRPMANAGER"]                                          = get("GRPMANAGER");
    $options["GRPNAME"]                                             = get("GRPNAME");
    $options["GRPNO"]                                               = get("GRPNO");
    $options["HASCARFLG"]                                           = get("HASCARFLG");
    $options["ID"]                                                  = get("ID");
    $options["IMG"]                                                 = get("IMG");
    $options["IS_ADMIN"]                                            = get("IS_ADMIN");
    $options["KEYWORD"]                                             = get("KEYWORD");
    $options["MODIDT"]                                              = get("MODIDT");
    $options["MONTH"]                                               = get("MONTH"); /* abstract */
    $options["NAME"]                                                = get("NAME");
    $options["NICK"]                                                = get("NICK");
    $options["OPTION"]                                              = get("OPTION"); /* abstract */
    $options["ORDERNO"]                                             = get("ORDERNO");
    $options["PHONE"]                                               = get("PHONE");
    $options["PLATFORM"]                                            = get("PLATFORM");
    $options["POINT"]                                               = get("POINT");
    $options["POINTMEMO"]                                           = get("POINTMEMO");
    $options["POSITION"]                                            = get("POSITION");
    $options["PUSHTOKEN"]                                           = get("PUSHTOKEN");
    $options["PW"]                                                  = get("PW");
    $options["REGDT"]                                               = get("REGDT");
    $options["REGIDT"]                                              = get("REGIDT");
    $options["SBINDEX"]                                             = get("SBINDEX");
    $options["SCLCLOSEDATE"]                                        = get("SCLCLOSEDATE");
    $options["SCLMONTH"]                                            = get("SCLMONTH");
    $options["SCLSTARTDATE"]                                        = get("SCLSTARTDATE");
    $options["SCLWEEK"]                                             = get("SCLWEEK");
    $options["SCLYEAR"]                                             = get("SCLYEAR");
    $options["SERVICE_LAYER"]                                       = get("SERVICE_LAYER");
    $options["SPONCOMMENT"]                                         = get("SPONCOMMENT");
    $options["SPONCOST"]                                            = get("SPONCOST");
    $options["SPONIDX"]                                             = get("SPONIDX");
    $options["SPONITEM"]                                            = get("SPONITEM");
    $options["SPONTYPE"]                                            = get("SPONTYPE");
    $options["SPONUSERNAME"]                                        = get("SPONUSERNAME");
    $options["SPONUSERNO"]                                          = get("SPONUSERNO");
    $options["TARGET"]                                              = get("TARGET"); /* abstract */
    $options["TEAMNAME"]                                            = get("TEAMNAME");
    $options["TEAMNICK"]                                            = get("TEAMNICK");
    $options["TOKEN"]                                               = get("TOKEN");
    $options["USABLE"]                                              = get("USABLE");
    $options["USERNAME"]                                            = get("USERNAME");
    $options["USERNO"]                                              = get("USERNO");
    $options["USERREGDT"]                                           = get("USERREGDT");
    $options["VALUE"]                                               = get("VALUE"); /* abstract */
    $options["WEEK"]                                                = get("WEEK"); /* abstract */
    $options["YEAR"]                                                = get("YEAR"); /* abstract */
    $options["SPONSORTYPE"]                                         = get("SPONSORTYPE"); /* abstract */

    /* if has pagenum, add pageflg */
    if(isset($_POST["PAGENUM"]))
        $options["PAGEFLG"] = GGF::Y;

    extract($options);

?>