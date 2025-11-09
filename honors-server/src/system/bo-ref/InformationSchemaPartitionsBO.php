<?php

/* part : information_schema.partitions */
class InformationSchemaPartitionsBO extends _CommonBO
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
        // GGnavi::getReorderpctResultBO();
    }

    const FIELD__TABLE_SCHEMA   = "table_schema";    /* varchar(64) */
    const FIELD__TABLE_NAME     = "table_name";      /* varchar(64) */
    const FIELD__PARTITION_NAME = "partition_name";  /* varchar(64) */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByTableNameForInside     ($TABLE_NAME)                  { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPartitionNameForInside ($TABLE_NAME, $PARTITION_NAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByTableNameForInside      = "selectByTableNameForInside";
    const selectByPartitionNameForInside  = "selectByPartitionNameForInside";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
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
             part.table_schema
            ,part.table_name
            ,part.partition_name
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByTableNameForInside     : { $from = "(select * FROM partitions WHERE table_name ='$TABLE_NAME') part"; break; }
            case self::selectByPartitionNameForInside : { $from = "(select * FROM partitions WHERE table_name ='$TABLE_NAME' and partition_name = '$PARTITION_NAME') part"; break; }
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
                part.partition_name
        ";

        /* set db_name before sql */
        GGsql::setConnectionAsInformationSchema();
        $rslt = GGsql::select($query, $from, $options);
        GGsql::setConnectionAsNormal();
        return $rslt;
    }

} /* end class */
?>
