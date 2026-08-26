var GGutils =
{
    Img :
    {
        getIconUrl()     { return ServerInfo.getScriptHost() + "/source/common/res/icon"; },
        getHeartEmpty()  { return GGutils.Img.getIconUrl() + "/heart-empty.png"; },               /* GGutils.Img.getHeartEmpty() */
        getHeartFilled() { return GGutils.Img.getIconUrl() + "/heart-filled.png"; },              /* GGutils.Img.getHeartFilled() */
        getCart()        { return GGutils.Img.getIconUrl() + "/cart.svg"; },                      /* GGutils.Img.getCart() */
    },
    Navigation :
    {
        /* GGutils.Navigation.makeGetFromObject() */
        makeGetFromObject(obj)
        {
            let param = "";
            for (const [key, value] of Object.entries(obj))
            {
                if(param != "")
                    param += "&";

                param += `${key}=${value}`;
            }
            if(param != "")
                param = "?"+param;

            return param;
        }
    },
    Dist :
    {
        getDist(lat1, lon1, lat2, lon2)
        {
            lat1 = lat1*1;
            lon1 = lon1*1;
            lat2 = lat2*1;
            lon2 = lon2*1;
            const R = 6378; // 지구 반지름 (단위: km)
            const dLat = GGutils.Dist.deg2rad(lat2 - lat1);
            const dLon = GGutils.Dist.deg2rad(lon2 - lon1);
            const a =
                Math.sin(dLat/2) *
                Math.sin(dLat/2) +
                Math.cos(GGutils.Dist.deg2rad(lat1)) *
                Math.cos(GGutils.Dist.deg2rad(lat2)) *
                Math.sin(dLon/2) *
                Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; // 두 지점 간의 거리 (단위: km)
            return distance;
        },
        deg2rad(deg)
        {
            return deg * (Math.PI/180);
        },
        distPretty(dist)
        {
            if(dist < 1)
                return Math.round(dist*1000) + "m";
            else
                return Math.round(dist, 1) + "km";
        }
    },
}