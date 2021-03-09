<template>
  <div id="hlthreport_more_page" class="page page--hd_default">
    <header-component
      hd-title="건강리포트 더보기"
      :hd-theme-boxshadow="true"
      @privButtonClick="privButtonClick"
    />
    <div class="page_container">
      <div class="contents">
        <health-report-more-card
          v-for="item in batchInfos"
          :key="item.bt_no"
          :panel-info="item"
          :is-any-applied="!isAnyApplied"
          @ApplyButtonClick="ApplyButtonClick"
        ></health-report-more-card>
      </div>
    </div>
    <footer-navigation :report-on="true"></footer-navigation>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
  name: 'HealthReportMore',
  data () {
    return {
      batchInfos: []
    }
  },
  computed: {
    isAnyApplied () {
      return this.batchInfos.some((x) => x.ubt_no !== null)
    }
  },
  async created () {
    const data = await this.$axios.get('/batches')
      .then(response => response.data)

    this.batchInfos = data.reverse()
  },
  methods: {
    ...mapActions(['getUser']),
    privButtonClick () {
      this.$router.go(-1)
    },
    async ApplyButtonClick (batchInfo) {
      const value = await this.$swal({
        showCancelButton: true,
        confirmButtonText: '네, 기록하겠습니다.',
        cancelButtonText: '아니요',
        html: `${batchInfo.bt_order}기 결과 리포트를 위해 10일의 기록이 <br>필요합니다. 10일 간 기록 후 건강리포트를 <br>받아보시겠습니까?`,
        customClass: {
          container: 'bcg-swal__container',
          popup: 'bcg-swal__popup bcg-swal__popup--doublebtn',
          content: 'bcg-swal__content bcg-swal__content--theme01',
          confirmButton: 'bcg-swal__confirm-button',
          cancelButton: 'bcg-swal__cancel-button',
          image: 'bcg-swal__orange_single'
        },
        imageUrl: '/assets/images/logos/logo_bcg_swal_single.svg',
        imageWidth: 103,
        imageHeight: 103,
        imageAlt: 'Custom image'
      }).then(result => result.value)

      if (!value) {
        return
      }

      try {
        await this.$axios
          .post('/user_batches', {
            bt_no: batchInfo.bt_no
          })
          .then(response => response.data)

        await this.getUser()

        this.$swal({
          confirmButtonText: '네, 확인했습니다.',
          html: `${batchInfo.bt_order}기 결과 리포트 신청이 완료되었습니다.<br>10일 간 스케줄을 꼼꼼히 기록하시고,<br>${batchInfo.bt_order}기 건강리포트를 확인해보세요.`,
          customClass: {
            container: 'bcg-swal__container',
            popup: 'bcg-swal__popup bcg-swal__popup--doublebtn',
            content: 'bcg-swal__content bcg-swal__content--theme01',
            confirmButton: 'bcg-swal__confirm-button',
            cancelButton: 'bcg-swal__cancel-button',
            image: 'bcg-swal__orange_single'
          },
          imageUrl: '/assets/images/logos/logo_bcg_swal_single.svg',
          imageWidth: 103,
          imageHeight: 103,
          imageAlt: 'Custom image'
        }).then(() => {
          this.$router.replace('/plan/question')
        })
      } catch (e) {
        if (e.response) {
          const { status, data } = e.response
          if (status === 422) {
            this.$swal({
              text: data.message,
              type: 'warning',
              showConfirmButton: false,
              customClass: {
                container: 'bcg-swal__container',
                popup: 'bcg-swal__popup--btnfalse',
                content: 'bcg-swal__content',
                icon: 'bcg-swal__icon--btnfalse'
              },
              timer: 1300
            })
          }
        }

        console.log(e)
      }
    }

  }
}
</script>

<style lang="scss" scoped>
#hlthreport_more_page {
  .contents {
    padding: 1.8rem 1.5rem 7rem;
    background-color: #f7f7f7;
    overflow-y: scroll;
  }
}
</style>
