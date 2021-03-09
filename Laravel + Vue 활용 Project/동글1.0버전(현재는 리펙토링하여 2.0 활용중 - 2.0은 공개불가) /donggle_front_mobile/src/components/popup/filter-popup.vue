<template>
  <!-- * 필터검색 팝업 -->
  <div class="_popup_wrapper _filter_search_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4>
          필터검색
          <button
            type="button"
            class="_close_btn"
            @click="$emit('close-popup')"
          ></button>
        </h4>
      </div>
      <div class="_popup_content">
        <div class="dg-filter-search-group clear_both">
          <div class="l-title-group">
            <ul>
              <!-- 클릭시 클릭한 애와 .l-contents-group 중에서 같은 인덱스인 .in-section 둘다 .active -->
              <li
                :class="['in-label -gender', {'active':selectLi === 'gender'}]"
                @click="selectLi = 'gender'"
              >
                성별
              </li>
              <li
                :class="['in-label -age', {'active':selectLi === 'age'}]"
                @click="selectLi = 'age'"
              >
                나이
              </li>
              <li
                :class="['in-label -color', {'active':selectLi === 'color'}]"
                @click="selectLi = 'color'"
              >
                색상
              </li>
              <li
                :class="['in-label -price', {'active':selectLi === 'price'}]"
                @click="selectLi = 'price'"
              >
                가격대
              </li>
              <li
                :class="['in-label -size', {'active':selectLi === 'size'}]"
                @click="selectLi = 'size'"
              >
                사이즈
              </li>
              <li
                :class="['in-label -hashtag', {'active':selectLi === 'hash_tag'}]"
                @click="selectLi = 'hash_tag'"
              >
                해시태그
              </li>
            </ul>
          </div>
          <div class="l-contents-group">
            <!--
                각 영역에서 선택된 갯수를 .l-title-group중 같은 인덱스인 .in-label의 ._choice_num에 기입
            -->
            <Gender
              :class="{'active': selectLi === 'gender'}"
              :gender="form.gender"
              @input="form.gender = $event"
            />
            <Age
              :class="{'active': selectLi === 'age'}"
              :age="form.age"
              @input="form.age = $event"
            />
            <Color
              :class="{'active': selectLi === 'color'}"
              :color="form.color"
              :main-colors="mainColors"
              :sub-colors="subColors"
              @input="form.color = $event"
            />
            <Price
              :class="{'active': selectLi === 'price'}"
              :min-price="form.min_price"
              :max-price="form.max_price"
              @input="PriceChange"
            />
            <Size
              :class="{'active': selectLi === 'size'}"
              :min-size="this.form.min_size"
              :max-size="this.form.max_size"
              @input="SizeChange"
            />
            <HashTag
              :class="{'active': selectLi === 'hash_tag'}"
              :hash-tag="form.hash_tag"
              @input="form.hash_tag = $event"
            />
          </div>
        </div>
        <div class="dg-dubble_btn_wrap clear_both">
          <button
            type="button"
            class="in-refresh"
            @click="ResetForm"
          >
            <img
              src="/images/btn/btn_refresh.svg"
              alt="refresh button"
            >
            <span>전체해제</span>
          </button>
          <button
            type="button"
            class="dg-btn_gra dg-dubble_btn"
            @click="$emit('submit')"
          >
            선택완료
          </button>
        </div>
      </div>
    </div>
  </div>
  <!-- 필터검색 팝업 E -->
</template>

<script>
import Gender from '@/components/filter/gender.vue'
import Color from '@/components/filter/color.vue'
import Age from '@/components/filter/age.vue'
import HashTag from '@/components/filter/hash-tag.vue'
import Price from '@/components/filter/price.vue'
import Size from '@/components/filter/size.vue'

export default {
  components: {
    Gender,
    Color,
    Age,
    HashTag,
    Price,
    Size
  },
  data: function () {
    return {
      selectLi: 'gender'
    }
  },
  props: {
    form: {
      type: Object,
      required: true
    },
    mainColors: {
      type: Array,
      default: () => {
        return []
      }
    },
    subColors: {
      type: Array,
      default: () => {
        return []
      }
    }
  },
  methods: {
    ResetForm () {
      const form = {
        searchKeyword: this.$route.query.searchKeyword || '',
        ca_name: '',
        title: '',
        gender: '',
        age: [],
        color: [],
        min_price: null,
        max_price: null,
        max_size: 150,
        min_size: 20,
        hash_tag: [],
        orderBy: 'popular',
        offset: 0,
        limit: 30
      }

      this.$emit('input-form', form)
    },
    PriceChange (price) {
      this.form.min_price = price[0] === '' ? null : Number(price[0])
      this.form.max_price = price[1] === '' ? null : Number(price[1])
      this.$emit('input-form', this.form)
    },
    SizeChange (minSize, maxSize) {
      this.form.min_size = Number(minSize)
      this.form.max_size = Number(maxSize)
      this.$emit('input-form', this.form)
    }
  }
}
</script>

<style lang="scss" scoped>
._filter_search_popup_wrapper {
  height: 100% !important;

  @media all and (max-width: 576px) {
    ._popup_wrap {
      top: 0;
      left: 0;
      transform: translate(0,0);
      height: 100%;
    }
  }
}
</style>
