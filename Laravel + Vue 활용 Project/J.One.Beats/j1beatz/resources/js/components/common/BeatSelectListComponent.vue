<template>
    <div>
        <div
            v-if="title"
            class="in-title"
        >
            <h3 class="in-title-name">
                {{ title }}
            </h3>
        </div>

        <div
            id="beat-select-list"
            class="cart_chart_list chart_scroll_area"
            data-mcs-theme="minimal-dark"
            :style="{height: `${propheight}`}"
        >
            <table>
                <thead>
                    <tr>
                        <th
                            v-if="showSelectButton"
                            class="th-check"
                        >
                            <div>
                                <input
                                    id="check-all-order"
                                    v-model="isCheckAllChecked"
                                    type="checkbox"
                                    title="곡 전체선택"
                                    class="input-style-01"
                                    @change="toggleCheckAllBeats()"
                                >
                                <label for="check-all-order">
                                    <span class="none">곡 전체선택하기</span>
                                </label>
                            </div>
                        </th>
                        <th class="th-song">
                            <div>곡/프로듀서</div>
                        </th>
                        <th class="th-file_type">
                            <div>파일형식</div>
                        </th>
                        <th class="th-license">
                            <div>라이센스</div>
                        </th>
                        <th class="th-price">
                            <div>가격</div>
                        </th>
                        <th
                            v-if="showDeleteButton"
                            class="th-del"
                        >
                            <div>삭제</div>
                        </th>
                    </tr>
                </thead>
            </table>

            <table>
                <tbody>
                    <tr
                        v-for="(beat, index) in beatRows"
                        :key="beat.beat_id"
                    >
                        <td
                            v-if="showSelectButton"
                            class="td-check"
                        >
                            <div>
                                <input
                                    :id="`check-each-order-${index}`"
                                    v-model="beat.checked"
                                    type="checkbox"
                                    title="곡 개별선택"
                                    class="input-style-01"
                                    @change="beatCheckChanged"
                                >
                                <label
                                    :for="`check-each-order-${index}`"
                                >
                                    <span class="none">곡 개별선택하기</span>
                                </label>
                            </div>
                        </td>
                        <td class="td-song">
                            <div class="c-song-title">
                                <span
                                    class="thumb-mini"
                                    :style="{'background-image': `url(/fdata/beathumb/${beat.beat_thumb})`}"
                                ><span class="none">썸네일</span></span>
                                <p class="c-song-title-info">
                                    <b>{{ beat.beat_title }}</b>
                                    <br>
                                    <span>{{ beat.prdc_nick }}</span>
                                </p>
                            </div>
                        </td>
                        <td class="td-file_type">
                            <div v-if="beat.wav === 1 && beat.wav === 1">
                                mp3, wav
                            </div>
                            <div v-else-if="beat.mp3 === 1">
                                mp3
                            </div>
                            <div v-else-if="beat.wav === 1">
                                wav
                            </div>
                        </td>
                        <td class="td-license">
                            <div>
                                <span
                                    style="cursor: pointer"
                                    @click="isLicensePopupVisible = true"
                                >delux(다운로드)</span>
                            </div>
                        </td>
                        <td class="td-price">
                            <div><b>{{ Number(beat.beat_price).toLocaleString() }}</b><em>원</em></div>
                        </td>
                        <td
                            v-if="showDeleteButton"
                            class="td-del"
                        >
                            <div>
                                <button
                                    type="button"
                                    class="chart-ctrl-btn chart-ctrl-btn-del"
                                    @click="remove(beat)"
                                >
                                    <span class="none">삭제</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <confirm-popup-component
            title-text="확인"
            :visible.sync="isConfirmPopupVisible"
            :right-button-text="confirmPopupRightText"
            @rightButtonClick="confirmAction"
        >
            <!-- eslint-disable vue/no-v-html -->
            <div
                class="popup-layer-txt"
                v-html="confirmPopupMessage"
            />
        </confirm-popup-component>

        <license-info-popup
            :visible.sync="isLicensePopupVisible"
        />
    </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import LicenseInfoPopup from "../common/LicenseInfoPopup";
import ConfirmPopupComponent from "../common/ConfirmPopupComponent";

export default {
    components: {
        LicenseInfoPopup,
        ConfirmPopupComponent
    },
    props: {
        title: {
            type: String,
            default: ""
        },
        roleName: {
            type: String,
            default: "장바구니"
        },
        height: {
            type: String,
            default: "500"
        },
        beats: {
            type: Array,
            default: () => []
        },
        showSelectButton: {
            type: Boolean,
            default: true
        },
        showDeleteButton: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            isConfirmPopupVisible: false,
            isLicensePopupVisible: false,
            isCheckAllChecked: false,
            confirmPopupMessage: "",
            confirmPopupRightText: "",
            confirmAction: () => {},
            beatRows: [],
            selectedBeatRows: []
        };
    },
    computed: {
        ...mapGetters(["isUser"]),
        ...mapState({
            user: "user"
        }),
        propheight() {
            if (Number.isNaN(Number(this.height))) {
                return this.height;
            }

            return `${this.height}px`;
        }
    },
    watch: {
        beats() {
            this.beatRows = this.beats.map(beat => {
                return { ...beat, ...{ checked: false, more: false } };
            });
            this.selectedBeatRows = [];
        },
        selectedBeatRows() {
            this.isCheckAllChecked =
                this.beatRows.length !== 0 &&
                this.beatRows.length === this.selectedBeatRows.length;

            this.$emit(
                "selectedRowChange",
                this.selectedBeatRows.map(beat => ({ ...beat }))
            );
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.$RENDER_JQUERY_mCustomScrollbar(
                "#beat-select-list",
                "minimal-dark"
            );
        });
    },
    methods: {
        beatCheckChanged() {
            this.selectedBeatRows = this.beatRows.filter(beat => beat.checked);
        },
        toggleCheckAllBeats() {
            this.beatRows.forEach(beat => {
                beat.checked = this.isCheckAllChecked;
            });

            this.selectedBeatRows = this.beatRows.filter(beat => beat.checked);
        },
        remove(beat) {
            this.confirmPopupMessage = `정말로 해당 비트를 <br> ${
                this.roleName
            }에서 <br> 삭제하시겠습니까?`;
            this.confirmPopupRightText = "삭제";
            this.confirmAction = async () => {
                this.$emit("rowRemove", { ...beat });
            };
            this.isConfirmPopupVisible = true;
        }
    }
};
</script>

<style lang="scss">
</style>
