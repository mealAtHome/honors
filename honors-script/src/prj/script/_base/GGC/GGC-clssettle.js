GGC.Clssettle =
{
    memberdepositflg(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "미입금"; break;
        }
        return rslt;
    },
    memberdepositflgdt(val)
    {

    },
    managerdepositflg(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "미입금"; break;
        }
        return rslt;
    },
    managerdepositflgdt(val)
    {

    },

    clssettleStatusPretty(memberdepositflg, managerdepositflg, lossflg)
    {
        let status = "";
        let rslt = "";
        if(managerdepositflg == GGF.Y)                              { status = "succeed"; rslt = "입금확인"; }
        if(managerdepositflg == GGF.N && memberdepositflg == GGF.Y) { status = "warning"; rslt = "입금완료"; }
        if(managerdepositflg == GGF.N && memberdepositflg == GGF.N) { status = "warning"; rslt = "미입금"; }
        if(managerdepositflg == GGF.N && lossflg          == GGF.Y) { status = "failed";  rslt = "손실"; }

        let html = `<span class="common-span-card" span-type="${status}">${rslt}</span>`;
        return html;
    },
}