<template>
  <div>
    <ul class="invoice-customar-info">
      <li class="_infomation _bdtop_none">
        <dl class="customar-info">
          <dt>이름 :</dt>
          <dd>{{invoice.req_name}}</dd>
        </dl>
      </li>
      <li class="_infomation">
        <dl class="customar-info">
          <dt>연락처 :</dt>
          <dd>{{invoice.req_tel}}</dd>
        </dl>
      </li>
      <li class="_infomation">
        <dl class="customar-info">
          <dt>회사명 :</dt>
          <dd>{{invoice.req_company}}</dd>
        </dl>
      </li>
      <li class="_infomation">
        <dl class="customar-info">
          <dt>
            진행
            <br />가능 일자
          </dt>
          <dd class="_date">{{invoice.req_date}}</dd>
        </dl>
      </li>
      <li class="_infomation">
        <dl class="customar-info">
          <dt>
            기타
            <br />요청사항
          </dt>
          <dd v-html="invoice.req_contents">
          </dd>
        </dl>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "sub-invoice-result",
  beforeCreate(){
    if(!this.$route.query.rq_id){
      alert('잘못된 페이지 요청입니다.');
      this.$router.push('/home');
    }
  },
  props:{
    invoice:{
      type:Object,
      required:true
    }
  }
};
</script>

<style scoped>
</style>