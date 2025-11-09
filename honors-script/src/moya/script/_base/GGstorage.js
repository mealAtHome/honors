/* ============================ */
/* 개별 프로젝트 전용 스토리지 함수 모음 */
/* ============================ */
GGstorage.Prj =
{
    /* ========================= */
    /* 이 그룹의 매니저인지 여부를 확인 */
    /* {grpno: 1, val: ${grpmtype}} */
    /* ========================= */
    clearGrpmtype()
    {
        GGstorage.removeItem("grpmtype");
    },
    setGrpmtypeByGrpno(grpno, grpmtype)
    {
        let arr = GGstorage.Prj.getGrpmtypeByGrpno(grpno);
        if(arr == undefined || arr == null)
            arr = [];

        let nowStr = GGdate.toYYYYMMDDHHIISS(new Date());
        let obj =
        {
            GRPNO : grpno,
            GRPMTYPE : grpmtype,
            NOW : nowStr,
        };
        arr.push(obj);
        return GGstorage.setVal("grpmtype", arr);
    },
    getGrpmtypeByGrpno(grpno)
    {
        let obj = GGstorage.getVal("grpmtype");
        if(obj == undefined || obj == null)
            return null;
        for(let i in obj)
        {
            if(obj[i].GRPNO == grpno)
            {
                if(obj[i].NOW != null)
                {
                    /* 1시간이 지났으면 캐시 삭제 */
                    let nowDate = GGdate.fromStr(obj[i].NOW);
                    let seconds = GGdate.getSecondsBetweenDates(nowDate, new Date());
                    if(seconds >= 3600)
                    {
                        obj.slice(i, 1);
                        GGstorage.setVal("grpmtype", obj);
                        return null;
                    }
                }
                else
                {
                    /* NOW 값이 없으면 캐시 삭제 */
                    obj.slice(i, 1);
                    GGstorage.setVal("grpmtype", obj);
                    return null;
                }
                return obj[i].GRPMTYPE;
            }
        }
        return null;
    },
    isManagerOfGrp(grpno)
    {
        let grpmtype = GGstorage.Prj.getGrpmtypeByGrpno(grpno);
        if(grpmtype == null)
        {
            let mGrpMember = Api.GrpMember.selectMeByGrpno(grpno, GGF.none, GGF.none);
            if(mGrpMember == null)
                return false;

            grpmtype = mGrpMember.getGrpmtype();
            GGstorage.Prj.setGrpmtypeByGrpno(grpno, grpmtype);
        }
        if(grpmtype != GGF.GrpMember.Grpmtype.MNG && grpmtype != GGF.GrpMember.Grpmtype.MNGSUB)
            return false;
        return true;
    },
}