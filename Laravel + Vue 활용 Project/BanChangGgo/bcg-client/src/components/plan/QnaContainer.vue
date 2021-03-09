<template>
  <div class="qna_container">
    <!-- 질문카드 -->
    <article class="question_card" :class="{ symbol : symbol }">
      <div class="in_symbol" v-show="symbol">
        <img src="/assets/images/logos/logo_bcg_character.svg" alt="반창꼬 캐릭터" class="orange" />
      </div>
      <div class="tbl_cell_middle">
        <span class="in_number">{{ questionNumber }}</span>
        <h3 class="in_title" v-html="cardInfo.title"></h3>
        <p class="in_desc">{{ cardInfo.desc }}</p>
      </div>
    </article>
    <!-- end -->
    <transition-group appear name="todo-item" tag="div">
      <!-- 답변내용 -->

      <!-- template 1) select일때 -->
      <div class="answer_section" v-if="cardInfo.type === 'select'" :key="cardInfo.type">
        <select
          class="form_select"
          v-model="cardInfo.value"
          :init="cardInfo.value = cardInfo.value || get(cardInfo, 'option[0].name', null)"
        >
          <option v-for="(item, index) in cardInfo.option" :key="index">{{ item.name }}</option>
        </select>
      </div>
      <!-- end -->

      <!-- template 2) single 선택일때 (radio) -->
      <div class="answer_section" v-else-if="cardInfo.type === 'single'" :key="cardInfo.type">
        <div
          class="form_input"
          v-for="(item, index) in cardInfo.option"
          :key="index"
          :class="{ checked: cardInfo.value === item.name }"
          :init="cardInfo.value = cardInfo.value || get(cardInfo, 'option[0].name', null)"
        >
          <label :for="'singleCheck'+$vnode.key+index" class="checkbox_02">
            <!-- 체크박스 -->
            <input
              :id="'singleCheck'+$vnode.key+index"
              :name="$vnode.key"
              type="radio"
              class="none"
              :checked="cardInfo.value === item.name"
              @change="cardInfo.value = cardInfo.option[index].name"
            />
            <checkbox-svg-type02></checkbox-svg-type02>
            <!-- end -->
            <!-- 내용 -->
            <span class="in_name">{{ item.name }}</span>
            <p class="in_desc" v-if="item.desc">{{ item.desc }}</p>
            <!-- end -->
          </label>
          <div class="in_input_textarea" v-if="cardInfo.value === item.name && 'value' in item">
            <textarea-autosize v-model="item.value" placeholder="내용을 입력해주세요." rows="1" />
          </div>
        </div>
      </div>
      <!-- end -->

      <!-- template 3) multi 선택일때 (checkbox) -->
      <div class="answer_section" v-else-if="cardInfo.type == 'multi'" :key="cardInfo.type">
        <div
          class="form_input"
          v-for="(item, index) in cardInfo.option"
          :key="index"
          :class="{ checked : item.checked }"
        >
          <label :for="'multiCheck'+index" class="checkbox_02">
            <!-- 체크박스 -->
            <input :id="'multiCheck'+index" type="checkbox" class="none" v-model="item.checked" />
            <checkbox-svg-type02></checkbox-svg-type02>
            <!-- end -->
            <span class="in_name">{{ item.name }}</span>
            <p class="in_desc" v-if="item.desc">{{ item.desc }}</p>
          </label>
          <div class="in_input_textarea" v-if="item.checked && 'value' in item">
            <textarea-autosize v-model="item.value" placeholder="내용을 입력해주세요." rows="1" />
          </div>
        </div>
      </div>
      <!-- end -->

      <!-- template 4) number 일 때 -->
      <div class="answer_section" v-else-if="cardInfo.type == 'number'" :key="cardInfo.type">
        <select
          class="form_select"
          v-model="cardInfo.value"
          :init="cardInfo.value = cardInfo.value || cardInfo.range[0]"
        >
          <option
            v-for="(item, index) in range(cardInfo.range[0],cardInfo.range[1])"
            :key="index"
            :value="item"
          >{{ item }}{{ cardInfo.unit }}</option>
        </select>
      </div>
      <!-- end -->

      <!-- template 5) time 일 때 -->
      <div class="answer_section" v-else-if="cardInfo.type == 'time'" :key="cardInfo.type">
        <el-time-picker
          v-model="cardInfo.value"
          popper-class="answer_section__timepicker"
          :editable="false"
          :format="'HH:mm'"
          value-format="HH:mm"
          placeholder="시간을 선택해주세요"
        ></el-time-picker>
      </div>
      <!-- end -->

      <!-- template 6) textarea 일 때 -->
      <div class="answer_section" v-else-if="cardInfo.type == 'text'" :key="cardInfo.type">
        <div class="form_textarea">
          <textarea-autosize v-model="cardInfo.value" placeholder="기타사항을 입력해주세요" />
        </div>
      </div>
      <!-- end -->

      <!-- //답변내용 -->
    </transition-group>
  </div>
</template>

<script>
import Vue from 'vue'
import TextareaAutosize from 'vue-textarea-autosize'
import { range, get } from 'lodash'

Vue.use(TextareaAutosize)

export default {
  name: 'qna-container',
  props: ['symbol', 'questionNumber', 'cardInfo'],
  data () {
    return {
    }
  },
  methods: {
    ...{ range, get }
  }
}
</script>

<style lang="scss" scoped>
.orange {
  position: relative;
  animation: orangeAni 3.5s 10;
  animation-timing-function: ease-in-out;
}
.qna_container {
  margin-bottom: 30px;
}

.answer_section {
  width: 100%;
  transition: all 0.6s;
}
</style>
