<template>
    <header id="header" class="border_bottom" v-show="isVisible">
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
                <img src="/images/header_logo.svg" alt />
            </a>
        </div>
        <div v-else-if="center === 'text'" class="hd_center">
            <h3 @click="$emit('centerTextClick')">{{centerText}}</h3>
        </div>

        <div v-if="rightButton === 'lang'" class="hd_right">
            <div class="lang_change" @click="$emit('langButtonClick');">
                {{lang}}
                <i class="fas fa-caret-down"></i>
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
        }
    },
    data() {
        return {
            lang: this.__("LANG"),
            isVisible: true
        };
    }
};
</script>

<style scoped>
#header {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 2;
    width: 100%;
    background: #fff;
    line-height: 1.1;
    transition: top ease-in-out 0.3s;
}

#header.border_bottom {
    border-bottom: 1px solid #f1f1f1;
}

#header .hd_center {
    text-align: center;
    padding: 12.5px 0;
}

#header .hd_center img {
    width: 80px;
}

#header .hd_center h3 {
    padding: 1px 0;
    font-size: 100%;
    font-weight: 300;
    margin-bottom: 0;
    line-height: 1.1;
}

#header .hd_right {
    position: absolute;
    top: 11px;
    right: 12px;
}

#header .hd_right button {
    background: #fff;
    border: none;
    padding: 2px 4px;
}

#header .hd_right div {
    color: #000;
    font-size: 13px;
    font-weight: 600;
    border: 1px solid #dcdcdc;
    padding: 4px 10px;
    width: 56px;
    text-align: center;
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
    padding: 14px 14px;
}

#header button.hd_left span {
    display: block;
    width: 21px;
    height: 2px;
    margin-bottom: 5px;
    background: #49d094;
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
    font-size: 19px;
    color: #646464;
}

.home_icon {
    color: #0045ab;
    font-size: 20px;
    font-weight: 400;
}

.go_home_btn:focus {
    outline: none;
}
</style>
