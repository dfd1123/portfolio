<template>
  <div class="_popup_wrapper _write_ppopup_wrapper review_write_popup_wrapper">
    <div class="_bg"></div>
    <div class="_popup_wrap">
      <div class="_popup_title">
        <h4 v-if="kind === 'create'">
          구매후기 작성
          <button
            type="button"
            class="_close_btn"
            @click="$emit('close-event')"
          ></button>
        </h4>
        <h4 v-if="kind === 'edit'">
          구매후기 수정
          <button
            type="button"
            class="_close_btn"
            @click="$emit('close-event')"
          ></button>
        </h4>
      </div>
      <div class="_popup_content">
        <!-- 상품 종류 E -->
        <div class="_product_view">
          <div class="_img_box">
            <img
              v-if="ConvertImage(item.images).length > 0"
              :src="storageUrl+ConvertImage(item.images)[0]"
              :alt="item.title"
            >
            <img
              v-else
              src="/images/img/thumbnail.png"
              alt="동글 이미지 없음"
            >
          </div>
          <div class="_text">
            <p class="_title">
              {{ item.title }}
            </p>
            <p class="_option">
              <span
                v-for="(option, index) in OptionFormat()"
                :key="index"
              >{{ option }} /</span>
              <span>수량: {{ item.qty }}개</span>
            </p>
          </div>
        </div>
        <!-- 상품 종류 E -->
        <!-- 에디터 대신 삽입 -->
        <div class="_editer_wrap">
          <h5>내용 작성</h5>
          <uploader
            v-model="fileList"
            :auto-upload="false"
            :limit="5"
          />
          <textarea
            class="_editer"
            v-model="form.review_body"
          ></textarea>
        </div>
        <!-- 에디터 대신 삽입 E -->
        <!-- 별점 영역 -->
        <div class="_star_score_wrap">
          <h5>만족도 평가</h5>
          <div class="_star_score">
            <input
              type="radio"
              id="star05"
              value="5"
              v-model="form.rating"
              class="dg-input-checkbox display_none"
            >
            <label
              for="star05"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="star05"
              class="dg-input-checkbox_text"
            >
              <div class="in-star-group star-05">
                <i class="_img"></i>
                <b class="_rate_txt"></b>
              </div>
            </label>
          </div>
          <div class="_star_score">
            <input
              type="radio"
              id="star04"
              value="4"
              v-model="form.rating"
              class="dg-input-checkbox display_none"
            >
            <label
              for="star04"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="star04"
              class="dg-input-checkbox_text"
            >
              <div class="in-star-group star-04">
                <i class="_img"></i>
                <b class="_rate_txt"></b>
              </div>
            </label>
          </div>
          <div class="_star_score">
            <input
              type="radio"
              id="star03"
              value="3"
              v-model="form.rating"
              class="dg-input-checkbox display_none"
            >
            <label
              for="star03"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="star03"
              class="dg-input-checkbox_text"
            >
              <div class="in-star-group star-03">
                <i class="_img"></i>
                <b class="_rate_txt"></b>
              </div>
            </label>
          </div>
          <div class="_star_score">
            <input
              type="radio"
              id="star02"
              value="2"
              v-model="form.rating"
              class="dg-input-checkbox display_none"
            >
            <label
              for="star02"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="star02"
              class="dg-input-checkbox_text"
            >
              <div class="in-star-group star-02">
                <i class="_img"></i>
                <b class="_rate_txt"></b>
              </div>
            </label>
          </div>
          <div class="_star_score">
            <input
              type="radio"
              id="star01"
              value="1"
              v-model="form.rating"
              class="dg-input-checkbox display_none"
            >
            <label
              for="star01"
              class="dg-input-checkbox_label"
            ></label>
            <label
              for="star01"
              class="dg-input-checkbox_text"
            >
              <div class="in-star-group star-01">
                <i class="_img"></i>
                <b class="_rate_txt"></b>
              </div>
            </label>
          </div>
        </div>
        <!-- 별점 영역 E -->
        <div class="popup_end_btn_wrap">
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn _popup_editer_btn"
            v-ripple
            v-if="kind === 'create'"
            @click="Submit"
          >
            작성완료
          </button>
          <button
            type="button"
            class="dg-btn_line dg-dubble_btn _popup_editer_btn"
            v-ripple
            v-if="kind === 'edit'"
            @click="Submit"
          >
            수정완료
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Uploader from 'vux-uploader-component'

export default {
  components: {
    Uploader
  },
  data: function () {
    return {
      form: {
        review_id: null,
        item_id: null,
        review_title: null,
        review_body: null,
        rating: 5
      },
      fileList: []
    }
  },
  props: {
    item: {
      type: Object,
      required: true
    },
    kind: {
      type: String,
      default: 'create'
    }
  },
  watch: {
    item () {
      this.form.review_title = this.item.review_title
      this.form.review_body = this.item.review_body
      this.form.rating = this.item.rating.toFixed(0)
      this.form.item_id = this.item.item_id
      this.form.review_id = this.item.id

      this.ConvertImage(this.item.image).forEach(image => {
        this.fileList.push({ url: image })
      })
    }
  },
  methods: {
    OptionFormat () {
      if (this.item.item_option) {
        const options = this.item.item_option.split(',')
        const optionSubject = this.item.option_subject.split(',')
        const productOption = []

        for (let i = 0; i < optionSubject.length; i++) {
          productOption.push(optionSubject[i] + ' : ' + options[i])
        }

        return productOption
      }
    },
    async Submit () {
      if (this.kind === 'create') {
        const formData = new FormData()

        await this.fileList.forEach((image, index) => {
          formData.append('images[]', image.blob || {})
        })

        formData.append('item_id', this.form.item_id)
        formData.append('review_body', this.form.review_body)
        formData.append('rating', this.form.rating)

        try {
          const res = (
            await this.$http.post(this.$APIURI + 'review', formData, {
              headers: {
                'Content-type': 'application/json; multipart/form-data;'
              }
            })
          ).data

          if (res.state === 1) {
            this.SuccessAlert('구매후기가 등록되었습니다.')

            const params = {
              target_mem_id: this.item.seller_uid,
              not_type: 'review',
              not_content_id: res.query,
              not_message:
                this.$store.state.user.name +
                '님 께서 구매후기를 남기셨습니다.',
              not_url:
                'https://store.dong-gle.co.kr/manage-review-detail/' + res.query
            }

            this.NotifyStore(params)
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      } else if (this.kind === 'edit') {
        const formData = new FormData()
        await this.fileList.forEach((image, index) => {
          if (!image.blob) {
            formData.append('index[]', image.url)
          }
          formData.append('images[]', image.blob || {})
        })

        formData.append('item_id', this.form.item_id)
        formData.append('review_body', this.form.review_body)
        formData.append('rating', this.form.rating)
        formData.append('_method', 'put')

        try {
          const res = (
            await this.$http.post(this.$APIURI + 'review/update', formData, {
              headers: {
                'Content-type': 'application/json; multipart/form-data;'
              }
            })
          ).data

          if (res.state === 1) {
            this.SuccessAlert('구매후기가 수정되었습니다.')
          } else {
            console.log(res.msg)
          }
        } catch (e) {
          console.log(e)
        }
      }
      this.$emit('reload')
    }
  }
}
</script>

<style lang="scss" scoped>
.review_write_popup_wrapper {
  top: 0%;
  height: 100% !important;
  z-index: 9996;

  &.posFixed {
    top: 0;
    overflow: hidden;
  }

  ._bg {
    display: none;
  }

  ._popup_wrap {
    height: 100%;
    overflow: hidden;
    padding-top: 55px;
    top: 0;
    left: 0;
    transform: translate(0, 0);
  }

  ._popup_title {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 990;
    width: 100%;
    left: 0;
  }

  ._popup_content {
    height: 100%;
    overflow: auto;
    padding-top: 3px;
  }
}
</style>
