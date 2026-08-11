GGC.Date =
{
    dateDiff(value) { return GGC.Date.datePretty(value); },
    datePretty(value)
    {
        if(value == null || value == "")
            return "-";

        const today = new Date();
        const timeValue = new Date(value);

        const betweenTime = Math.floor((today.getTime() - timeValue.getTime()) / 1000 / 60);
        if (betweenTime < 1) return '1분 이내';
        if (betweenTime < 60)
            return `${betweenTime}분 전`;

        const betweenTimeHour = Math.floor(betweenTime / 60);
        if (betweenTimeHour < 24)
            return `${betweenTimeHour}시간 전`;

        const betweenTimeDay = Math.floor(betweenTime / 60 / 24);
        if (betweenTimeDay < 31)
            return `${betweenTimeDay}일 전`;

        const betweenTimeMonth = Math.floor(betweenTime / 60 / 24 / 30);
        if (betweenTimeMonth < 12)
            return `${betweenTimeMonth}개월 전`;

        return `${Math.floor(betweenTimeDay / 365)}년 전`;
    },

    getCardByPeriod(startDateStr, endDateStr)
    {
        let now = new Date();
        let startDate = new Date(startDateStr);
        let endDate = new Date(endDateStr);

        let point = GGdate.getPointOfDate(now, startDate, endDate);
        let color = "";
        switch(point)
        {
            case GGF.GGdate.PointOfDate.UPCOMING : color = GGF.Color.NTCE; break; /* 두 기간 이전 */
            case GGF.GGdate.PointOfDate.WITHIN   : color = GGF.Color.PROG; break; /* 두 기간 사이 */
            case GGF.GGdate.PointOfDate.PASSED   : color = GGF.Color.ENDD; break; /* 두 기간 이후 */
        }
        return `<span class="common-card" card-color="${color}">${GGdate.getTextForUpcoming(now, startDate, endDate)}</span>`;
    }
}