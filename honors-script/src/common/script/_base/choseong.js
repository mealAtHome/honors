var Choseong =
{
    getChoArr() { return ["ㄱ","ㄲ","ㄴ","ㄷ","ㄸ","ㄹ","ㅁ","ㅂ","ㅃ","ㅅ","ㅆ","ㅇ","ㅈ","ㅉ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ"]; },
    getChoStr() { return "ㄱㄲㄴㄷㄸㄹㅁㅂㅃㅅㅆㅇㅈㅉㅊㅋㅌㅍㅎ"; },

    /* =================== */
    /* 주어진 문자를 초성으로 변환 */
    /* =================== */
    toCho(str)
    {
        var res = "", // 초성으로 변환
        choArr = Choseong.getChoArr();

        for (var i in str) {
            code = Math.floor((str[i].charCodeAt() - 44032) / 588)
            res += code >= 0 ? choArr[code] : str[i];
        }
        return res;
    },

    /* =================== */
    /* 검색키워드와 주어진 str을 비교해서 일치하는지? */
    /* =================== */
    compare(key, str)
    {
        key = key.toString();
        str = str.toString();

        /* ------------ */
        /* 검색키워드와 비교문자열을 비교해볼 횟수 */
        /* ------------ */
        let loopCnt = (str.length - key.length) + 1;

        /* 검색키워드의 글자가 더 길다면, 절대 일치할 수 없음. */
        if(loopCnt < 0)
            return -1;

        /* ------------ */
        /* 검색키워드 안에 초성이 없다면 평범하게 함수로 비교 */
        /* ------------ */
        let choStr = Choseong.getChoStr();
        let choInKey = [];
        for(var i in key)
        {
            if(choStr.search(key[i]) >= 0)
                choInKey.push(i);
        }

        if(choInKey.length == 0)
            return Choseong.upgradeSearch(key, str);

        /* ------------ */
        /* 검색키워드 안에 초성이 있다면, str을 루프시켜 비교 */
        /* ------------ */
        for(var i = 0; i < loopCnt; i++)
        {
            let compare = str;

            /* i를 기준으로 해서, 초성이 있었던 인덱스에 비교문자열도 초성으로 변경 */
            for(var j = 0; j < choInKey.length; j++)
            {
                let choIdx = i+parseInt(choInKey[j]);
                let cho = Choseong.toCho(compare[choIdx]);
                let head = compare.substring(0,choIdx);
                let tail = compare.substr(choIdx+1);
                compare = head+cho+tail;
            }

            /* 초성을 변환한 뒤 일치하는 인덱스를 리턴한다. */
            if(Choseong.upgradeSearch(key, compare) >= 0)
                return Choseong.upgradeSearch(key, compare);
        }
        return -1;
    },

    /* =================== */
    /* search 시, 대문자는 소문자로 변환하여 비교 */
    /* =================== */
    upgradeSearch(key, str)
    {
        str = str.toUpperCase();
        key = key.toUpperCase();
        return str.search(key);
    }
}