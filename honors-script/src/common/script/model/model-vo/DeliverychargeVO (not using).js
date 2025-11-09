class DeliverychargeVO
{
    constructor(dat)
    {
        this.charge                     = GGC.Common.num(dat.charge, 0);                      /* data */
        this.default_charge             = GGC.Common.num(dat.default_charge, 0);              /* data */
        this.store_charge               = GGC.Common.num(dat.store_charge, 0);                /* data */
        this.weather_charge             = GGC.Common.num(dat.weather_charge, 0);              /* data */
        this.chargeWon                  =        GGC.Common.priceWon(this.charge);            /* custom */
        this.defaultChargeWon           = "＋" + GGC.Common.priceWon(this.default_charge);    /* custom */
        this.storeChargeWon             = "－" + GGC.Common.priceWon(this.store_charge);      /* custom */
        this.weatherChargeWon           = "＋" + GGC.Common.priceWon(this.weather_charge);    /* custom */
    }

    getCharge()                     { return this.charge; }                             /* data */
    getDefaultCharge()              { return this.default_charge; }                     /* data */
    getStoreCharge()                { return this.store_charge; }                       /* data */
    getWeatherCharge()              { return this.weather_charge; }                     /* data */
    getChargeWon()                  { return this.chargeWon; }                          /* custom */
    getDefaultChargeWon()           { return this.defaultChargeWon; }                   /* custom */
    getStoreChargeWon()             { return this.storeChargeWon; }                     /* custom */
    getWeatherChargeWon()           { return this.weatherChargeWon; }                   /* custom */

}

class DeliverychargeVOs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new DeliverychargeVO(dat));
        }
    }
}