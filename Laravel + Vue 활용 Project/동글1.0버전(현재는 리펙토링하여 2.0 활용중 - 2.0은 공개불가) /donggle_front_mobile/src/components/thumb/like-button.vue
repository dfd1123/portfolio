<template>
  <div>
    <input
      type="checkbox"
      class="heart-badge"
      :id="'like' + timestamp"
      v-model="likeChecked"
      :checked="likeChecked"
      @change="ClickHeart()"
    >
    <label
      class="heart-badge-label"
      :for="'like' + timestamp"
    >
      <svg
        viewBox="467 392 58 57"
        :id="'like' + timestamp + '-svg'"
      >
        <defs>
          <linearGradient
            id="heart-shape-gradient"
            x2="0.35"
            y2="1"
          >
            <stop
              offset="0%"
              stop-color="var(--color-stop)"
            />
            <stop
              offset="100%"
              stop-color="var(--color-bot)"
            />
          </linearGradient>
        </defs>

        <g
          class="heart-Group"
          fill="none"
          fill-rule="evenodd"
          transform="translate(467 392)"
        >
          <path
            d="M29.144 20.773c-.063-.13-4.227-8.67-11.44-2.59C7.63 28.795 28.94 43.256 29.143 43.394c.204-.138 21.513-14.6 11.44-25.213-7.214-6.08-11.377 2.46-11.44 2.59z"
            class="heart-path"
            fill="#AAB8C2"
          />
          <circle
            class="main-circ"
            opacity="0"
            cx="29.5"
            cy="29.5"
            r="1.5"
          />

          <g
            class="grp7"
            opacity="0"
            transform="translate(7 6)"
          >
            <circle
              class="oval2"
              fill="#a134e8"
              cx="5"
              cy="6"
              r="2"
            />
            <circle
              class="oval1"
              fill="#fc4ead"
              cx="2"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp6"
            opacity="0"
            transform="translate(0 28)"
          >
            <circle
              class="oval1"
              fill="#fc4ead"
              cx="2"
              cy="7"
              r="2"
            />
            <circle
              class="oval2"
              fill="#ff62b9"
              cx="3"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp3"
            opacity="0"
            transform="translate(52 28)"
          >
            <circle
              class="oval2"
              fill="#a134e8"
              cx="2"
              cy="7"
              r="2"
            />
            <circle
              class="oval1"
              fill="#a134e8"
              cx="4"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp2"
            opacity="0"
            transform="translate(44 6)"
          >
            <circle
              class="oval2"
              fill="#a134e8"
              cx="5"
              cy="6"
              r="2"
            />
            <circle
              class="oval1"
              fill="#fc4ead"
              cx="2"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp5"
            opacity="0"
            transform="translate(14 50)"
          >
            <circle
              class="oval1"
              fill="#fc4ead"
              cx="6"
              cy="5"
              r="2"
            />
            <circle
              class="oval2"
              fill="#ff62b9"
              cx="2"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp4"
            opacity="0"
            transform="translate(35 50)"
          >
            <circle
              class="oval1"
              fill="#fc4ead"
              cx="2"
              cy="7"
              r="2"
            />
            <circle
              class="oval2"
              fill="#fc4ead"
              cx="3"
              cy="2"
              r="2"
            />
          </g>

          <g
            class="grp1"
            opacity="0"
            transform="translate(24)"
          >
            <circle
              class="oval1"
              fill="#a134e8"
              cx="2.5"
              cy="3"
              r="2"
            />
            <circle
              class="oval2"
              fill="#a134e8"
              cx="7.5"
              cy="2"
              r="2"
            />
          </g>
        </g>
      </svg>
    </label>
  </div>
</template>

<script>
  export default {
    data: function () {
      return {
        timestamp: new Date().getTime(),
        likeChecked: this.like === 1
      }
    },
    props: {
      itemNo: {
        type: Number,
        default: 0
      },
      like: {
        type: Number,
        default: 0
      },
      sellerId: {
        type: Number,
        default: 0
      }
    },
    methods: {
      async ClickHeart () {
        const like = new Audio('/sound/decision22.mp3')
        const unlike = new Audio('/sound/cancel4.mp3')

        if (this.likeChecked) {
          like.currentTime = 0
          like.play()
        } else {
          unlike.currentTime = 0
          unlike.play()
        }

        if (this.$cookies.isKey('access_token')) {
          const params = {
            item_id: this.itemNo
          }

          try {
            const res = (await this.$http.post(this.$APIURI + 'wish', params)).data

            if (res.state === 1) {
              if (this.likeChecked) {
                const notifyParams = {
                  target_mem_id: this.sellerId,
                  not_type: 'wish',
                  not_content_id: this.itemNo,
                  not_message: this.$store.state.user.nickname + '님 께서 판매자님의 상품을 찜하셨습니다.',
                  not_url: window.location.protocol + '//' + window.location.host + '/product/view/' + this.itemNo
                }

                this.NotifyStore(notifyParams)
              }
            }
          } catch (e) {
            console.log(e)
          }
        }

        this.$emit('click-listen', this.likeChecked)
      }
    }
  }
</script>

<style lang="scss" scoped>
</style>
