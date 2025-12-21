<?php

class SystemBoardBO extends _CommonBO
{
    /* ----- */
    /* singleton */
    /* ----- */
    private static $bo;
    public static function getInstance()
    {
        if(self::$bo == null)
            self::$bo = new static();
        return self::$bo;
    }
    public function setBO()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* field */
    /* ========================= */
    const FIELD__SBINDEX   = "sbindex";    /* int                        / NO  / PRI / / auto_increment */
    const FIELD__SBLEVEL   = "sblevel";    /* enum('info','warn','err')  / YES /     / /                */
    const FIELD__SBTITLE   = "sbtitle";    /* varchar(100)               / YES /     / /                */
    const FIELD__ISOPEN    = "isopen";     /* enum('y','n')              / YES /     / /                */
    const FIELD__ISMAIN    = "ismain";     /* enum('y','n')              / YES /     / /                */
    const FIELD__URL       = "url";        /* varchar(255)               / YES /     / /                */
    const FIELD__MODIDT    = "modidt";     /* datetime                   / YES /     / /                */
    const FIELD__REGIDT    = "regidt";     /* datetime                   / YES /     / /                */

    /* ========================= */
    /* select */
    /* ========================= */
    const selectMain     = "selectMain";       /* 메인에 표시할 공지사항 */
    const selectOpenList = "selectOpenList";   /* 조회가능한 공지사항 전체 */
    const selectOpenByPk = "selectOpenByPk";   /* 인덱스로 선택 */
    protected function select($options, $option="")
    {
        /* set */
        extract($this->setBO());
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
              t.sbindex
            , t.sblevel
            , t.sbtitle
            , t.isopen
            , t.ismain
            , t.url
            , t.modidt
            , t.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectMain     : { $from = "(select * from _system_board where ismain = 'y'          and isopen = 'y' order by regidt desc limit 1) t"; break; }
            case self::selectOpenList : { $from = "(select * from _system_board where                           isopen = 'y') t"; break; }
            case self::selectOpenByPk : { $from = "(select * from _system_board where sbindex = $SBINDEX    and isopen = 'y') t"; break; }
            default: { throw new GGexception("(server) no option defined"); }
        }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
            order by
                t.regidt desc
        ";
        return GGsql::select($query, $from, $options);
    }

}
?>
