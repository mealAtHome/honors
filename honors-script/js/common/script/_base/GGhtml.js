/* =============== */
/* 뒤로가기 */
/* =============== */
class GGbackbtnHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <span style="fill:rgb(0,0,0); padding:0px 12px; height:100%; margin-left:6px;">
                <svg
                    style="display:inline-flex; line-height:52px;"
                    width="16px"
                    height="16px"
                    viewBox="0 0 16 16"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                    <defs></defs>
                    <g stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g transform="translate(-32.000000, -32.000000)" fill-rule="nonzero">
                            <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon>
                        </g>
                    </g>
                </svg>
            </span>
        `;
    }
}
customElements.define('gg-backbtn', GGbackbtnHtml);

/* =============== */
/* 뒤로가기 (심플) */
/* =============== */
class GGbackbtnSimpleHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <span style="fill:rgb(2,6,84); padding:0px; height:100%;">
                <svg
                    style="display:inline-flex; line-height:56px;"
                    width="16px"
                    height="16px"
                    viewBox="0 0 16 16"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                    <defs></defs>
                    <g stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g transform="translate(-32.000000, -32.000000)" fill-rule="nonzero">
                            <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon>
                        </g>
                    </g>
                </svg>
            </span>
        `;
    }
}
customElements.define('gg-backbtn-simple', GGbackbtnSimpleHtml);

/* =============== */
/* 카트 */
/* =============== */
class GGcartHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <diV class="common-div-cartFab">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="rgb(255,255,255)"
                    viewBox="0 0 24 24"
                >
                    <path d="M16.53 7l-.564 2h-15.127l-.839-2h16.53zm-14.013 6h12.319l.564-2h-13.722l.839 2zm5.983 5c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm11.305-15l-3.432 12h-13.017l.839 2h13.659l3.474-12h1.929l.743-2h-4.195zm-6.305 15c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5z"/>
                </svg>
            </div>
        `;
    }
}
customElements.define('gg-cart', GGcartHtml);

/* =============== */
/* 리뷰 스코어 */
/* =============== */
class GGscoreHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <div class="GGscore-div-top" style="text-align:center; padding-top:0.5em;">
                <div style="display:block; margin-bottom:0.3em;">
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="1" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="1" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="2" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="2" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="3" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="3" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="4" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="4" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="5" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="5" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                </div>
                <div class="GGscore-div-scoreSub" style="display:none;">
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="1" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="1" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="2" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="2" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="3" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="3" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="4" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="4" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="5" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="5" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="6" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="6" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="7" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="7" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="8" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="8" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="9" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="9" score-type="gold"><rect width="14" height="30"/></svg>
                </div>
                <span class="GGscore-span-score common-span-strong common-span-block"></span>
                <div>
                    <span class="GGscore-div-announce common-span-block" score="1" style="display:none;">도저히 견딜 수 없습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="2" style="display:none;">만족스럽지 못합니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="3" style="display:none;">괜찮았습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="4" style="display:none;">만족스럽습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="5" style="display:none;">아주 만족스럽습니다.</span>
                </div>
            </div>
        `;
    }

    getScore()
    {
        return $(this).attr("score");
    }

    setScore(score)
    {
        let bigScore   = Math.floor(score*1);
        let smallScore = Math.ceil(((score*1) - bigScore) * 10);
        $(this).find(`.commonEvent-svg-GGscore[score-type=gray][score=${bigScore}]`).click();

        if(smallScore > 0)
            $(this).find(`.commonEvent-svg-GGscoreSub[score-type=gray][score=${smallScore}]`).click();
    }
}
customElements.define('gg-score', GGscoreHtml);

/* =============== */
/* 라이더 */
/* =============== */
class IconRiderHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <div class="common-div-svgTop">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="${size}" height="${size}" viewBox="0 0 ${size*2} ${size*2}" style="padding:5px;">
                    <path d="M 103 20 C 98 20 94 24 94 29 C 94 34 98 38 103 38 C 104.7 38 106 36.7 106 35 L 106 23 C 106 21.3 104.7 20 103 20 z M 4 24 C 2.3 24 1 25.3 1 27 L 1 57 C 1 58.7 2.3 60 4 60 L 34 60 C 35.7 60 37 58.7 37 57 L 37 27 C 37 25.3 35.7 24 34 24 L 24 24 C 23.4 24 23 24.4 23 25 L 23 31 C 23 31.6 22.6 32 22 32 L 16 32 C 15.4 32 15 31.6 15 31 L 15 25 C 15 24.4 14.6 24 14 24 L 4 24 z M 74 36 C 72.3 36 71 37.3 71 39 C 71 40.7 72.3 42 74 42 L 84 42 L 84 106 L 66 106 L 66 69 L 66 62 C 66 57.6 62.4 54 58 54 L 43 54 C 41.3 54 40 55.3 40 57 C 40 58.7 41.3 60 43 60 L 58 60 C 59.1 60 60 60.9 60 62 L 60 66 L 4 66 C 2.3 66 1 67.3 1 69 C 1 70.7 2.3 72 4 72 L 26.599609 72 C 20.999609 79.3 12.000781 93.5 11.800781 109 C 11.800781 110.7 13.100781 112 14.800781 112 L 14.900391 112 L 20.199219 112 C 21.599219 120.5 29.000391 127 37.900391 127 C 46.800391 127 54.199609 120.5 55.599609 112 L 93.900391 112 C 102.40039 111.9 110.29922 107.8 115.19922 101 L 121.09961 92.699219 C 121.59961 91.999219 121.79961 91.100781 121.59961 90.300781 C 121.39961 89.500781 120.89922 88.800781 120.19922 88.300781 C 116.49922 86.200781 112.29961 85.099609 108.09961 85.099609 C 100.79961 85.099609 94.300391 88.300781 89.900391 93.300781 L 89.900391 42.599609 C 92.700391 43.699609 94.799219 46.200781 95.199219 49.300781 L 99 79.400391 C 99.2 81.000391 100.70078 82.2 102.30078 82 C 103.90078 81.8 105.10039 80.299219 104.90039 78.699219 L 101.09961 48.599609 C 100.29961 41.399609 94.2 36 87 36 L 74 36 z M 34.300781 72 L 60 72 L 60 106 L 18 106 C 19.5 90.1 31.100781 75.7 34.300781 72 z M 108.19922 91.199219 C 110.29922 91.199219 112.30078 91.599219 114.30078 92.199219 L 110.5 97.599609 C 106.8 102.79961 100.59961 105.99961 94.099609 106.09961 L 90.300781 106.09961 C 91.700781 97.599609 99.199219 91.199219 108.19922 91.199219 z M 122.30273 102.11328 C 122.10234 102.12129 121.89922 102.14922 121.69922 102.19922 C 120.09922 102.59922 119.09961 104.30039 119.59961 105.90039 C 119.99961 107.30039 120.10039 108.80078 119.90039 110.30078 C 119.50039 113.50078 118 116.30078 115.5 118.30078 C 113 120.30078 109.89922 121.20039 106.69922 120.90039 C 103.69922 120.60039 100.9 119.10078 99 116.80078 C 97.9 115.50078 96.000781 115.4 94.800781 116.5 C 93.500781 117.6 93.4 119.49922 94.5 120.69922 C 97.5 124.09922 101.59961 126.30078 106.09961 126.80078 C 106.79961 126.90078 107.49961 126.90039 108.09961 126.90039 C 112.19961 126.90039 116.10078 125.5 119.30078 123 C 123.10078 120 125.40039 115.70039 125.90039 110.90039 C 126.10039 108.70039 126.00039 106.40078 125.40039 104.30078 C 125.05039 102.98828 123.70547 102.05723 122.30273 102.11328 z M 26.400391 112 L 49.599609 112 C 48.299609 117.2 43.6 121 38 121 C 32.4 121 27.700391 117.2 26.400391 112 z"></path>
                </svg>
            <div>
        `;
    }
}
customElements.define('gg-icon-rider', IconRiderHtml);