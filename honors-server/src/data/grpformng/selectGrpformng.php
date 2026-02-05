<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpformngBO();

    /* vars */
    $grpformngBO = GrpformngBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpformngBO->selectByOption($options);

        /* get data, if not exists, make new one */
        switch($OPTION)
        {
            case GrpformngBO::selectByPk:
            {
                /* if has record, break */
                $data = Common::getDataOne($rslt);
                if($data != null)
                    break;

                /* if not has record, create new one with recal */
                GGnavi::getGrpBO();
                $grpBO = GrpBO::getInstance();
                $grp = Common::getDataOne($grpBO->selectByPkForInside($GRPNO));
                if($grp == null)
                    break;

                /* make new record */
                $grpformngBO->recalByPkForInside($GRPNO);
                $rslt = $grpformngBO->selectByOption($options);
                break;
            }
        }
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }

    /* print rslt */
    Common::returnRslt($rslt);

?>