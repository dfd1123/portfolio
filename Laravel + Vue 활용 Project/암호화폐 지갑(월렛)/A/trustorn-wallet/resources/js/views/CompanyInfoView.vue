<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            centerText="기타정보"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor scroll_area">
            <div class="sd_box">
                <div class="form_line">
                    <label class="label_hr">회사정보</label>
                    <table class="company_info_table">
                        <tbody>
                            <tr>
                                <th class="label_th">회사명</th>
                                <td class="contents_td">{{company.company}}</td>
                            </tr>
                            <tr>
                                <th class="label_th">대표자명</th>
                                <td class="contents_td">{{company.ceo}}</td>
                            </tr>
                            <tr>
                                <th class="label_th">사업자번호</th>
                                <td class="contents_td">{{company.business_num}}</td>
                            </tr>
                            <tr>
                                <th class="label_th">주소</th>
                                <td class="contents_td">{{company.address}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <span class="copylight">ⓒ Copylight TRUSTON 2019</span>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";

    export default {
        components: {
            "header-component": HeaderComponent
        },
        data() {
            return {
                company: {},
            };
        },
        created(){
            this.fetch_data();
        },
        computed:{

        },
        methods:{
            async fetch_data(){
                try{
                    this.$store.commit("progressComponentShow");
                    this.company = (await axios.get(`/api/company/info`)).data;
                }catch(e){
                    console.log(e);
                }finally{
                    this.$store.commit("progressComponentHide");
                }
            }
        }
    }
</script>

<style scoped>
.ai_wrapper{
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
}

.trst-container{
    position: relative;
}

.company_info_table{
    font-size: 0.85rem;
    color: #505050;
    font-weight: 400;
    width:100%;
}

.company_info_table .label_th{
    color: #003E5A;
    font-weight: 500;
    padding: 8px 0;
    width: 35%;
}

.copylight{
    width: 100%;
    display: inline-block;
    position: absolute;
    bottom: 1.5rem;
    color: #BEC8C8;
    font-size: 11px;
    text-align: center;
    left: 0;
    letter-spacing: 0;
}
</style>
