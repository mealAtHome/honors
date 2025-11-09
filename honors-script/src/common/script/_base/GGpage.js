var GGpage =
{
    setCartFab()
    {
        ApiPr.Cart.selectByExecutor()
        .done(function(mCarts)
        {
            if(mCarts.models.length == 0)
            {
                // Common.toastInfo("has no models");
            }
        });
    }
}