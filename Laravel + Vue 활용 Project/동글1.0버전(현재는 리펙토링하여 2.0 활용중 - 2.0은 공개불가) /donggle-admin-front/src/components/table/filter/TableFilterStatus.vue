<template>
  <div class="search-option-check-group">
    <span class="in-tit">{{title}}</span>
    <div class="in-check-list">
      <template v-for="(option, index) in options">
        <input
          v-model="selected"
          v-uniq-id="`filter-${option.value || 'all'}-${index}`"
          type="radio"
          v-uniq-name="'filter-status'"
          class="chck-box none"
          :key="`filter-input-${option.value}-${index}`"
          :value="option.value"
          @change="change"
          @click="click"
        />
        <label
          v-uniq-for="`filter-${option.value || 'all'}-${index}`"
          :key="`filter-label-${option.value}-${index}`"
          class="rounded-square-xs-btn btn-outline-gray-light check-outline-navy"
        >{{option.label}}</label>
      </template>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableFilterStatus',
  props: {
    isUnselectable: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ''
    },
    select: {
      type: null,
      required: true,
      default: null
    },
    options: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data () {
    return {
      selected: this.select || ''
    }
  },
  watch: {
    select () {
      this.selected = this.select
    }
  },
  methods: {
    change (e) {
      const value = e.target.value

      this.$emit('update:select', value)
      this.$emit('change', value)
    },
    click (e) {
      if (this.isUnselectable) {
        return
      }

      const value = e.target.value
      if (this.selected === value) {
        this.$emit('update:select', null)
        this.$emit('change', null)
      }
    }
  }
}
</script>

<style>
</style>
