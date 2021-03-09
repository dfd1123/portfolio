<template>
    <div class="ai_wrapper">
        <header-component
            leftButton="back"
            leftButtonRoute="/home"
            center="text"
            :centerText="__('wallet_add.title')"
            rightButton="home"
        ></header-component>
        <div class="ai_container bgcolor">
            <div class="coin_name_sch">
                <input
                    type="text"
                    v-model="searchModel"
                    v-on:input="searchIME = $event.target.value"
                    :placeholder="__('wallet_add.placeholder_search_name')"
                />
                <i class="far fa-search"></i>
            </div>
            <div class="s_text coin_s_text">{{__('wallet_add.ment_1')}}</div>
            <div class="name_line">
                <span>{{__('wallet_add.coin_name')}}</span>
                <span>{{__('wallet_add.own_coin')}}</span>
            </div>
            <div class="coin_list plus_list list">
                <ul>
                    <li
                        v-for="coin in filteredCoins"
                        :key="coin.symbol"
                        v-bind:class="{active: selected === coin.symbol}"
                        @click="selected = coin.symbol"
                    >
                        {{coin.name}}({{coin.symbol.toUpperCase()}})
                        <span>{{coin.balance}}</span>
                    </li>
                </ul>
            </div>
        </div>
        <footer-component
            :buttonText="__('wallet_add.select')"
            v-on:buttonClick="selectButtonClick"
            :active="selected"
        ></footer-component>
    </div>
</template>

<script>
import HeaderComponent from "../components/common/HeaderComponent";
import FooterComponent from "../components/common/FooterComponent";

export default {
    beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    components: {
        "header-component": HeaderComponent,
        "footer-component": FooterComponent
    },
    data() {
        return {
            searchIME: "",
            searchModel: "",
            selected: "",
            coins: []
        };
    },
    async created() {
        await this.fetchData();
    },
    computed: {
        filteredCoins() {
            return this.coins.filter(coin => {
                return (
                    coin.name
                        .toLowerCase()
                        .includes(this.searchIME.toLowerCase()) ||
                    coin.name
                        .toLowerCase()
                        .includes(this.searchModel.toLowerCase()) ||
                    coin.symbol
                        .toLowerCase()
                        .includes(this.searchModel.toLowerCase())
                );
            });
        }
    },
    methods: {
        async fetchData() {
            try {
                this.$store.commit("progressComponentShow");

                const coins = (await axios.get("/api/wallet/coins")).data;
                const favorSymbols = this.$store.state.favors.map(favor => {
                    return favor.symbol;
                });

                this.coins = coins
                    .filter(coin => {
                        return !favorSymbols.includes(coin.symbol);
                    })
                    .map(coin => {
                        coin.name = this.__(`coin.${coin.symbol}`);
                        return coin;
                    });
            } finally {
                this.$store.commit("progressComponentHide");
            }
        },
        async selectButtonClick() {
            if (this.selected) {
                try {
                    this.$store.commit("progressComponentShow");

                    await axios.post(`/api/favors`, {
                        symbol: this.selected
                    });

                    this.$store.commit(
                        "favors",
                        (await axios.get(`/api/favors`)).data
                    );

                    await this.fetchData();
                } finally {
                    this.$store.commit("progressComponentHide");
                }
            }
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

.coin_name_sch {
    width: 80%;
    height: 40px;
    text-align: center;
    border: 1px solid #dcdcdc;
    margin: 0 auto;
    position: relative;
    top: 4vh;
}

.coin_name_sch input {
    width: 100%;
    height: 100%;
    border: 0;
    padding: 5px 38px 5px 10px;
    font-size: 14px;
    position: absolute;
    top: 0;
    left: 0;
}

.coin_name_sch i {
    position: absolute;
    right: 0;
    top: 0;
    padding: 10px 10px;
    color: #0072ff;
    cursor: pointer;
}

.ai_wrapper .s_text {
    font-size: 10px;
    color: #b4b4b4;
    padding-top: 8px;
}

.s_text.coin_s_text {
    width: 100%;
    float: left;
    padding-left: 10%;
    position: relative;
    top: 5vh;
}

.name_line {
    width: 100%;
    height: auto;
    position: relative;
    top: 8vh;
    background-color: white;
    float: left;
    padding: 7px 0;
    border-top: 1px solid #dcdcdc;
    border-bottom: 1px solid #dcdcdc;
}

.name_line span {
    width: 50%;
    float: left;
    padding: 0 15px;
    font-size: 12px;
    font-weight: bold;
}

.name_line span {
    width: 50%;
    float: left;
    padding: 0 15px;
    font-size: 12px;
    font-weight: bold;
}

.name_line span:nth-child(2) {
    text-align: right;
}

.list {
    width: 100%;
    position: relative;
    overflow-x: hidden;
    overflow-y: auto;
    border-top: 1px solid #dcdcdc;
}

.coin_list {
    height: 59.5vh;
    top: 8vh;
}

.list ul {
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.list ul li {
    padding: 20px 15px;
    box-sizing: border-box;
    border-bottom: 1px solid #dfdfdf;
    font-size: 14px;
    color: #5a5a5a;
    text-align: left;
    background-color: #ffffff;
    transition-duration: 0.3s;
}

.coin_list.plus_list ul li span {
    float: right;
    font-weight: bold;
    color: #0045ab;
}

.coin_list ul li.active {
    background-color: #49d094;
    color: white;
}
</style>
