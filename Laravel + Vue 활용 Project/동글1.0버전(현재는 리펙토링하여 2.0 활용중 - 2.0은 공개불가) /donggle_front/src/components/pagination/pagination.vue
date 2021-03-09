<template>
  <div
    v-show="itemCnt > 0 && pager.pages && pager.pages.length > 1"
    class="paging-wrap"
  >
    <div class="paging">
      <a
        class="btn-page-first"
        :class="{'disabled': pager.currentPage === 1}"
        @click="SetPage(1)"
      >{{ labels.first }}</a>
      <a
        class="btn-page-prev"
        :class="{'disabled': pager.currentPage === 1}"
        @click="SetPage(pager.currentPage - 1)"
      >{{ labels.previous }}</a>
      <div class="page-wrap">
        <a
          v-for="page in pager.pages"
          :key="page"
          :class="{'on': pager.currentPage === page}"
          @click="SetPage(page)"
        >{{ page }}</a>
      </div>
      <a
        class="btn-page-next"
        :class="{'disabled': pager.currentPage === pager.totalPages}"
        @click="SetPage(pager.currentPage + 1)"
      >{{ labels.next }}</a>
      <a
        class="btn-page-end"
        :class="{'disabled': pager.currentPage === pager.totalPages}"
        @click="SetPage(pager.totalPages)"
      >{{ labels.last }}</a>
    </div>
  </div>
</template>

<script>
  import paginate from 'jw-paginate'

  const defaultLabels = {
    first: '<<',
    last: '>>',
    previous: '<',
    next: '>'
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
      SetPage (page) {
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
        this.$emit('changePage', page)
      }
    }
  }
</script>

<style lang="scss" scoped>
  .paging-wrap {
    position: relative;
    height: 3em;
    user-select: none;
    .paging {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      .page-wrap {
        display: inline;
      }
    }
    a {
      margin: 0px 8px;
      cursor: pointer;
      color: #c8c8c8;
    }
    a:hover,
    a:focus {
      color: #333;
    }
    a.on {
      color: #333;
      font-weight: 600;
    }
  }
  .dg-cs-wrapper .paging-wrap {
    height: 104px;
  }
</style>
