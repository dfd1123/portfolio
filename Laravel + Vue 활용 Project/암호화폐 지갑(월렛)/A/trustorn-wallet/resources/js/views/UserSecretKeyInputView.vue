<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: $route.params.backName, params:{kind: 'back'}}"
            center="text"
            :centerText="__('user_secret_keys.input_key')"
            rightButton="home"
        ></header-component>
        <div class="trst-container bgcolor">
            <div class="secretkey-hd-inner">
                <div class="icon_box">
                    <span class="state_img">
                        <img src="/images/trst-images/icon/icon_lock.svg" alt="icon" />
                    </span>
                    <span class="ment_1">
                        <strong>{{__('user_secret_keys.ment_1_1')}}</strong>
                        {{__('user_secret_keys.ment_1_2')}}
                    </span>
                    <span id="code_input">
                        <input
                            type="password"
                            class="keypad_inp1"
                            v-model="code[0]"
                            readonly="readonly"
                        />
                        <input
                            type="password"
                            class="keypad_inp2"
                            v-model="code[1]"
                            readonly="readonly"
                        />
                        <input
                            type="password"
                            class="keypad_inp3"
                            v-model="code[2]"
                            readonly="readonly"
                        />
                        <input
                            type="password"
                            class="keypad_inp4"
                            v-model="code[3]"
                            readonly="readonly"
                        />
                        <input
                            type="password"
                            class="keypad_inp5"
                            v-model="code[4]"
                            readonly="readonly"
                        />
                        <input
                            type="password"
                            class="keypad_inp6"
                            v-model="code[5]"
                            readonly="readonly"
                        />
                    </span>
                </div>
            </div>
        </div>
        <div id="safe_keypad">
            <div class="hr">{{__('user_secret_keys.secure_pad_active')}}</div>
            <div class="hr_1">
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[0])"
                >{{keys[0]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[1])"
                >{{keys[1]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[2])"
                >{{keys[2]}}</span>
            </div>
            <div class="hr_1">
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[3])"
                >{{keys[3]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[4])"
                >{{keys[4]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[5])"
                >{{keys[5]}}</span>
            </div>
            <div class="hr_1">
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[6])"
                >{{keys[6]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[7])"
                >{{keys[7]}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[8])"
                >{{keys[8]}}</span>
            </div>
            <div class="hr_1">
                <span class="pad_reset" style="line-height: 25px;" @click="shuffle()">{{__('user_secret_keys.shuffle')}}</span>
                <span
                    class="number"
                    style="line-height: 25px;"
                    @click="keyAppend(keys[9])"
                >{{keys[9]}}</span>
                <span class="number no" style="line-height: 25px;" @click="keyRemove">
                    <i class="fal fa-long-arrow-left"></i>
                </span>
            </div>
        </div>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import { setTimeout } from "timers";

export default {
    components: {
        "header-component": HeaderComponent
    },
    data() {
        return {
            isSecretKeyconfirmed: false,
            code: [],
            keys: []
        };
    },
    created() {
        this.shuffle();
        this.isSecretKeyconfirmed = false;
    },
    methods: {
        shuffle() {
            const array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 0];
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            this.keys = array;
        },
        keyAppend(key) {
            if (this.code.length <= 6) {
                this.code.push(key);

                if (this.code.length === 6) {
                    const code = [...this.code];
                    setTimeout(() => {
                        this.$router.replace({
                            name: this.$route.params.proceedName,
                            params: {
                                kind: this.$route.params.kind,
                                value: code.join("")
                            }
                        });
                    }, 250);
                }
            }
        },
        keyRemove() {
            this.code.splice(-1, 1);
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 2.815rem;
    margin-bottom: -3.315rem;
    padding-bottom: 3.315rem;
}

.bgcolor{
    background-color: #FAFAFA;
}

.trst-container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.secretkey-hd-inner{
    display: table;
    width: 100%;
    height: calc(100% - 49%);
}

.secretkey-hd-inner .icon_box {
    display: table-cell;
    vertical-align: middle;
}

.secretkey-hd-inner .state_img {
    top: -20px;
    position: relative;
}

.secretkey-hd-inner .state_img img {
    width: 62px;
    box-shadow: 0 4px 10px rgba(195, 215, 215, 0.5);
    border-radius: 50%;
}

.secretkey-hd-inner .ment_1 {
    font-size: 15px;
    padding: 5px 0 16px;
    top: -12px;
    position: relative;
}

.secretkey-hd-inner .ment_1 strong {
    color: #003E5A;
    font-weight: 600;
}

#code_input {
    width: 100%;
}

#code_input input {
    width: 38px;
    height: 38px;
    border: 1px solid #dcdcdc;
    font-size: 45px;
    text-align: center;
    color: #FFB400;
    vertical-align: middle;
    box-sizing: border-box;
    font: 300 1.5em/normal "Helvetica Neue", Helvetica, Arial, sans-serif;
}

#code_input input {
    padding: 0;
    border-radius: 0;
    box-shadow: 0;
    -webkit-appearance: none;
    -webkit-border-radius: 0;
    outline: none;
}

#safe_keypad {
    width: 100%;
    height: 50%;
    padding-top: 25px;
    background-color: #003E5A;
    position: absolute;
    bottom: 0;
    z-index: 0;
}

#safe_keypad .hr {
    font-size: 12px;
    position: absolute;
    top: 0;
    left: 0;
    text-align: center;
    border-bottom: 1px solid rgba(25, 180, 170, 0.2);
    color: #fff;
    width: 100%;
    height: 25px;
    padding: 6px 0;
}

#safe_keypad .hr_1 {
    width: 100%;
    height: 25%;
    display: table;
    justify-content: space-between;
    border-bottom: 1px solid rgba(25, 180, 170, 0.2);
}

#safe_keypad .hr_1 .number,
#safe_keypad .hr_1 .pad_reset {
    display: table-cell;
    vertical-align: middle;
    width: 33.3%;
    text-align: center;
    color: #fff;
    font-size: 20px;
    letter-spacing: -1px;
    border-right: 1px solid rgba(25, 180, 170, 0.2);
    line-height: 150%;
}

#safe_keypad .hr_1 .pad_reset{
    font-weight: 500;
}

#safe_keypad span:active {
    background-color: #00344B;
    transition-duration: 0.1s;
}
</style>
