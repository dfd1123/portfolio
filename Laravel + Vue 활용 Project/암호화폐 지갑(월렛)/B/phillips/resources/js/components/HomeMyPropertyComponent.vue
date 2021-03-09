<template>
    <div id="my_property" v-bind:class="{ active: visible }">
        <div class="overlap" @click="$emit('update:visible', false)"></div>
        <div class="my_property_wrap">
            <div class="my_property_hd">
                <div>
                    <img src="/images/button_my_property.svg" alt="my property" />
                </div>
                <p>
                    <b>{{$store.state.detail.user.fullname}}</b>
                    {{__('my_property.samano_asset')}}
                </p>
                <ul>
                    <li @click="depositRouteClick">{{__('my_property.krw_deposit')}}</li>
                    <li @click="withdrawRouteClick">{{__('my_property.krw_withdraw')}}</li>
                </ul>
            </div>
            <div class="my_property_con" style="height: 621px;">
                <div class="my_property_box">
                    <h5 class="myprop_hd">{{__('my_property.cash_asset')}}</h5>
                    <div class="myprop_bd">
                        <h4 id="myprop_local">
                            {{asset.cash_asset || 0}}
                            <i>KRW</i>
                        </h4>
                    </div>
                </div>
                <div class="my_property_box">
                    <h5 class="myprop_hd">{{__('my_property.total_revenue')}}</h5>
                    <div class="myprop_bd">
                        <h4>
                            {{asset.total_revenue || 0}}
                            <i>KRW</i>
                        </h4>
                    </div>
                </div>
                <div class="my_property_box">
                    <h5 class="myprop_hd">{{__('my_property.total_revenue_percent')}}</h5>
                    <div class="myprop_bd">
                        <h4 id="total_revenue_percentage">
                            <b>{{asset.total_revenue_percent || 0}}</b>
                            <i>%</i>
                        </h4>
                    </div>
                </div>
                <div class="my_property_box">
                    <h5 class="myprop_hd">{{__('my_property.total_asset')}}</h5>
                    <div class="myprop_bd">
                        <h4>
                            {{asset.total_asset || 0}}
                            <i>KRW</i>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <system-notice-component
            :message="systemNoticeMessage"
            :closeText="__('system.close')"
            :visible.sync="isPopupVisible"
        ></system-notice-component>
    </div>
</template>

<script>
import SystemNoticeComponent from "../components/common/SystemNoticeComponent";

export default {
    props: ["visible", "fullname", "asset"],
    components: {
        "system-notice-component": SystemNoticeComponent
    },
    data() {
        return {
            isPopupVisible: false,
            systemNoticeMessage: this.__("my_property.cash_lv_four_warning")
        };
    },
    methods: {
        depositRouteClick() {
            if (Number(this.$store.state.detail.security.status) >= 4) {
                this.$router.replace("/deposit");
            } else {
                this.isPopupVisible = true;
            }
        },
        withdrawRouteClick() {
            if (Number(this.$store.state.detail.security.status) >= 4) {
                this.$router.replace("/withdraw");
            } else {
                this.isPopupVisible = true;
            }
        }
    }
};
</script>

<style scoped>
#my_property {
    height: 100%;
    width: 100%;
    position: absolute;
    top: 0;
    right: 0;
    overflow: hidden;
}

#my_property.active {
    z-index: 11;
}

#my_property.active .overlap {
    display: block;
}

#my_property .overlap {
    display: none;
    background: rgba(0, 0, 0, 0.4);
    height: 100%;
    z-index: 2;
}

#my_property.active .my_property_wrap {
    right: 0;
}

#my_property .my_property_wrap {
    position: absolute;
    top: 0;
    right: -100%;
    width: 72%;
    background: #f0f0f0;
    height: 100%;
    z-index: 10;
    transition: right ease 0.3s;
}

#my_property .my_property_wrap .my_property_hd {
    position: relative;
    background: #0072ff;
    padding-top: 25px;
    text-align: center;
    border-bottom: 2px solid #cfdee5;
}

#my_property .my_property_wrap .my_property_hd div {
    background: #fff;
    border-radius: 50%;
    width: 70px;
    height: 70px;
    text-align: center;
    margin: 0 auto;
}

#my_property .my_property_wrap .my_property_hd div img {
    width: 40px;
    margin: 13px 6px 0 0;
}

#my_property .my_property_wrap .my_property_hd p {
    font-size: 16px;
    padding-top: 15px;
    color: #fff;
    margin-bottom: 0;
}

#my_property .my_property_wrap .my_property_hd p b {
    font-size: 18px;
}

#my_property .my_property_wrap .my_property_hd ul {
    overflow: hidden;
    padding-top: 15px;
    margin: 0;
}

#my_property .my_property_wrap .my_property_hd ul li:first-child {
    border-right: 1px solid #f4f4f4;
}

#my_property .my_property_wrap .my_property_hd ul li {
    float: left;
    width: 50%;
    background: #fff;
    font-size: 16px;
    font-weight: 600;
    padding: 15px 0;
    cursor: pointer;
    transition: all ease 0.1s;
}

.my_property_con {
    overflow-y: auto;
    padding-top: 10px;
}

.my_property_con .my_property_box {
    background: #fff;
    border-bottom: 2px solid #cfdee5;
    margin-bottom: 10px;
}

.my_property_con .my_property_box .myprop_hd {
    color: #0045ab;
    font-weight: bold;
    padding: 12px 17px;
    border-bottom: 1px solid #f4f4f4;
    position: relative;
    font-size: initial;
    margin-bottom: initial;
}

.my_property_con .my_property_box .myprop_hd button {
    font-size: 13px;
    color: #000;
    font-weight: 400;
    background: #fff;
    border: 1px solid #222;
    padding: 5px 7px;
    line-height: 1;
    margin: 0;
    border-radius: 5px;
    position: absolute;
    top: 8px;
    right: 8px;
}

.my_property_con .my_property_box .myprop_bd {
    padding: 13px 20px;
    text-align: right;
}

.my_property_con .my_property_box .myprop_bd h4 {
    color: #5a5a5a;
    font-size: 18px;
    font-weight: 600;
    margin-bottom: inherit;
    line-height: inherit;
}

.my_property_con .my_property_box .myprop_bd h4 b {
    font-weight: 600;
}

.my_property_con .my_property_box .myprop_bd h4 i {
    font-size: 15px;
    letter-spacing: -1px;
    color: #0072ff;
}

.my_property_con .my_property_box .myprop_bd div {
    position: relative;
    margin-top: 9px;
}

#my_property.active .my_property_wrap .my_property_hd ul li:active {
    background: #49d094;
    color: #fff;
}
</style>
