<template>
  <div>
    <div class="tbl_scroll">
      <table class="invoice-prdt-table">
        <tbody class="tbl-con">
          <tr v-for="item in items" :key="'items'+item.option.op_id" class="order-prdt">
            <td class="_service">
              <div class="_desc">{{item.title}}</div>
              <small class="_detail">{{item.option.op_simple_intro}}</small>
            </td>
            <td class="_concept">
              <div class="_desc">{{item.option.op_concept}}</div>
            </td>
            <td class="_price _tbody_price">
              <div class="_desc">
                <b class="txt-en">{{item.total_price}}</b>원
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <table class="invoice-prdt-table">
      <tfoot class="tbl-ft">
        <tr>
          <td class="_doublecol _tfoot_cell" colspan="2">
            <div class="_desc txt-en">total price</div>
          </td>
          <td class="_price _tfoot_cell">
            <div class="_desc">
              <b class="txt-en">{{NumberFormat(invoice.total_price || 0)}}</b>원
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</template>

<script>
export default {
  name: "sub-invoice-table",
  props:{
    items:{
      type:Array,
      required:true
    },
    invoice:{
      type:Object,
      required:true
    }
  }
};
</script>

<style scoped>
</style>