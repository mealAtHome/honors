<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpfncaBO();

    /* vars */
    $grpfncaBO = GrpfncaBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpfncaBO->selectByOption($options);

        /* get data, if not exists, make new one */
        switch($OPTION)
        {
            case GrpfncaBO::selectByPk:
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
                $grpfncaBO->makeRecordsIfNotExistsByPkForInside($GRPNO);
                $rslt = $grpfncaBO->selectByOption($options);
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