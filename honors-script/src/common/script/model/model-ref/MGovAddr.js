class MGovAddr
{
    constructor(dat)
    {
        /* ----------- */
        /* origin field */
        /* ----------- */
        this.admCd         = dat.admCd;
        this.bdKdcd        = dat.bdKdcd;
        this.bdMgtSn       = dat.bdMgtSn;
        this.bdNm          = dat.bdNm;
        this.buldMnnm      = dat.buldMnnm;
        this.buldSlno      = dat.buldSlno;
        this.detBdNmList   = dat.detBdNmList;
        this.emdNo         = dat.emdNo;
        this.liNm          = dat.liNm;
        this.lnbrMnnm      = dat.lnbrMnnm;
        this.lnbrSlno      = dat.lnbrSlno;
        this.mtYn          = dat.mtYn;
        this.rn            = dat.rn;
        this.rnMgtSn       = dat.rnMgtSn;
        this.zipNo         = dat.zipNo;
        this.siNm          = dat.siNm;
        this.sggNm         = dat.sggNm;
        this.emdNm         = dat.emdNm;
        this.roadAddr      = dat.roadAddr;
        this.jibunAddr     = dat.jibunAddr;
        this.engAddr       = dat.engAddr;
        this.roadAddrPart1 = dat.roadAddrPart1;
        this.roadAddrPart2 = dat.roadAddrPart2;
        this.udrtYn        = dat.udrtYn;

    } /* contructor */

    static getModelFromEl(el)
    {
        let dat =
        {
            admCd           : el.attr("adm_cd"),
            bdKdcd          : el.attr("bd_kdcd"),
            bdMgtSn         : el.attr("bd_mgt_sn"),
            bdNm            : el.attr("bd_nm"),
            buldMnnm        : el.attr("buld_mnnm"),
            buldSlno        : el.attr("buld_slno"),
            detBdNmList     : el.attr("det_bd_nm_list"),
            emdNo           : el.attr("emd_no"),
            liNm            : el.attr("li_nm"),
            lnbrMnnm        : el.attr("lnbr_mnnm"),
            lnbrSlno        : el.attr("lnbr_slno"),
            mtYn            : el.attr("mt_yn"),
            rn              : el.attr("rn"),
            rnMgtSn         : el.attr("rn_mgt_sn"),
            zipNo           : el.attr("zip_no"),
            siNm            : el.attr("si_nm"),
            sggNm           : el.attr("sgg_nm"),
            emdNm           : el.attr("emd_nm"),
            roadAddr        : el.attr("road_addr"),
            jibunAddr       : el.attr("jibun_addr"),
            engAddr         : el.attr("eng_addr"),
            roadAddrPart1   : el.attr("road_addr_part1"),
            roadAddrPart2   : el.attr("road_addr_part2"),
            udrtYn          : el.attr("udrt_yn"),
        };
        let mGovAddr = new MGovAddr(dat);
        return mGovAddr;
    }

    make()
    {
        let html =
        `
            <table
                class="MGovAddr-make-tbl-label"
                adm_cd="${this.admCd}"
                bd_kdcd="${this.bdKdcd}"
                bd_mgt_sn="${this.bdMgtSn}"
                bd_nm="${this.bdNm}"
                buld_mnnm="${this.buldMnnm}"
                buld_slno="${this.buldSlno}"
                det_bd_nm_list="${this.detBdNmList}"
                emd_no="${this.emdNo}"
                li_nm="${this.liNm}"
                lnbr_mnnm="${this.lnbrMnnm}"
                lnbr_slno="${this.lnbrSlno}"
                mt_yn="${this.mtYn}"
                rn="${this.rn}"
                rn_mgt_sn="${this.rnMgtSn}"
                zip_no="${this.zipNo}"
                si_nm="${this.siNm}"
                sgg_nm="${this.sggNm}"
                emd_nm="${this.emdNm}"
                road_addr="${this.roadAddr}"
                jibun_addr="${this.jibunAddr}"
                eng_addr="${this.engAddr}"
                road_addr_part1="${this.roadAddrPart1}"
                road_addr_part2="${this.roadAddrPart2}"
                udrt_yn="${this.udrtYn}"
            >
                <tbody>
                    <tr>
                        <td>${this.zipNo}</td>
                        <td>
                            ${this.roadAddr}<br/>
                            <span style="font-size:0.9em; color:#444">${this.jibunAddr}<span>
                        </td>
                    </tr>
                </tbody>
            </table>
        `;
        return html;
    }

}

class MGovAddrs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);

        /* set result code to lang */
        this.isSucceed = false;
        this.msg = "";
        if(ajax.ERROR != undefined)
        {
            switch(ajax.ERROR)
            {
                case "0"     : this.msg = $.i18n("(MGovAddr)errorCode__0"); this.isSucceed = true; break; /* API 성공표시 */
                case "-999"  : this.msg = $.i18n("(MGovAddr)errorCode__-999");  break;
                case "E0001" : this.msg = $.i18n("(MGovAddr)errorCode__E0001"); break;
                case "E0005" : this.msg = $.i18n("(MGovAddr)errorCode__E0005"); break;
                case "E0006" : this.msg = $.i18n("(MGovAddr)errorCode__E0006"); break;
                case "E0008" : this.msg = $.i18n("(MGovAddr)errorCode__E0008"); break;
                case "E0009" : this.msg = $.i18n("(MGovAddr)errorCode__E0009"); break;
                case "E0010" : this.msg = $.i18n("(MGovAddr)errorCode__E0010"); break;
                case "E0011" : this.msg = $.i18n("(MGovAddr)errorCode__E0011"); break;
                case "E0012" : this.msg = $.i18n("(MGovAddr)errorCode__E0012"); break;
                case "E0013" : this.msg = $.i18n("(MGovAddr)errorCode__E0013"); break;
                case "E0014" : this.msg = $.i18n("(MGovAddr)errorCode__E0014"); break;
                case "E0015" : this.msg = $.i18n("(MGovAddr)errorCode__E0015"); break;
            }
        }
        if(this.msg == "")
            this.msg = $.i18n("(MGovAddr)errorCode__unknownErr");

        /* make data */
        this.models = [];
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGovAddr(dat));
        }
    }

    static setInfoToObj(obj, el)
    {
        if(obj == undefined || obj == null)
        {
            Common.toast("시스템에 문제가 발생했습니다. 관리자에게 문의하세요. (주소정보취득)");
            return null;
        }

        let dataEl = $(`${el} > .MGovAddr-make-tbl-label`);
        if(dataEl.length == 0)
        {
            Common.toast("주소를 입력해주세요.");
            return null;
        }

        /* --------------- */
        /* make main data */
        /* --------------- */
        obj.ADDR_ZIPCODE    = dataEl.attr("zip_no");
        obj.ADDR_SIDO       = dataEl.attr("si_nm");
        obj.ADDR_SIGUNGU    = dataEl.attr("sgg_nm");
        obj.ADDR_EMD        = dataEl.attr("emd_nm");
        obj.ADDR_ROAD       = dataEl.attr("road_addr");
        obj.ADDR_JIBUN      = dataEl.attr("jibun_addr");
        obj.ADDR_ROADENG    = dataEl.attr("eng_addr");
        obj.ADDR_ADMCD      = dataEl.attr("adm_cd");
        obj.ADDR_RNMGTSN    = dataEl.attr("rn_mgt_sn");
        obj.ADDR_UDRTYN     = dataEl.attr("udrt_yn");
        obj.ADDR_BULDMNNM   = dataEl.attr("buld_mnnm");
        obj.ADDR_BULDSLNO   = dataEl.attr("buld_slno");
        return obj;
    }

    /* ================================ */
    /* 주소 리스트 출력 */
    /*
        opt :
        {
            [*] code : 페이지코드
            [-] nowPage      : 현재 페이지
            [-] viewCount    : 페이지당 글 수
        }
    */
    /* ================================ */
    makeForSearch(el)
    {
        /* ------------------------ */
        /* check error && check data */
        /* ------------------------ */
        /* ajax 결과확인 */
        if(!this.isSucceed)
            return GGhtml.getCard('failed', this.msg);

        /* ------------------------ */
        /* set variables */
        /* ------------------------ */
        let pagenationHtml = "";
        let html = "";

        /* ------------------------ */
        /* get html */
        /* ------------------------ */
        pagenationHtml = super.getPagenation();

        /* set header */
        html +=
        `
            <div style="margin:1em 0em;">
                <table class="common-tbl-normal MGovAddrs-makeForSearch-tbl-top" tbl-type="normal">
                    <thead>
                        <th>선택</th>
                        <th>우편번호</th>
                        <th>주소</th>
                    </thead>
                    <tbody>
        `;

        /* set body */
        for(let i in this.models)
        {
            let dat = this.models[i];
            html +=
            `
                <tr>
                    <td>
                        <button
                            class="common-btn-outline MGovAddr-makeForSearch-btn-address"
                            adm_cd="${dat.admCd}"
                            bd_kdcd="${dat.bdKdcd}"
                            bd_mgt_sn="${dat.bdMgtSn}"
                            bd_nm="${dat.bdNm}"
                            buld_mnnm="${dat.buldMnnm}"
                            buld_slno="${dat.buldSlno}"
                            det_bd_nm_list="${dat.detBdNmList}"
                            emd_no="${dat.emdNo}"
                            li_nm="${dat.liNm}"
                            lnbr_mnnm="${dat.lnbrMnnm}"
                            lnbr_slno="${dat.lnbrSlno}"
                            mt_yn="${dat.mtYn}"
                            rn="${dat.rn}"
                            rn_mgt_sn="${dat.rnMgtSn}"
                            zip_no="${dat.zipNo}"
                            si_nm="${dat.siNm}"
                            sgg_nm="${dat.sggNm}"
                            emd_nm="${dat.emdNm}"
                            road_addr="${dat.roadAddr}"
                            jibun_addr="${dat.jibunAddr}"
                            eng_addr="${dat.engAddr}"
                            road_addr_part1="${dat.roadAddrPart1}"
                            road_addr_part2="${dat.roadAddrPart2}"
                            udrt_yn="${dat.udrtYn}"
                        >
                            선택
                        </button>
                    </td>
                    <td>${dat.zipNo}</td>
                    <td>
                        ${dat.roadAddr}<br/>
                        <span style="font-size:0.9em; color:#444">${dat.jibunAddr}<span>
                    </td>
                </tr>
            `;
        }
        html += `</tbody></table></div>`;
        $(el).html(pagenationHtml + html + pagenationHtml);

    } /* end makeForSearch */
}