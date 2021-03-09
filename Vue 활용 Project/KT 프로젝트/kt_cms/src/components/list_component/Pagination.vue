<template>
  <div
    v-show="itemCnt > 0 && pager.pages && pager.pages.length > 1"
    class="paging-wrap"
  >
    <div class="paging">
      <!--
      <a
        class="btn-page-first"
        :class="{'disabled': pager.currentPage === 1}"
        @click="setPage(1)"
      >
        {{ labels.first }}
      </a>
      -->
      <a
        class="btn-page-prev"
        :class="{'disabled': pager.currentPage === 1}"
        @click="SetPage(pager.currentPage - 1)"
      >
        {{ labels.previous }}
      </a>
      <p>
        <a
          v-for="page in pager.pages"
          :key="page"
          :class="{'on': pager.currentPage === page}"
          @click="SetPage(page)"
        >{{ page }}</a>
      </p>
      <a
        class="btn-page-next"
        :class="{'disabled': pager.currentPage === pager.totalPages}"
        @click="SetPage(pager.currentPage + 1)"
      >
        {{ labels.next }}
      </a>
      <div
        v-if="pager.pages && pager.pages.length"
        class="paging-total"
      >
        ( <strong>{{ pager.pages[0] }}-{{ pager.pages[pager.pages.length - 1] }}</strong> / {{ pager.totalPages }} )
      </div>
      <!--
      <a
        class="btn-page-end"
        :class="{'disabled': pager.currentPage === pager.totalPages}"
        @click="setPage(pager.totalPages)"
      >
        {{ labels.last }}
      </a>
      -->
      <div class="paging-line-set">
        페이지당 줄 수 :
        <a
          href=""
          :class="{on: pageSize === 20}"
          @click.prevent="ChangeSize(20)"
        >20,</a>
        <a
          href=""
          :class="{on: pageSize === 50}"
          @click.prevent="ChangeSize(50)"
        >50,</a>
        <a
          href=""
          :class="{on: pageSize === 100}"
          @click.prevent="ChangeSize(100)"
        >100</a>
      </div>
    </div>
  </div>
</template>

<script>
  import paginate from 'jw-paginate'

  const defaultLabels = {
    first: '<<',
    last: '>>',
    previous: '<',
    next: '<'
  }

  const defaultStyles = {
    ul: {
      margin: 0,
      padding: 0,
      display: 'inline-block'
    },
    li: {
      listStyle: 'none',
      display: 'inline',
      textAlign: 'center'
    },
    a: {
      cursor: 'pointer',
      padding: '6px 12px',
      display: 'block',
      float: 'left'
    }
  }

  export default {
    props: {
      items: {
        type: Array,
        required: true
      },
      itemCnt: {
        type: Number,
        default: 0
      },
      initialPage: {
        type: Number,
        default: 1
      },
      pageSize: {
        type: Number,
        default: 30
      },
      currentPage: {
        type: Number,
        default: 1
      },
      maxPages: {
        type: Number,
        default: 10
      },
      labels: {
        type: Object,
        default: () => defaultLabels
      },
      styles: {
        type: Object,
        default: () => { }
      },
      disableDefaultStyles: {
        type: Boolean,
        default: false
      }
    },
    data () {
      return {
        pager: {},
        ulStyles: {},
        liStyles: {},
        aStyles: {}
      }
    },
    created () {
      if (!this.$listeners.changePage) {
        throw new Error('Missing required event listener: "changePage"')
      }

      // set default styles unless disabled
      if (!this.disableDefaultStyles) {
        this.ulStyles = defaultStyles.ul
        this.liStyles = defaultStyles.li
        this.aStyles = defaultStyles.a
      }

      // merge custom styles with default styles
      if (this.styles) {
        this.ulStyles = { ...this.ulStyles, ...this.styles.ul }
        this.liStyles = { ...this.liStyles, ...this.styles.li }
        this.aStyles = { ...this.aStyles, ...this.styles.a }
      }
    },
    updated () {
      // this.pager.currentPage = parseInt(this.$route.query.page)
    },
    methods: {
      SetPage (page, emit = true) {
        const { items, itemCnt, pageSize, maxPages } = this

        // get new pager object for specified page
        const pager = paginate(itemCnt, page, pageSize, maxPages)

        // get new page of items from items array
        // const pageOfItems = items.slice(pager.startIndex, pager.endIndex + 1)
        items.slice(pager.startIndex, pager.endIndex + 1)
        // update pager
        this.pager = pager
        /*
        let newPager = {
          currentPage: this.currentPage,
          endIndex: this.itemCnt - 1,
          endPage: this.
        }

        this.pager.
        */

        // emit change page event to parent component
        if (emit) {
          this.$emit('changePage', page)
        }
      },
      ChangeSize (value) {
        this.$emit('changeSize', value)
      }
    }
  }
</script>

<style scoped>
  a[class^="btn-page-"].disabled {
    pointer-events: none;
    opacity: 0.3;
  }

  .paging-size {
    margin-left: 8px;
    height: 25px;
    background-position: right;
  }

  .paging-line-set a {
    font-size: 14px;
    line-height: 25px;
    font-weight: 400;
    width: initial;
    margin: 0;
    margin-right: 3px;
    color: #999;
  }

  .paging-line-set a.on {
    font-size: 14px;
    line-height: 25px;
    font-weight: 400;
    width: initial;
    margin: 0;
    margin-right: 3px;
    color: #cc0f0f;
  }
</style>
