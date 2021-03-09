<template>
  <div id="hlthcontent_page" class="page page--hd_default">
    <header-component hd-title="증상선택" @privButtonClick="privButtonClick" />
    <div class="page_container">
      <transition appear name="slide-fade">
        <div class="contents contents--choice">
          <ul class="grid_group">
            <li class="row-2" v-for="item in symptomList" :key="item.spt_no">
              <figure
                class="choice_box"
                :class="{ active: symptomType === item.spt_no }"
                @click="symptomType = item.spt_no"
              >
                <span class="in_icon">
                  <img :src="`/fdata/symptom_thumb/${item.spt_thumb}`" :alt="item.spt_title" />
                </span>
                <figcaption class="in_caption">{{ item.spt_title }}</figcaption>
              </figure>
            </li>
          </ul>
          <input
            type="button"
            value="선택 완료"
            class="wide_btn btn_clear_to_theme step_btn"
            :class="{ active: isChoiced }"
            @click="buttonClick"
          />
        </div>
      </transition>
      <transition appear name="slide-fade">
        <div class="contents contents--boxes" v-if="isCompltType">
          <article class="hlth_content_wrap">
            <h2>관련 컨텐츠입니다.</h2>
            <figure
              v-for="item in symptomList.find(x => x.spt_no === symptomType)
                .spt_contents"
              :key="item.smptm_no"
              class="in_conbox"
              @click="open(item.smptm_link)"
            >
              <img :src="`/fdata/symptom_info_thumb/${item.smptm_thumb}`" :alt="item.smptm_title" />
            </figure>
          </article>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
export default {
  name: 'HealthContent',
  data () {
    return {
      symptomType: null,
      isCompltType: false,
      symptomList: []
    }
  },
  async created () {
    this.symptomList = await this.$axios
      .get('/symptoms')
      .then(response => response.data)
  },
  computed: {
    isChoiced () {
      return Boolean(this.symptomType)
    }
  },
  methods: {
    privButtonClick () {
      if (this.isCompltType) {
        this.isCompltType = false
        this.symptomType = 0
      } else {
        this.$router.push('/')
      }
    },
    buttonClick () {
      if (!this.isChoiced) {
        return
      }

      this.isCompltType = true
    },
    open (url) {
      window.open(url, '_blank', '')
    }
  }
}
</script>

<style lang="scss" scoped>
#hlthcontent_page {
  .page_container {
    overflow-x: hidden;
  }
  .step_btn {
    @include btButton;
  }
  .contents {
    @include position($t: 0, $l: 0);
    padding: 1.8rem 1.5rem;
    background-color: #fffbf2;
    overflow-y: scroll;
  }
  .grid_group {
    height: 100%;
    overflow-y: scroll;
    padding-bottom: 7rem;
  }

  .contents--boxes {
    background-color: #fafafa;
    padding-bottom: 3rem;
    .in_conbox {
      width: 100%;
      box-shadow: 4px 10px 20px rgba(0, 0, 0, 0.08);
      border-radius: 20px;
      margin: 20px 0;
      background-color: #fffbf2;
      overflow: hidden;
    }
    .in_conbox img {
      width: 100%;
      float: left;
    }
  }

  .choice_box {
    background-color: white;
    border: 0;
    padding: 25px 0;
    border-radius: 18px;
    .in_icon img {
      opacity: 0.4;
    }
  }

  .choice_box.active {
    background-color: $theme-03;
    color: white;
    box-shadow: 0 4px 11px rgba(248, 175, 37, 0.5);
    .in_icon img {
      opacity: 1;
    }
  }
}
</style>
