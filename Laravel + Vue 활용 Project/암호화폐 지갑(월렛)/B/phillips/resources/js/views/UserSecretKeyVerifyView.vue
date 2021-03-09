<template>
    <div class="ai_wrapper top_0">
        <header-component
            leftButton="back"
            :leftButtonRoute="{name: $route.params.backName, params: {verify: false}}"
            center="text"
            :centerText="__('user_secret_keys.input_key')"
            rightButton="home"
            :homeButtonRoute="{name: 'home', params: { verify: false }}"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="icon_box top_12vh">
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
                <span
                    class="pad_reset"
                    style="line-height: 25px;"
                    @click="shuffle()"
                >{{__('user_secret_keys.shuffle')}}</span>
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

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        if (
            from.path !== "/wallet_send" &&
            from.path !== "/home" &&
            from.path !== "/wallet_buy" &&
            from.path !== "/wallet_sell"
        ) {
            return next("/");
        }

        next();
    },
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
        async keyAppend(key) {
            if (this.code.length <= 6) {
                this.code.push(key);
            }

            if (this.code.length === 6) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/detail/confirm_secret`, {
                        secret_key: this.code.join("")
                    });
                    this.$router.replace({
                        name: this.$route.params.proceedName,
                        params: { verify: true }
                    });
                } catch (e) {
                    this.$swal({
                        type: "error",
                        text: this.__("user_secret_keys.invalid_key")
                    });
                    this.code = [];
                } finally {
                    this.$store.commit("progressComponentHide");
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
    padding-top: 48px;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}

.bgcolor {
    background-color: #000;
    height: 100%;
}

.icon_box {
    position: relative;
    text-align: center;
    height: auto;
    margin-bottom: 7px;
}

.icon_box.top_12vh {
    top: 12vh;
}

.state_img {
    width: 100%;
    display: inline-block;
}

.state_img img {
    width: 62px;
}

.ment_1 {
    font-size: 17px;
    color: #fff;
    padding: 10px 0;
    font-weight: 600;
    float: left;
    width: 100%;
    line-height: initial;
    margin-bottom: 13px;
}

.ment_1 strong {
    color: #2E87C8;
    font-weight: 700;
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
    color: #2E87C8;
    vertical-align: middle;
    box-sizing: border-box;
}

#code_input input {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 1.5em;
    padding: 0;
    border-radius: 0;
    box-shadow: 0;
    -webkit-appearance: none;
    -webkit-border-radius: 0;
}

#code_input input:focus {
    outline: none;
}

#safe_keypad {
    width: 100%;
    height: 55%;
    padding-top: 35px;
    background-color: #fff;
    position: absolute;
    bottom: 0;
    z-index: 0;
}

#safe_keypad .hr {
    font-size: 13px;
    font-weight: 600;
    position: absolute;
    top: 0;
    left: 0;
    text-align: center;
    border-bottom: 1px solid #ddd;
    color: #666;
    width: 100%;
    height: 35px;
    padding: 11px 0;
}

#safe_keypad .hr_1 {
    width: 100%;
    height: 25%;
    display: table;
    justify-content: space-between;
    border-bottom: 1px solid #ddd;
}

#safe_keypad .hr_1 .number,
#safe_keypad .hr_1 .pad_reset {
    display: table-cell;
    vertical-align: middle;
    width: 33.3%;
    text-align: center;
    color: #666;
    font-size: 20px;
    letter-spacing: -1px;
    border-right: 1px solid #ddd;
    line-height: 150%;
}

#safe_keypad span:active {
    background-color: #ddd;
    transition-duration: 0.5s;
}
</style>
