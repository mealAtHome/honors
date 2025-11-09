var GGtoast =
{
    timeDeft: 3000,
    timeLeft: 0,
    show(msg)
    {
        const toastEl = $("#index-div-toast");
        toastEl.text(msg);
        toastEl.addClass("show");

        GGtoast.timeLeft += GGtoast.timeDeft;
        setTimeout(() =>
        {
            GGtoast.timeLeft -= GGtoast.timeDeft;
            if (GGtoast.timeLeft > 0)
                return;

            toastEl.removeClass("show");
        }, GGtoast.timeDeft);
    },
};
