<?php

abstract class Per00BatchBase
{
    private $systemBatchBO;
    public function __construct()
    {

    }

    public function beforeProcess()
    {
        /* set batchname */
        $batchname = $this->batchname;

        /* redefine log directory */
        if(isset($GLOBALS['LOG_ROOT_FINAL']))
            unset($GLOBALS['LOG_ROOT_FINAL']);
        $logRootBatch = LOG_ROOT."/batch/$batchname";
        $GLOBALS['LOG_ROOT_FINAL'] = $logRootBatch;

        /* set batch start */
        GGnavi::getSystemBatchBO();
        $this->systemBatchBO = SystemBatchBO::getInstance();
        $this->systemBatchBO->insertByBatchnameForInside($batchname);
    }

    public function afterProcess()
    {
        if(isset($GLOBALS['LOG_ROOT_FINAL']))
            unset($GLOBALS['LOG_ROOT_FINAL']);
    }

    public function lock($batchname="")
    {
        $lockSucceed = $this->systemBatchBO->doLock($batchname);
        if(!$lockSucceed)
            throw new GGexception("이미 실행중입니다. 잠시 후 다시 시도해주세요.");
        return true;
    }

    public function unlock($batchname="")
    {
        $this->systemBatchBO->updateUnlockForInside($batchname);
    }
}

?>