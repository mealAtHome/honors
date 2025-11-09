Common.Class =
{
    toMulti(model, clz)
    {
        let ajax =
        {
            CODE  : Api.succeed,
            MSG : "",
            COUNT : 1,
            DATA  : [],
        }
        let rslt = new clz(ajax);
        rslt.models.push(model);
        return rslt;
    }
};