<template>
  <div>
    <h3 class="con-title">
      채팅 내용 보기
      <a
        style="float: right; margin-top: -9px;"
        class="btn btn-01 type-03"
        @click.prevent="isFolded = !isFolded"
      >토글</a>
    </h3>
    <div
      class="service-form"
      :style="{'height': setting.height ? setting.height : 'initial', 'display': isFolded || chatList.length === 0 ? 'none' : 'block'}"
    >
      <div
        v-for="(chat, index) in chatList"
        :key="index"
        class="form-group"
      >
        <div
          class="form-group-list"
          style="height: 60px;"
        >
          <div style="width: 15%; height:100%; display:inline-block; text-align: center">
            <div>{{ chat.name }}</div>
            <div>{{ $moment(chat.receiveTime).format('YYYY-MM-DD') }}</div>
            <div>{{ $moment(chat.receiveTime).format('HH:mm:ss') }}</div>
          </div>
          <div style="border: 1px solid; width: 75%; height:100%; display:inline-block">
            <span>{{ chat.message }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    components: {
    },
    data () {
      return {
        isFolded: false,
        chatList: []
      }
    },
    props: {
      setting: {
        type: Object,
        required: true,
        default: () => ({})
      },
      status: {
        type: String,
        required: true
      }
    },
    watch: {
      setting () {
        try {
          const chatData = this.get(this, 'setting.list.chttCn', null)
          if (chatData) {
            this.chatList = JSON.parse(chatData)
          }
        } catch (e) {
          console.log(e)
        }
      }
    },
    computed: {
    },
    methods: {
    }
  }
</script>

<style scoped>
  .btn-service-add {
    float: right;
    margin-top: -9px;
    min-width: initial;
  }

  .form-group {
    padding: 2px;
    padding-top: 10px;
    padding-bottom: 0px;
    border-top: 0px solid #666;
  }

  .form-group-list {
    margin: 0;
  }

  .form-title {
    margin-bottom: 0px;
  }

  .service-form {
    overflow-y: auto;
    border: 1px solid black;
  }
</style>
