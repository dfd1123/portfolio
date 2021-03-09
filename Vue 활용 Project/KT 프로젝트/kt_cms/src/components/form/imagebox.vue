<template>
  <div class="preview-thumb-file-input">
    <div
      class="file-input"
      style="white-space: nowrap"
      :style="{'padding-right': !readonly && download ? '0' : '105px'}"
    >
      <input
        ref="inputFile"
        type="file"
        class="input-file"
        @change="handleFile"
        title="파일 업로드 찾기"
        style="pointer-events: none"
      >
      <input
        type="text"
        :value="fileName"
        readonly=""
        placeholder=""
        class="read-file"
        title="업로드된 파일 경로"
        :style="[!readonly && download ? {'width': '30%'} : '']"
      >
      <a
        v-if="!readonly"
        class="button-custom"
        :style="[!readonly && download ? {'position': 'initial', 'margin-left': '10px'} : '']"
        @click="loadFile"
      >찾아보기</a>
    </div>
    <br>
    <img
      v-show="inputData.value"
      ref="image"
      :type="inputData.type"
      :name="inputData.name"
      :id="inputData.name"
      :style="{'max-height': inputData.height || 'auto', 'max-width': inputData.width || 'auto'}"
      style="object-fit: contain;"
    >
    <div
      v-show="!inputData.value"
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
      },
      readonly: {
        type: Boolean,
        default: false
      },
      multiple: {
        type: Boolean,
        default: false
      },
      download: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        fileName: ''
      }
    },

    destroyed () {
      if (this.$refs.image && this.$refs.image.src) {
        URL.revokeObjectURL(this.$refs.image.src)
      }
    },
    watch: {
      'inputData.value': function () {
        if (this.inputData.value === '') {
          this.$refs.inputFile.value = ''
          this.$refs.image.src = ''
          this.fileName = ''
        } else {
          if (!(this.inputData.value instanceof File)) {
            this.$refs.inputFile.value = ''
            this.$refs.image.src = this.inputData.value
            this.fileName = ''
          }
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
      },
      handleFile: function (e) {
        const fileInput = e.target
        URL.revokeObjectURL(this.$refs.image.src)
        this.$refs.image.src = URL.createObjectURL(fileInput.files[0])
        this.fileName = fileInput.files[0].name
        this.$emit('input', fileInput.files[0])
      },
      loadFile () {
        if (!this.readonly) {
          this.$refs.inputFile.click()
        }
      },
      downloadFile () {
        if (this.download) {
          this.$emit('download', { ...this.inputData })
        }
      }
    }
  }
</script>

<style scoped>
  button[type="button"] {
    float: right;
  }

  .preview-thumb-file-input {
    padding-right: 0;
  }

  .preview-thumb-file-input .preview-thumb {
    position: relative;
    margin: 0;
    left: 0;
  }

  .preview-thumb-file-input .button-custom {
    position: absolute;
    top: 0;
    right: 0;
    width: 85px;
    padding: 0;
    text-align: center;
    height: 38px;
    font-size: 14px;
    color: #fff;
    line-height: 38px;
    background: #595959;
    border-radius: 3px;
  }
</style>
