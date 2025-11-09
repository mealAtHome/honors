class OrderDeliverystatusCntForRiderVO
{
    constructor(dat)
    {
        this.matching = GGC.Common.num(dat.matching, 0); /* data */
        this.delivery = GGC.Common.num(dat.delivery, 0); /* data */
        this.complete = GGC.Common.num(dat.complete, 0); /* data */
    }

    getMatching() { return this.matching; } /* data */
    getDelivery() { return this.delivery; } /* data */
    getComplete() { return this.complete; } /* data */
}

class OrderDeliverystatusCntForRiderVOs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new OrderDeliverystatusCntForRiderVO(dat));
        }
    }
}