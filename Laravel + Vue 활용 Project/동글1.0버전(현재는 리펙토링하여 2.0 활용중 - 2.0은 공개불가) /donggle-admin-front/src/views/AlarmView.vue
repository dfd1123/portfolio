<template>
  <div id="app">
    <layout-header
      :title="null"
      title-mobile="알림"
    />

    <!-- contents -->
    <div
      id="admin-container"
      class="alarm-container"
    >
      <div class="wrapper">
        <!-- page content -->
        <div id="page-snb-alarm-wrap">
          <div class="grid-line-group clearfix">
            <!-- cs content -->
            <div class="panel-default-container">
              <section class="alarm-list-wrap">
                <div class="alarm-card">
                  <div class="panel-default-title type_02 show-pc">
                    <h3>알림</h3>
                  </div>
                  <div class="panel-default-title page-alarm-mobile-title">
                    <h3>알림</h3>
                    <button
                      class="icon-close-btn-wh"
                      @click.prevent="close"
                    >닫기</button>
                  </div>
                  <!-- height가 576px보다 커지면 .y-scroll ._list의 보더때문!-->
                  <div class="panel-default y-scroll">
                    <template v-if="notificationList.length > 0">
                      <ul class="_alarm_list_group">
                        <!-- 알림 리스트 있을 때 -->
                        <!-- 미확인 알림은 _list에 클래스 new 추가 클릭시 확인처리되면서 new 클래스 사라짐 -->
                        <li
                          v-for="notification in notificationList"
                          :key="notification.not_id"
                          class="_list"
                          :class="{new: Boolean(!notification.not_read_datetime)}"
                          @click="clickNotification(notification)"
                        >
                          <div class="in-store-profile">
                            <div class="symbol-circle">
                              <img
                                v-if="notification.store_profile_img"
                                :src="_get(JSON.parse(notification.store_profile_img), 0, null)?storagePath(_get(JSON.parse(notification.store_profile_img), 0, null)):'/images/img/profile_admin.jpg'"
                                class="_imgtag"
                                alt="profile image"
                              />
                              <img
                                v-else-if="notification.profile_img"
                                :src="_get(JSON.parse(notification.profile_img), 0, null)?_get(JSON.parse(notification.profile_img), 0, null):storagePath(_get(JSON.parse(notification.profile_img), 0, null))"
                                class="_imgtag"
                                alt="profile image"
                              />
                              <img
                                v-else-if="notification.from_name"
                                src="/images/img/basic_profile/01.png"
                                class="_imgtag"
                                alt="profile image"
                              />
                              <img
                                v-else
                                src="/images/img/profile_admin.png"
                                class="_imgtag"
                                alt="profile image"
                              />
                            </div>
                          </div>
                          <div class="_alarm_desc">
                            <span
                              v-if="notification.from_storename"
                              class="_name"
                            >{{notification.from_storename}}</span>
                            <span
                              v-else-if="notification.from_name"
                              class="_name"
                            >{{notification.from_name}}</span>
                            <span
                              v-else
                              class="_name"
                            >동글 고객센터</span>
                            <span class="_date">{{(notification.not_datetime || '').split(' ')[0]}}</span>
                            <p class="_desc">{{notification.not_message}}</p>
                          </div>
                        </li>
                        <!-- 알림 리스트 있을 때 E-->
                      </ul>
                    </template>
                    <template v-else>
                      <!-- 알림이 없을 때 -->
                      <div class="nothing-history">
                        <div class="in-empty-icon">
                          <img
                            src="/images/icon/empty_alarm.svg"
                            alt="등록된 알림이 없습니다."
                          />
                        </div>
                        <div class="in-empty-ment">알림이 없습니다.</div>
                      </div>
                      <!-- 알림이 없을 때 E -->
                    </template>
                  </div>
                </div>
              </section>
            </div>
            <!-- cs content E -->
          </div>
        </div>
        <!-- page content E -->
      </div>
      <layout-footer class="show-pc" />
    </div>
    <!-- END contents -->
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  name: 'AlarmView',
  computed: {
    ...mapGetters([
      'notificationList'
    ])
  },
  methods: {
    ...mapActions([
      'getNotification',
      'readNotification'
    ]),
    close () {
      if (window.history.length > 2) {
        this.$router.go(-1)
      } else {
        this.$router.replace('/main')
      }
    },
    clickNotification (item) {
      if (!item.not_read_datetime) {
        item.not_read_datetime = true
        this.readNotification(item)
      }

      this.NativePopup(item.not_url)
    }
  }
}
</script>
