import Vue from 'vue'
import moment from 'moment'
import get from 'lodash/get'

export default {
  state: {
    plan: {
      upt_list: []
    }
  },
  getters: {
    plan: state => state.plan,
    planList: state => state.plan.upt_list,
    planProgressPercent: (state, { planList }) => {
      return Math.floor(planList.filter(x => x.result === 1).length / planList.length * 100) || 0
    }
  },
  mutations: {
    setPlan (state, payload) {
      state.plan = payload
    }
  },
  actions: {
    async getPlan ({ commit, getters }) {
      const data = await Vue.axios
        .get('/user_plans')
        .then(response => response.data)

      const isAlarmMute = Boolean(get(getters.user.usr_extra, 'alarm_mute', false))

      // 안드로이드 알람 설정
      data.upt_list.forEach((item) => {
        if (!Number(item.id)) {
          if (isAlarmMute) {
            window.$EventBus.$emit('reset-alarm-request', {
              id: item.id
            })

            return
          }

          if (item.push === 1 && item.result === 1) {
            window.$EventBus.$emit('reset-alarm-request', {
              id: item.id
            })

            return
          }

          if (item.push === 1 && item.result === 0) {
            const alarmTime = moment(item.time, 'h:m')
            const currentTime = moment(Vue.moment(), 'h:m')

            if (alarmTime.isBefore(currentTime)) {
              window.$EventBus.$emit('reset-alarm-request', {
                id: item.id
              })
            } else {
              window.$EventBus.$emit('set-alarm-request', {
                id: item.id,
                title: '반창꼬 알림',
                body: item.title,
                hour: Number(item.time.split(':')[0]),
                min: Number(item.time.split(':')[1])
              })
            }

            return
          }

          if (item.push === 0) {
            window.$EventBus.$emit('reset-alarm-request', {
              id: item.id
            })

            // return
          }
        }
      })

      commit('setPlan', data)
    },
    async setPlanItem ({ commit, dispatch, state }, payload) {
      await Vue.axios
        .put(`/user_plans/${state.plan.upt_no}`, payload)
        .then(response => response.data)

      await dispatch('getPlan')
    },
    async addPlanItem ({ commit, dispatch, state }, payload) {
      await Vue.axios
        .post('/user_plans', {
          upt_no: state.plan.upt_no,
          title: payload.title,
          time: payload.time,
          memo: payload.memo,
          push: payload.push ? 1 : 0
        })
        .then(response => response.data)

      await dispatch('getPlan')
    },
    async removePlanItem ({ commit, dispatch, state }, payload) {
      await Vue.axios
        .delete(`/user_plans/${state.plan.upt_no}`, {
          data: {
            id: payload.id
          }
        })
        .then(response => response.data)

      if (!Number(payload.id)) {
        window.$EventBus.$emit('reset-alarm-request', {
          id: payload.id
        })
      }

      await dispatch('getPlan')
    }
  }
}
