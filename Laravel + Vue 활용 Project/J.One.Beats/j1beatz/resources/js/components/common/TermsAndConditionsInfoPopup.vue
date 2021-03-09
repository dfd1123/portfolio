<template>
    <div
        v-show="visible"
    >
        <!-- 팝업 레이어 -->
        <div
            class="black-overlay"
            style="display: initial"
        />
        <div class="popup-layer">
            <!-- 약관 정보 팝업 -->
            <div class="popup-ticketinfo popup-02">
                <h4 class="popup-layer-title _another">
                    내용보기
                </h4>
                <div class="popup-layer-txt">
                    <div class="tac">
                        <p>
                            <slot />
                        </p>
                    </div>
                    <button
                        ref="button"
                        type="button"
                        class="popup-layer-close-btn"
                        @click="closeButtonClick"
                    >
                        <span class="none">팝업 닫기버튼</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        visible: {
            type: Boolean,
            default: false
        }
    },
    watch: {
        visible() {
            if (this.visible) {
                this.$nextTick(() => {
                    this.$refs.button.focus();
                    this.$refs.button.blur();
                });
                setTimeout(() => {
                    document.querySelector('.tac').scrollTo(0, 0);
                });
            }
        }
    },
    methods: {
        closeButtonClick() {
            this.$emit("update:visible", false);
            this.$emit("closeButtonClick");
        }
    }
};
</script>

<style lang="scss" scoped>
.tac {
    overflow-y: scroll;
    height: calc(30 * (1vw + 1vh - 1vmin));
		text-align: left;
		word-break: keep-all;
}

.popup-02 {
    width: calc(50 * (1vw + 1vh - 1vmin));
}

/* Font Definitions */
 @font-face
	{font-family:Wingdings;
	panose-1:5 0 0 0 0 0 0 0 0 0;}
@font-face
	{font-family:"Cambria Math";
	panose-1:2 4 5 3 5 4 6 3 2 4;}
@font-face
	{font-family:"맑은 고딕";
	panose-1:2 11 5 3 2 0 0 2 0 4;}
@font-face
	{font-family:HY신명조;
	panose-1:2 3 6 0 0 1 1 1 1 1;}
@font-face
	{font-family:나눔고딕;}
@font-face
	{font-family:"\@맑은 고딕";
	panose-1:2 11 5 3 2 0 0 2 0 4;}
@font-face
	{font-family:"\@HY신명조";
	panose-1:2 3 6 0 0 1 1 1 1 1;}
@font-face
	{font-family:"\@나눔고딕";}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	text-align:justify;
	text-justify:inter-ideograph;
	line-height:115%;
	text-autospace:none;
	word-break:break-hangul;
	font-size:10.0pt;
	font-family:"맑은 고딕";}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{mso-style-link:"머리글 Char";
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	text-align:justify;
	text-justify:inter-ideograph;
	line-height:115%;
	layout-grid-mode:char;
	text-autospace:none;
	word-break:break-hangul;
	font-size:10.0pt;
	font-family:"맑은 고딕";}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{mso-style-link:"바닥글 Char";
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	text-align:justify;
	text-justify:inter-ideograph;
	line-height:115%;
	layout-grid-mode:char;
	text-autospace:none;
	word-break:break-hangul;
	font-size:10.0pt;
	font-family:"맑은 고딕";}
a:link, span.MsoHyperlink
	{color:blue;
	text-decoration:underline;}
a:visited, span.MsoHyperlinkFollowed
	{color:purple;
	text-decoration:underline;}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{mso-style-link:"풍선 도움말 텍스트 Char";
	margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	text-justify:inter-ideograph;
	text-autospace:none;
	word-break:break-hangul;
	font-size:9.0pt;
	font-family:"맑은 고딕";}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:40.0pt;
	text-align:justify;
	text-justify:inter-ideograph;
	line-height:115%;
	text-autospace:none;
	word-break:break-hangul;
	font-size:10.0pt;
	font-family:"맑은 고딕";}
span.Char
	{mso-style-name:"머리글 Char";
	mso-style-link:머리글;}
span.Char0
	{mso-style-name:"바닥글 Char";
	mso-style-link:바닥글;}
span.Char1
	{mso-style-name:"풍선 도움말 텍스트 Char";
	mso-style-link:"풍선 도움말 텍스트";
	font-family:"맑은 고딕";}
span.UnresolvedMention
	{mso-style-name:"Unresolved Mention";
	color:#605E5C;
	background:#E1DFDD;}
.MsoChpDefault
	{font-family:"맑은 고딕";}
.MsoPapDefault
	{margin-bottom:10.0pt;
	text-align:justify;
	text-justify:inter-ideograph;
	line-height:115%;}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:3.0cm 72.0pt 72.0pt 72.0pt;}
 /* List Definitions */
 ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
</style>
