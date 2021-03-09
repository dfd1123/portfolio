<template>
  <div>
    <div v-if="multiple !== true">
      <!-- single file -->
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
          readonly=""
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
        <!--
        <a
          href="javascript:;"
          class="btn-close"
        >파일삭제</a>
        -->
        <a
          v-if="!readonly"
          href="javascript:;"
          class="btn-01 type-03 round"
          :style="[!readonly && download ? {'position': 'static', 'margin-left': '10px'} : '']"
          @click="loadFile"
        >찾아보기</a>
        <a
          v-if="download"
          href="javascript:;"
          class="btn-01 type-03 round"
          :style="[!readonly && download ? {'position': 'static', 'margin-left': '10px'} : '']"
          @click="downloadFile"
        >다운로드</a>
      </div>
    </div>
    <div v-else>
      <!--
      <ul class="file-parent">
        <li class="add-input">
          <div class="add-file">
            <div class="file-input">
              <input
                type="file"
                name="file01"
                id="f01"
                class="input-file"
                onchange="front.form.inputFile(this)"
                title="파일 업로드 찾기"
              >
              <input
                type="text"
                name="file-name01"
                id="fn01"
                readonly=""
                placeholder=""
                class="read-file"
                title="업로드된 파일 경로"
              >
              <a
                href="javascript:;"
                class="btn-close"
              >파일삭제</a>
              <a
                href="javascript:;"
                class="btn-01 type-03 round"
              >찾아보기</a>
            </div>
            <p class="btn-ui">
              <a
                href="javascript:;"
                onclick="front.form.addInputFile(this);"
                class="btn-add"
              ></a>
              <a
                href="javascript:;"
                onclick="front.form.deleteInputFile(this);"
                class="btn-del"
              ></a>
            </p>
          </div>
        </li>
      </ul>
      -->
    </div>
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
    watch: {
      'inputData.value': function () {
        if (this.inputData.value === '') {
          this.$refs.inputFile.value = ''
          this.fileName = ''
        }
      },
      'inputData.fileName': function () {
        this.fileName = this.inputData.fileName
      }
    },
    methods: {
      handleFile: function (e) {
        const fileInput = e.target
        this.fileName = fileInput.files[0].name
        this.$emit('input', fileInput.files[0])

        console.log(this.fileName)
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
</style>
