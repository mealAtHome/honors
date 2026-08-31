<?php

/* user_addr, uadr : 유저 주소관리 */
class UserAddrBO extends _CommonBO
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
    function readBO()
    {
        GGnavi::getAddrcodeBO();
    }
    function setBO()
    {
        self::readBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['addrcodeBO'] = AddrcodeBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__USERNO        = "userno";        /* (pk) char(30) */
    const FIELD__USERADDRIDX   = "useraddridx";   /* (pk) int */
    const FIELD__USERADDRTITLE = "useraddrtitle"; /* (  ) char(10) */
    const FIELD__USERADDRCODE  = "useraddrcode";  /* (  ) bigint */
    const FIELD__USERBASEPOINT = "userbasepoint"; /* (  ) point */
    const FIELD__USERADDRDEFFLG = "useraddrdefflg"; /* (  ) enum('y','n') */
    const FIELD__MODIDT        = "modidt";        /* (  ) datetime */
    const FIELD__REGDT         = "regdt";         /* (  ) datetime */

    /* ========================= */
    /* consts */
    /* ========================= */
    const USERADDRTITLE_MAX = 10; /* 주소 별칭 최대 글자수 */
    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectDefaultForInside($USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside($USERNO, $USERADDRIDX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByUsernoForUsr = "selectByUsernoForUsr"; /* EXECUTOR */
    const selectDefaultForInside = "selectDefaultForInside";
    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
        extract($options);

        /* override option */
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
              t.userno
            , t.useraddridx
            , t.useraddrtitle
            , t.useraddrcode
            , ST_X(t.userbasepoint) useraddrlat
            , ST_Y(t.userbasepoint) useraddrlng
            , t.useraddrdefflg
            , t.modidt
            , t.regdt
            , ac.addrstrfull    useraddrstr
            , (select count(*) from grp_member gm where gm.userno = t.userno and gm.useraddridx = t.useraddridx) usinggrpcnt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByUsernoForUsr   : { $from = "(select * from user_addr where userno = '$EXECUTOR') t"; break; }
            case self::selectDefaultForInside : { $from = "(select * from user_addr where userno = '$USERNO' and useraddrdefflg = 'y' limit 1) t"; break; }
            case self::selectByPkForInside    : { $from = "(select * from user_addr where userno = '$USERNO' and useraddridx = $USERADDRIDX) t"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
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
                left join _addrcode ac
                    on
                        ac.addrcode = t.useraddrcode
            order by
                t.useraddridx asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function deleteAllReferenceForInside($USERNO, $USERADDRIDX)
    {
        $query = "update grp_member set useraddridx = null where userno = '$USERNO' and useraddridx = $USERADDRIDX";
        GGsql::exeQuery($query);
    }

    /*
        회원가입 시, 첫 기본주소를 등록 (인증 없이 호출되는 내부용 - 아직 로그인 전이라 EXECUTOR가 없음)
    */
    public function insertDefaultForInside($USERNO, $USERADDRCODE, $LAT, $LNG)
    {
        $useraddrcode = intval($USERADDRCODE);
        $lat = floatval($LAT);
        $lng = floatval($LNG);
        $query =
        "
            insert into user_addr (userno, useraddridx, useraddrtitle, useraddrcode, userbasepoint, useraddrdefflg, regdt, modidt)
            values
            (
                  '$USERNO'
                ,  1
                , '기본주소'
                ,  $useraddrcode
                ,  ST_PointFromText('POINT($lat $lng)', 4326)
                , 'y'
                ,  now()
                ,  now()
            )
        ";
        GGsql::exeQuery($query);
    }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertForMng = "insertForMng";
    const updateForMng = "updateForMng";
    const deleteForMng = "deleteForMng";
    const updateDefaultForMng = "updateDefaultForMng";
    protected function update($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* process */
        switch($OPTION)
        {
            case self::insertForMng:
            {
                /* 본인만 가능 */
                $ggAuth->checkMe($EXECUTOR, $USERNO, true);

                /* validation */
                $useraddrtitle = trim($USERADDRTITLE);
                if(Common::isEmpty($useraddrtitle))
                    throw new GGexception("주소 별칭을 입력해주세요.");
                if(mb_strlen($useraddrtitle) > self::USERADDRTITLE_MAX)
                    throw new GGexception("주소 별칭은 ".self::USERADDRTITLE_MAX."자 이내로 입력해주세요.");
                if(Common::isEmpty($USERADDRCODE))
                    throw new GGexception("지역을 선택해주세요.");
                $useraddrcode = intval($USERADDRCODE);
                if(Common::getDataOneField($addrcodeBO->selectByPkForInside($useraddrcode), AddrcodeBO::FIELD__ADDRCODE) == null)
                    throw new GGexception("존재하지 않는 지역입니다.");
                if(Common::isEmpty($LAT) || Common::isEmpty($LNG))
                    throw new GGexception("위치를 선택해주세요.");
                $lat = floatval($LAT);
                $lng = floatval($LNG);
                if($lat < -90 || $lat > 90)   { throw new GGexception("위도 값이 올바르지 않습니다."); }
                if($lng < -180 || $lng > 180) { throw new GGexception("경도 값이 올바르지 않습니다."); }
                $useraddrtitle = GGsql::realEscapeString($useraddrtitle);

                /* 첫 주소면 자동으로 기본주소 */
                $cnt = GGsql::selectCnt("select count(*) cnt from user_addr where userno = '$USERNO'");
                $defflg = $cnt == 0 ? 'y' : 'n';

                /* process */
                $query =
                "
                    insert into user_addr (userno, useraddridx, useraddrtitle, useraddrcode, userbasepoint, useraddrdefflg, regdt, modidt)
                    select
                          '$USERNO'
                        , (select ifnull(max(useraddridx), 0) + 1 from user_addr where userno = '$USERNO')
                        , '$useraddrtitle'
                        ,  $useraddrcode
                        ,  ST_PointFromText('POINT($lat $lng)', 4326)
                        , '$defflg'
                        ,  now()
                        ,  now()
                    from dual
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateForMng:
            {
                /* 본인만 가능 */
                $ggAuth->checkMe($EXECUTOR, $USERNO, true);

                /* validation */
                if(Common::getDataOneField($this->selectByPkForInside($USERNO, intval($USERADDRIDX)), self::FIELD__USERADDRIDX) == null)
                    throw new GGexception("존재하지 않는 주소입니다.");
                $useraddrtitle = trim($USERADDRTITLE);
                if(Common::isEmpty($useraddrtitle))
                    throw new GGexception("주소 별칭을 입력해주세요.");
                if(mb_strlen($useraddrtitle) > self::USERADDRTITLE_MAX)
                    throw new GGexception("주소 별칭은 ".self::USERADDRTITLE_MAX."자 이내로 입력해주세요.");
                if(Common::isEmpty($USERADDRCODE))
                    throw new GGexception("지역을 선택해주세요.");
                $useraddrcode = intval($USERADDRCODE);
                if(Common::getDataOneField($addrcodeBO->selectByPkForInside($useraddrcode), AddrcodeBO::FIELD__ADDRCODE) == null)
                    throw new GGexception("존재하지 않는 지역입니다.");
                if(Common::isEmpty($LAT) || Common::isEmpty($LNG))
                    throw new GGexception("위치를 선택해주세요.");
                $lat = floatval($LAT);
                $lng = floatval($LNG);
                if($lat < -90 || $lat > 90)   { throw new GGexception("위도 값이 올바르지 않습니다."); }
                if($lng < -180 || $lng > 180) { throw new GGexception("경도 값이 올바르지 않습니다."); }
                $useraddrtitle = GGsql::realEscapeString($useraddrtitle);
                $useraddridx = intval($USERADDRIDX);

                /* process */
                $query =
                "
                    update user_addr set
                        useraddrtitle = '$useraddrtitle',
                        useraddrcode = $useraddrcode,
                        userbasepoint = ST_PointFromText('POINT($lat $lng)', 4326),
                        modidt = now()
                    where userno = '$USERNO' and useraddridx = $useraddridx
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteForMng:
            {
                /* 본인만 가능 */
                $ggAuth->checkMe($EXECUTOR, $USERNO, true);
                $useraddridx = intval($USERADDRIDX);

                $row = $this->selectByPkForInside($USERNO, $useraddridx);
                if(Common::getDataOneField($row, self::FIELD__USERADDRIDX) == null)
                    throw new GGexception("존재하지 않는 주소입니다.");
                $wasDefault = Common::getDataOneField($row, self::FIELD__USERADDRDEFFLG) == 'y';

                /* 이 주소를 지정해둔 모임의 지정을 해제 */
                $this->deleteAllReferenceForInside($USERNO, $useraddridx);

                /* 삭제 */
                $query = "delete from user_addr where userno = '$USERNO' and useraddridx = $useraddridx";
                GGsql::exeQuery($query);

                /* 기본주소를 지웠다면, 남은 주소 중 가장 먼저 등록된 것을 기본주소로 승격 */
                if($wasDefault)
                {
                    $nextRow = GGsql::selectOne("select useraddridx from user_addr where userno = '$USERNO' order by useraddridx asc limit 1");
                    $nextIdx = Common::get($nextRow, self::FIELD__USERADDRIDX);
                    if(Common::isNotEmpty($nextIdx))
                    {
                        $query = "update user_addr set useraddrdefflg = 'y' where userno = '$USERNO' and useraddridx = $nextIdx";
                        GGsql::exeQuery($query);
                    }
                }
                break;
            }
            case self::updateDefaultForMng:
            {
                /* 본인만 가능 */
                $ggAuth->checkMe($EXECUTOR, $USERNO, true);
                $useraddridx = intval($USERADDRIDX);

                if(Common::getDataOneField($this->selectByPkForInside($USERNO, $useraddridx), self::FIELD__USERADDRIDX) == null)
                    throw new GGexception("존재하지 않는 주소입니다.");

                $query = "update user_addr set useraddrdefflg = 'n' where userno = '$USERNO'";
                GGsql::exeQuery($query);
                $query = "update user_addr set useraddrdefflg = 'y' where userno = '$USERNO' and useraddridx = $useraddridx";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

}
?>
