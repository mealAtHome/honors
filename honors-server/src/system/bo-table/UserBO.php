<?php

class UserBO extends _CommonBO
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
    public function __construct()
    {
        GGnavi::getIdxBO();
        GGnavi::getGrpBO();
        GGnavi::getGrpMemberBO();
    }
    public function setBO()
    {
        $this->idxBO = IdxBO::getInstance();
        $this->grpBO = GrpBO::getInstance();
        $this->grpMemberBO = GrpMemberBO::getInstance();
    }
    private $idxBO;
    private $grpBO;
    private $grpMemberBO;

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__USERNO               = "userno";             /* (pk) char(30) */
    const FIELD__USERTYPE             = "usertype";           /* (  ) enum('normal','temp') default 'normal' */
    const FIELD__ID                   = "id";                 /* (  ) char(50) */
    const FIELD__PW                   = "pw";                 /* (  ) char(255) */
    const FIELD__IMG                  = "img";                /* (  ) char(10) */
    const FIELD__NAME                 = "name";               /* (  ) char(30) */
    const FIELD__BIRTHYEAR            = "birthyear";          /* (  ) int */
    const FIELD__PHONE                = "phone";              /* (  ) char(30) */
    const FIELD__EMAIL                = "email";              /* (  ) char(150) */
    const FIELD__POINT                = "point";              /* (  ) int default 0 */
    const FIELD__BACCNODEFAULT        = "baccnodefault";      /* (  ) int */
    const FIELD__ADRCVFLG             = "adrcvflg";           /* (  ) enum('y','n') default 'n' */
    const FIELD__HASCARFLG            = "hascarflg";          /* (  ) enum('y','n') default 'n' */
    const FIELD__ADDRESS              = "address";            /* (  ) char(50) */
    const FIELD__ADMINFLG             = "adminflg";           /* (  ) enum('y','n') default 'n' */
    const FIELD__PLATFORM             = "platform";           /* (  ) char(3) */
    const FIELD__APIKEY               = "apikey";             /* (  ) char(50) */
    const FIELD__PUSHTOKEN            = "pushtoken";          /* (  ) char(255) */
    const FIELD__DELETEDATA_RQSTDT    = "deletedata_rqstdt";  /* (  ) datetime */
    const FIELD__DELETEDATA_PROCDT    = "deletedata_procdt";  /* (  ) datetime */
    const FIELD__MODIDT               = "modidt";             /* (  ) datetime */
    const FIELD__REGIDT               = "regidt";             /* (  ) datetime */

    /* ========================= */
    /* 유저의 모든 정보 습득 */
    /* ========================= */
    function getByPk     ($USERNO) { return                     GGsql::selectOne("select * from user where userno = '$USERNO'"); }
    function getUser     ($USERNO) { return                     GGsql::selectOne("select * from user where userno = '$USERNO'"); }
    function getUserById ($ID)     { return                     GGsql::selectOne("select * from user where id     = '$ID'"); }
    function getUsername ($USERNO) { return $this->makeUsername(GGsql::selectOne("select * from user where userno = '$USERNO'")); }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function selectUsernoByApikeyForInside ($apikey) { return Common::getDataOneField($this->selectByApikeyForInside($apikey), GGF::USERNO); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside               ($USERNO)      { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByIdForInside               ($ID)          { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByApikeyForInside           ($APIKEY)      { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectMe = "selectMe";
    const selectMeForLogin = "selectMeForLogin";
    const selectByUserno = "selectByUserno"; /* USERNO */
    const selectByPkForInside = "selectByPkForInside"; /* USERNO */
    const selectByIdForInside = "selectByIdForInside"; /* ID */
    const selectByApikeyForInside = "selectByApikeyForInside";
    const selectCntById = "selectCntById"; /* ID */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* get vars */
        /* --------------- */
        $this->setBO();
        extract($options);

        /* overring option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* make sql */
        /* --------------- */
        $query   = "";
        $select  = "";
        $from    = "";
        $select =
        "
              t.userno
            , t.id
            , t.img
            , t.name
            , t.birthyear
            , t.phone
            , t.email
            , t.hascarflg
            , t.address
            , t.adminflg
            , t.baccnodefault
            , t.modidt
            , t.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case UserBO::selectMe                        : { $from = "(select * from user where userno = '$EXECUTOR') t"; break; }
            case UserBO::selectMeForLogin                : { $from = "(select * from user where userno = '$EXECUTOR' and deletedata_rqstdt is null) t"; break; }
            case UserBO::selectByUserno                  : { $from = "(select * from user where userno = '$USERNO') t"; break; }
            case UserBO::selectByPkForInside             : { $from = "(select * from user where userno = '$USERNO') t"; break; }
            case UserBO::selectByIdForInside             : { $from = "(select * from user where id     like '$ID') t"; break; }
            case UserBO::selectByApikeyForInside         : { $from = "(select * from user where apikey like '$APIKEY') t"; break; }
            case UserBO::selectCntById                   : { $from = "(select * from user where id = '$ID') t"; break; }
        }

        /* --------------- */
        /* complete query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
            order by
                t.id asc
        ";

        /* --------------- */
        /* execute query */
        /* --------------- */
        $rslt = GGsql::select($query, $from, $options);
        switch($OPTION)
        {
            case UserBO::selectCntById: { $rslt[GGF::DATA] = []; break; }
        }
        return $rslt;
    }


    /* ==================== */
    /*  */
    /*
     */
    /* ==================== */
    public function insertForInside                 ($ID, $PW, $NAME, $BIRTHYEAR, $PHONE, $EMAIL, $ADRCVFLG, $HASCARFLG, $ADDRESS) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertTempForInside             ($NAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateDeviceInfoByInside        ($USERNO, $PLATFORM, $PUSHTOKEN) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updatePhoneByUsernoForInside    ($USERNO, $PHONE) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function addPointForInside               ($USERNO, $POINT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateBaccnodefaultForInside    ($USERNO, $BACCNODEFAULT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteRecordByPkForInside       ($USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
    const insertTempForInside = "insertTempForInside"; /* temp 유저 작성 */
    const updateBaccnoRefund = "updateBaccnoRefund";
    const updateDeviceInfoByInside = "updateDeviceInfoByInside";
    const updatePhoneByUsernoForInside = "updatePhoneByUsernoForInside";
    const addPointForInside = "addPointForInside";
    const updateBaccnodefaultForInside = "updateBaccnodefaultForInside";
    const deleteUserInfo = "deleteUserInfo";
    const deleteRecordByPkForInside = "deleteRecordByPkForInside";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        $this->setBO();
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* return */
        $rslt = Common::getReturn();

        try
        {
            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::insertForInside:
                {
                    /* make userno */
                    $userno = $this->idxBO->makeUserno();

                    /* ID : 영숫자만 사용가능 */
                    if(preg_match("/[abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789]/", $ID) == false)
                        throw new GGexception("아이디는 영숫자만 사용가능합니다.");

                    /* check id duplicated */
                    $userForId = $this->getUserById($ID);
                    if($userForId != null) { throw new GGexception("이미 사용된 아이디입니다."); } /* ID : 동일한 아이디 불가 */

                    /* check validation */
                    if(Common::isEmpty($ID)) { throw new GGexception("아이디를 입력해주세요."); } /* ID : 아이디 입력 필수 */
                    if(Common::isEmpty($PW)) { throw new GGexception("비밀번호를 입력해주세요."); } /* PW : 비밀번호 입력 필수 */
                    if(Common::isEmpty($NAME)) { throw new GGexception("이름을 입력해주세요."); } /* NAME : 닉네임 입력 필수 */

                    /* 비밀번호 해쉬화 */
                    $pwHash = password_hash($PW, PASSWORD_BCRYPT);

                    /* 전화번호 하이픈 여부 확인 */
                    if(strpos($PHONE, "-") === false)
                    {
                        /* 01011111111 > 010-1111-1111 */
                        $PHONE = preg_replace("/[^0-9]/", "", $PHONE);
                        if(strlen($PHONE) == 11)
                            $PHONE = substr($PHONE, 0, 3)."-".substr($PHONE, 3, 4)."-".substr($PHONE, 7);
                        else if(strlen($PHONE) == 10)
                            $PHONE = substr($PHONE, 0, 3)."-".substr($PHONE, 3, 3)."-".substr($PHONE, 6);
                    }

                    /* for int */
                    $BIRTHYEAR = $BIRTHYEAR == "" ? "null" : $BIRTHYEAR;

                    /* insert */
                    $query =
                    "
                        insert into user
                        (
                              userno
                            , id
                            , pw
                            , img
                            , name
                            , birthyear
                            , phone
                            , email
                            , adrcvflg
                            , hascarflg
                            , address
                            , adminflg
                            , apikey
                            , pushtoken
                            , modidt
                            , regidt
                        )
                        values
                        (
                              '$userno'
                            , '$ID'
                            , '$pwHash'
                            ,  null
                            , '$NAME'
                            ,  $BIRTHYEAR
                            , '$PHONE'
                            , '$EMAIL'
                            , '$ADRCVFLG'
                            , '$HASCARFLG'
                            , '$ADDRESS'
                            , 'n'
                            ,  null
                            ,  null
                            ,  now()
                            ,  now()
                        )
                    ";
                    $result = GGsql::exeQuery($query);

                    /* set user img */
                    GGnavi::getImageUtils();
                    ImageUtils::setImgUser($userno);

                    /* insert grp_member */
                    $this->grpMemberBO->insertTempForInside($userno);

                    /* generate API key */
                    $rslt[GGF::ID] = $ID;
                    $rslt[GGF::USERNO] = $userno;
                    $rslt[GGF::APIKEY] = $this->generateApikey($userno);
                    break;
                }
                case self::insertTempForInside:
                {
                    $userno = $this->idxBO->makeUserno();
                    $id = "temp-".$this->generateTempId();
                    $pwHash = password_hash(Common::getRandomString(10), PASSWORD_BCRYPT);

                    /* insert */
                    $query =
                    "
                        insert into user
                        (
                              userno
                            , usertype
                            , id
                            , pw
                            , img
                            , name
                            , birthyear
                            , phone
                            , email
                            , adrcvflg
                            , hascarflg
                            , address
                            , adminflg
                            , apikey
                            , pushtoken
                            , modidt
                            , regidt
                        )
                        values
                        (
                              '$userno'
                            , 'temp'
                            , '$id'
                            , '$pwHash'
                            ,  null
                            , '$NAME'
                            ,  null
                            , ''
                            , ''
                            , 'n'
                            , 'n'
                            , ''
                            , 'n'
                            ,  null
                            ,  null
                            ,  now()
                            ,  now()
                        )
                    ";
                    $result = GGsql::exeQuery($query);

                    /* set user img */
                    GGnavi::getImageUtils();
                    ImageUtils::setImgUser($userno);

                    /* generate API key */
                    $rslt[GGF::ID] = $id;
                    $rslt[GGF::USERNO] = $userno;
                    break;
                }
                case self::updateBaccnoRefund:
                {
                    $query = "update user set baccno_refund = $BACCNO_REFUND where userno = '$EXECUTOR'";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::updateDeviceInfoByInside:
                {
                    /* update pushToken */
                    if(Common::isEmpty($PUSHTOKEN) == false)
                    {
                        $query = "update user set pushtoken = '$PUSHTOKEN', modidt = now() where userno = '$USERNO'";
                        $result = GGsql::exeQuery($query);
                    }

                    /* is pushToken null? */
                    if(Common::isEmpty($PLATFORM) == false)
                    {
                        $query = "update user set platform = '$PLATFORM', modidt = now() where userno = '$USERNO'";
                        $result = GGsql::exeQuery($query);
                    }
                    break;
                }
                case self::updatePhoneByUsernoForInside:
                {
                    $query = "update user set phone = '$PHONE', modidt = now() where userno = '$USERNO'";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::addPointForInside:
                {
                    $query =
                    "
                        update
                            user
                        set
                              point = point + $POINT
                            , modidt = now()
                        where userno = '$USERNO'
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::updateBaccnodefaultForInside:
                {
                    $query = "update user set baccnodefault = $BACCNODEFAULT where userno = '$USERNO'";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::deleteUserInfo:
                {
                    if($ID == "" || $PW == "")
                        return $rslt;

                    /* 유저 조회 */
                    $user = $this->getUserById($ID);

                    /* 비밀번호 확인 */
                    $pw = Common::getField($user, self::FIELD__PW);
                    if(password_verify($PW, $pw) == false)
                        return $rslt;

                    /* 이미 신청했다면 */
                    $deletedataRqstdt = Common::getField($user, self::FIELD__DELETEDATA_RQSTDT);
                    if(Common::isEmpty($deletedataRqstdt) == false)
                        return $rslt;

                    $query = "update user set deletedata_rqstdt = now() where id = '$ID'";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::deleteRecordByPkForInside:
                {
                    $query = "delete from user where userno = '$USERNO'";
                    GGsql::exeQuery($query);
                    break;
                }
                default:
                {
                    throw new Exception("unknown option");
                }
            }
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $rslt;
    }


    /* ====================== */
    /* update */
    /* 자동로그인을 위한 autologin_key 생성 */
    /* ====================== */
    function generateAutologinkey($userno)
    {
        /* 유저 인덱스에 따른 autologin 가 존재한다면, autologin 키를 조회해서 반납 */
        $users = $this->getUser($userno);
        $autologin = isset($users['autologin']) ? $users['autologin'] : "";
        if($autologin != "")
            return $autologin;

        /* 유저 인덱스에 따른 autologin 가 존재하지 않는다면, 새로운 autologin 키 생성하여 줌 */
        $key = "";
        do
        {
            $randomString = Common::getRandomString(50);
            $query = "select coalesce(count(*),0) cnt from user where autologin = '{$randomString}'";
            $cnt   = intval(GGsql::selectCnt($query));
            if($cnt == 0)
            {
                $query = "update user set autologin = '{$randomString}' where userno = {$userno}";
                $result = GGsql::exeQuery($query);
                $key = $randomString;
                break;
            }
        }
        while (true);
        return $key;
    }

    /* ====================== */
    /* update */
    /* 서비스이용을 위한 apikey 생성 */
    /* ====================== */
    function generateApikey($userno)
    {
        /* vars */
        $key = "";

        /* check apikey exists */
        $user = $this->getUser($userno);
        $apikey = Common::getField($user, self::FIELD__APIKEY);
        if(Common::isEmpty($apikey) == false)
            return $apikey;

        /* new apikey */
        do
        {
            $randomString = Common::getRandomString(50);
            $query = "select count(*) cnt from user where apikey = '$randomString'";
            $result = GGsql::exeQuery($query);
            $row = $result->fetch_assoc();
            if(intval($row['cnt']) == 0)
            {
                $query = "update user set apikey = '$randomString' where userno = '$userno'";
                $result = GGsql::exeQuery($query);
                $key = $randomString;
                break;
            }
        } while (true);
        return $key;
    }

    /* ====================== */
    /* 임시 ID 발급 */
    /* ====================== */
    function generateTempId()
    {
        /* vars */
        $key = "";

        /* new temp id */
        do
        {
            $randomString = Common::getRandomString(30);
            $query = "select count(*) cnt from user where id = '$randomString'";
            $result = GGsql::exeQuery($query);
            $row = $result->fetch_assoc();
            if(intval($row['cnt']) == 0)
            {
                $key = $randomString;
                break;
            }
        } while (true);
        return $key;
    }

    public function makeUsername($user)
    {
        /* check user */
        if($user == null)
            return "";

        /* make username */
        $username = "";
        $name = Common::getField($user, self::FIELD__NAME);
        return $name;
    }


} /* end class */
?>
