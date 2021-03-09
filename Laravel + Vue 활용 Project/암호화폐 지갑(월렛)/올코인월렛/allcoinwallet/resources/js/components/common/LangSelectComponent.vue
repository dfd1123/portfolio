<template>
    <div id="lang_sel_pop" v-show="visible">
        <div class="modal" style="display: block;" @click="$emit('update:visible', false)"></div>
        <div class="popup" style="display: block;">
            <span class="icon">
                <img src="/images/07_icon_popup_icon_earth.svg">
            </span>
            <div class="country_sel_box">
                <h3>Please select your country</h3>
                <ul>
                    <li v-bind:class="{ active: selected === 'kr' }" @click="selected = 'kr'">
                        <div>
                            &nbsp;
                            <img src="/images/07_icon_popup_icon_KR.svg" alt="KOREA">
                            <br>&nbsp;한국
                            <br>(KR)
                            <input
                                type="radio"
                                name="country"
                                class="hide"
                                value="kr"
                                checked="checked"
                            >
                        </div>
                    </li>
                    <li v-bind:class="{ active: selected === 'jp' }" @click="selected = 'jp'">
                        <div>
                            &nbsp;
                            <img src="/images/07_icon_popup_icon_JP.svg" alt="KOREA">
                            <br>&nbsp;日本
                            <br>(JP)
                            <input type="radio" name="country" class="hide" value="jp" checked>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="half_100_btn">
                <button type="button" @click="$emit('update:visible', false)">cancel</button>
                <button type="button" @click="changeLang">select</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["visible"],
    watch: {
        visible: function(newVal, oldVal) {
            this.selected = this.__("LANG");
        }
    },
    data() {
        return {
            selected: "kr"
        };
    },
    methods: {
        changeLang() {
            window.location.href = `/?locale=${this.selected}`;
        }
    }
};
</script>

<style scoped>
#lang_sel_pop {
    position: relative;
    height: 100%;
}

#lang_sel_pop .modal {
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.49);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 3;
}

#lang_sel_pop .popup {
    position: fixed;
    top: 31%;
    left: 50%;
    right: 0;
    z-index: 3;
    margin-top: 0;
    margin-left: -45%;
    width: 90%;
    background-color: white;
    border-radius: 10px;
    padding: 10px 15px;
}

.icon {
    width: 62px;
    margin-top: 20vh;
}

.popup .icon img {
    position: absolute;
    top: -35px;
    left: 50%;
    margin-left: -35px;
    width: 70px;
}

.country_sel_box {
    padding: 35px 0;
}

.country_sel_box h3 {
    text-align: center;
    font-size: 16px;
    font-weight: 700;
    color: #5a5a5a;
    padding-top: 10px;
    padding-bottom: 25px;
    margin-bottom: initial;
}

.country_sel_box ul {
    overflow: hidden;
}

.country_sel_box ul li {
    float: left;
    width: 50%;
    padding: 0 10px;
    text-align: center;
    line-height: 22px;
    font-size: 17px;
}

.country_sel_box ul li div {
    display: block;
    padding: 11px 0;
    border: 2px solid #e5e5e5;
    color: #5a5a5a;
    text-decoration: none;
}

.country_sel_box ul li.active div {
    border: 2px solid #49d094;
}

.country_sel_box ul li img {
    width: 55px;
    vertical-align: baseline;
}

.half_100_btn {
    line-height: unset;
}
</style>
