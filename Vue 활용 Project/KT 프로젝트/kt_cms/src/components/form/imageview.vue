<template>
  <div>
    <template v-if="inputData.value">
      <img
        v-show="inputData.value"
        ref="image"
        :type="inputData.type"
        :name="inputData.name"
        :id="inputData.name"
        :src="inputData.value"
        :style="{'max-height': inputData.height || 'auto', 'max-width': inputData.width || 'auto'}"
        style="object-fit: contain;"
      >
      <button
        type="button"
        class="btn-01 type-03"
        @click="print"
      >
        <span>인쇄</span>
      </button>
    </template>
    <div
      v-else
      class="preview-thumb"
    ></div>
  </div>
</template>

<script>
  export default {
    props: {
      inputData: {
        type: Object,
        default () {
          return {}
        }
      }
    },
    watch: {
      'inputData.value': function () {
        if (!this.inputData.value) {
          this.$refs.inputFile.value = ''
          this.fileName = ''
        }
      }
    },
    methods: {
      print () {
        const image = this.$refs.image.cloneNode()
        image.style.maxHeight = 'initial'

        const popup = window.open(null, '_blank') // required for IE (null)
        popup.document.write(image.outerHTML)
        popup.document.close() // required for IE
        popup.focus() // required for IE
        popup.print()
        setTimeout(() => { popup.close() }, 0) // required for IE
      }
    }
  }
</script>

<style lang="css" scoped>
  button[type="button"] {
    float: right;
  }
</style>
