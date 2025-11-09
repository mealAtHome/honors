<?php

class MenuPicBO extends _CommonBO
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
    }


    /* ============================== */
    /*  */
    /*
        [*] OPTION
            - keyworkd : [EXECUTOR, KEYWORD]  키워드로 사진조회
            - pickind  : [EXECUTOR, PIC_KIND] 분류별 사진조회
    */
    /* ============================== */
    const selectByKeyword  = "selectByKeyword";
    const selectByPicIndex = "selectByPicIndex";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* option override */
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
            p.storeno
            ,p.pic_index
            ,p.pic_name
            ,p.pic_kind
            ,p.pic_path
            ,p.modidt
            ,p.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPicIndex:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            menu_pic
                        where
                            storeno = '$STORENO' and
                            pic_index = $PIC_INDEX
                    ) p
                ";
                break;
            }
            case self::selectByKeyword:
            {
                $picKindSql = "";
                if($picKind != "ALL")
                    $picKindSql = "and pic_kind = '$PIC_KIND'";

                $from =
                "
                    (
                        select
                            *
                        from
                            menu_pic
                        where
                            storeno = '$STORENO' and
                            pic_name like '%$KEYWORD%'
                            $picKindSql
                    ) p
                ";
                break;
            }
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
            order by
                p.pic_name
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* 유저가 등록한 사진의 분류별 개수를 리턴한다 */
    /*
        [*] OPTION
            - keyword : [EXECUTOR, KEYWORD] : 키워드로 사진 분류별 조회
    */
    /* ========================= */
    // {
    //     /* -------------- */
    //     /* vars */
    //     /* -------------- */
    //     $rslt = array();
    //     $rslt[GGF::CODE]  = Common::SUCCEED;
    //     $rslt["COUNT"] = 0;
    //     $rslt["DATA"]  = array();

    //     /* --------------- */
    //     /* init vars */
    //     /* --------------- */
    //     extract($options);

    //     /* --------------- */
    //     /* exe query */
    //     /* --------------- */
    //     $query =
    //     "
    //         select
    //             'ALL' pic_kind,
    //             count(*) cnt
    //         from
    //             menu_pic
    //         where
    //             storeno = '$STORENO' and
    //             pic_name like '%$KEYWORD%'

    //         union all

    //         select
    //             pic_kind,
    //             count(*) cnt
    //         from
    //             menu_pic
    //         where
    //             storeno = '$STORENO' and
    //             pic_name like '%$KEYWORD%'
    //         group by
    //             pic_kind
    //     ";
    //     return GGsql::select($query, $from, $options);
    // }

    /* ==================== */
    /* 사진 등록 */
    /* ==================== */
    function updateMenuPic($options)
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $picIndex = "";

        try
        {
            /* ========================= */
            /* get vars */
            /* ========================= */
            extract($options);

            /* ========================= */
            /* record insert */
            /* ========================= */
            switch($OPTION)
            {
                case "insert":
                {
                    $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                    $picIndex = $this->getNewIndex($STORENO);
                    $query =
                    "
                        insert into menu_pic
                        (
                            storeno
                            ,pic_index
                            ,pic_name
                            ,pic_kind
                            ,pic_path
                            ,modidt
                            ,regidt
                        )
                        values
                        (
                             '$STORENO'
                            , $picIndex
                            ,'$PIC_NAME'
                            ,'$PIC_KIND'
                            ,''
                            ,now()
                            ,now()
                        )
                    ";
                    $result = GGsql::exeQuery($query);

                    /* --------------- */
                    /* set pic path */
                    /* --------------- */
                    $imgRoot = ROOT_RES."/pic/$STORENO/$picIndex";

                    /* make dir */
                    if(!file_exists($imgRoot))
                    {
                        if (!mkdir($imgRoot, 0777, true))
                            new Exception("cannot make directory");
                    }

                    /* get pic path */
                    $oriPath = "";
                    $thbPath = "";
                    $imgName = Common::getRandomString(10);
                    $thbPath = "$imgRoot/$imgName.png";
                    $oriPath = "$imgRoot/$imgName-origin.png";

                    /* 이미지 이동 및 이미지 얻기 */
                    move_uploaded_file($FILE, $oriPath);
                    $imageInfo    = getimagesize($oriPath);
                    $mime         = $imageInfo['mime'];
                    $beforeResize = null;
                    switch($mime)
                    {
                        case "image/jpeg" : $beforeResize = imagecreatefromjpeg ($oriPath); break;
                        case "image/png"  : $beforeResize = imagecreatefrompng  ($oriPath); break;
                        default :
                        {
                            throw new GGexception("사용할 수 없는 이미지형태 입니다. jpeg 혹은 png를 사용해주세요.");
                        }
                    }

                    /* 썸네일 생성 */
                    $resizePx     = 100;
                    $afterResize  = imagecreatetruecolor($resizePx, $resizePx);
                    imagecopyresampled($afterResize, $beforeResize, 0, 0, 0, 0, $resizePx, $resizePx, imagesx($beforeResize), imagesy($beforeResize));
                    imagejpeg($afterResize, $thbPath);

                    /* DB 업데이트 */
                    $query = "update menu_pic set pic_path='$STORENO/$picIndex/$imgName' where storeno = '$STORENO' and pic_index = $picIndex";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                default:
                {
                    throw new GGexception("(server) no option defined");
                }
            } /* end switch */
        }
        catch(Error $e)
        {
            throw new GGexception("메뉴사진 업데이트에 실패하였습니다.");
        }
        return $picIndex;
    }

    private function getNewIndex($storeno)
    {
        $query = "select coalesce(max(pic_index),0) cnt from menu_pic where storeno = '$storeno'";
        $maxIndex = intval(GGsql::selectCnt($query)) + 1;
        return $maxIndex;
    }

} /* end class */
?>
