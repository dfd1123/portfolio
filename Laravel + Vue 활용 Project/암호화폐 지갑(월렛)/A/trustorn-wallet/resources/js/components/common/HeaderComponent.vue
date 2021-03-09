<template>
    <header 
        id="header" 
        class="border_bottom" v-show="isVisible" 
        :class="{mainheader:mainHeader, active : mainHeaderActive}"
    >
        <button
            v-if="leftButton === 'menu'"
            type="button"
            class="hd_left"
            @click="$emit('leftMenuButtonClick')"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>
        <button
            v-else-if="leftButton === 'back'"
            type="button"
            class="hd_left to_home"
            @click="$router.replace(leftButtonRoute)"
        >
            <i class="left_arrow fal fa-chevron-left"></i>
        </button>

        <div v-if="center === 'logo'" class="hd_center">
            <a @click.prevent="$emit('logoClick')">
                <img :src="mainHeaderActive == true ? logoColor : logoWhite" alt="logo" />
            </a>
        </div>
        <div v-else-if="center === 'text'" class="hd_center">
            <h3 @click="$emit('centerTextClick')">{{centerText}}</h3>
        </div>

        <div v-if="rightButton === 'lang'" class="hd_right">
            <div class="lang_change" @click="$emit('langButtonClick');">
                {{lang}}&nbsp;
                <i class="fal fa-angle-right"></i>
            </div>
        </div>
        <div v-else-if="rightButton === 'home'" class="hd_right">
            <button type="button" class="go_home_btn" @click="$router.replace(homeButtonRoute)">
                <i class="home_icon fal fa-home"></i>
            </button>
        </div>
    </header>
</template>

<script>
export default {
    props: {
        rightButton: {
            default: "lang"
        },
        center: {
            default: "logo"
        },
        centerText: "",
        leftButton: {
            default: ""
        },
        leftButtonRoute: {
            default: ""
        },
        leftButtonEvent: {
            default: null
        },
        homeButtonRoute: {
            default: "/home"
        },
        mainHeader: {
            default: false
        },
        mainHeaderActive: {
            default: false
        }
    },
    data() {
        return {
            lang: this.__("LANG"),
            isVisible: true,
            logoWhite: '/images/trst-images/logo/header_wh.svg',
            logoColor: '/images/trst-images/logo/header_gr.svg'
        };
    }
};
</script>

<style scoped>

#header {
    width: 100%;
    height: 2.815rem;
    line-height: 2.815rem;
    position: fixed;
    top: 0;
    left: 0;
    text-align: center;
    z-index: 2;
    background: #fff;
    transition: top ease-in-out 0.3s;
}

#header.border_bottom {
    border-bottom: 1px solid #E1E1E1;
}

#header .hd_center {
    text-align: center;
    display: inline-block;
}

#header .hd_center img {
    width: 80px;
}

#header .hd_center h3 {
    padding: 1px 0;
    font-size: 100%;
    margin-bottom: 0;
    line-height: 1.1;
    color: #505050;
    letter-spacing: -0.3px;
    font-weight: 500;
}

#header .hd_right {
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    width:60px;
}

#header .hd_right button {
    background: #fff;
    border: none;
    padding: 2px 4px;
    width: 100%;
    height: 100%;
}

#header .hd_right div {
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    border: 0;
    width: 56px;
    text-align: center;
    letter-spacing: 0.3px;
}

#header button.hd_left {
    background: transparent;
    border: none;
    outline: none;
}

#header .hd_left {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    width: 37px;
    text-align: center;
}

#header button.hd_left span {
    display: block;
    width: 18px;
    height: 2px;
    margin-bottom: 5px;
    background: #49d094;
    position: relative;
    left: 8px;
    top: 3px;
}

#header button.hd_left span:last-child {
    width: 14px;
}

a {
    margin: 0;
    padding: 0;
    font-size: 100%;
    vertical-align: baseline;
    text-decoration: none;
    color: #333;
}

.lang_change {
    text-transform: uppercase;
}

.left_arrow {
    font-size: 17px;
    color: #505050;
    font-weight: 500;
}

.home_icon {
    color: #19B4AA;
    font-size: 21px;
    font-weight: 300;
}

.go_home_btn:focus {
    outline: none;
}

#header.mainheader{
    background-color: transparent;
    border-bottom: 0;
    max-width: 700px;
    left: 50%;
    transform: translateX(-50%);
}

#header.mainheader .hd_center > a{
    overflow: hidden;
    display: inline-block;
    vertical-align: text-bottom;
    position: relative;
    top: 2px;
}

#header.mainheader .hd_center img {
    width: 105px;
    float: left;
}

#header.mainheader button.hd_left span{
    background: #ffffff;
}

#header.mainheader.active{
    transition: background-color 0.3s;
    background-color: white;
    max-width: unset;
    width: 100%;
    left: 0;
    transform: translateX(0);
}

#header.mainheader.active button.hd_left span{
    background-color: #505050;
}

#header.mainheader.active .hd_right div{
    color: #505050;
}
</style>
