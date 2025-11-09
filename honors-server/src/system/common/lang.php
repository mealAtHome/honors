<?php

class Lang
{
    private $lang     = null;
        const KR = "kr";
    const JP = "jp";

    /* =================== */
    /* 초기화 작업 (lang이 정의되어 있지 않으면 post 에서 가져옴, post 에서도 정의되어있지 않으면 에러 출력) */
    /* =================== */
    public function __construct($lang="")
    {
        /* set lang */
        if($lang == "" && isset($_POST['LANG']))
            $this->lang = $_POST['LANG'];
        else if($lang != "")
            $this->lang = $lang;
        else
        {
            Common::returnNoLang("env");
            exit;
        }
    }

    function codeToString($code, $return=true)
    {
        $code = $code."";
        $rslt = "";

        switch($this->lang)
        {
            case self::KR: $rslt = $this->codeToStringKR($code); break;
            case self::JP: $rslt = $this->codeToStringJP($code); break;
            default:
            {
                Common::returnNoLang("env");
                exit;
            }
        }
        return $rslt;
    }

    /* =================== */
    /* 찾는 코드가 없어도 실패하지 않음 */
    /* =================== */
    function get($code)
    {
        $rslt = "";

        switch($this->lang)
        {
            case self::KR: $rslt = $this->codeToStringKR($code); break;
            case self::JP: $rslt = $this->codeToStringJP($code); break;
            default:
                $rslt = $code;
                break;
        }
        return $rslt;
    }

    function codeToStringKR($code)
    {
        $rslt = "";
        $lang = LangKR::$lang;
        if(isset($lang[$code]))
            $rslt = $lang[$code];
        else
            $rslt = Common::UNDEXPECTED_ERROR;

        return $rslt;
    }

    function codeToStringJP($code)
    {
        $rslt = "";
        $lang = LangJP::$lang;
        if(isset($lang[$code]))
            $rslt = $lang[$code];
        else
            $rslt = Common::UNDEXPECTED_ERROR;

        return $rslt;
    }

}

?>