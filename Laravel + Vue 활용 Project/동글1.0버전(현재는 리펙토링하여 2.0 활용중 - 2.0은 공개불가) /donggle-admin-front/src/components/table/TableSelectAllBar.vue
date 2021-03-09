<template>
  <div>
    <div v-show="isSelectAllVisible" class="in-choice-wrap">
      <input
        v-uniq-id="'check-all'"
        type="checkbox"
        class="chck-box none"
        @change="$emit('update:is-select-all', $event.target.checked)"
        :checked="isSelectAll"
      />
      <label v-uniq-for="'check-all'" class="check-gradi-circle in-check-circle"></label>
      <ul class="in-sorting">
        <li class="type active">
          <label class="word" @click="$emit('update:is-select-all', true)">전체선택</label>
        </li>
        <li class="type">
          <label class="word" @click="$emit('update:is-select-all', false)">선택해제</label>
        </li>
      </ul>

      <div class="in-button-group">
        <button
          v-for="(button, index) in selectedReadyButtonList"
          :key="index"
          type="button"
          class="rounded-square-xs-btn btn-outline-navy type-shadow"
          @click="$emit('selected-ready-button-click', index)"
        >{{button}}</button>
      </div>
    </div>

    <select
      v-show="isOrderByOptionVisible"
      v-if="orderByOptions.length > 0"
      v-model="orderBySelectData"
      class="form-select in-align-right"
      @change="$emit('order-by-select-change', $event)"
    >
      <option
        v-for="orderByOption in orderByOptions"
        :key="orderByOption.value"
        :value="orderByOption.value"
      >{{orderByOption.label}}</option>
    </select>
  </div>
</template>

<script>
export default {
  name: 'TableSelectAllBar',
  props: {
    isSelectAll: {
      type: Boolean,
      default: false
    },
    isSelectAllVisible: {
      type: Boolean,
      default: true
    },
    isOrderByOptionVisible: {
      type: Boolean,
      default: true
    },
    orderBySelect: {
      type: String,
      default: null
    },
    orderByOptions: {
      type: Array,
      default: () => []
    },
    selectedReadyButtonList: {
      type: Array,
      default: () => []
    }
  },
  data () {
    return {
      orderBySelectData: this.orderBySelect
    }
  },
  watch: {
    orderBySelect () {
      this.orderBySelectData = this.orderBySelect
    }
  }
}
</script>

<style>
</style>
