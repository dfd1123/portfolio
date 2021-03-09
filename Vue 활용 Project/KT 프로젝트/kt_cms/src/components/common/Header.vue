<template>
  <div>
    <header id="dHead">
      <h1><a href="/">KT 5G AR MAKERS</a></h1>
      <nav id="gnb">
        <ul>
          <!--
            // @gnb
            // - li 태그에 actived클래스 추가시 메뉴 활성화
            -->
          <!-- @ CMS 계정 메뉴 -->
          <li
            v-for="menu in menus"
            :key="menu.title"
            :class="menu.class"
          >
            <span></span>
            <router-link
              :to="menu.link"
              v-if="menu.link === '/'"
              exact
            >
              {{ menu.title }}
            </router-link>
            <router-link
              :to="menu.link"
              v-else
            >
              {{ menu.title }}
            </router-link>
            <span :class="ActiveClass(menu.link)"></span>
          </li>
        </ul>
      </nav>
      <GlobalMenu />
    </header>
  </div>
</template>

<script>
  import GlobalMenu from '@/components/common/GlobalMenu.vue'

  export default {
    name: 'Header',
    components: {
      GlobalMenu
    },
    data: function () {
      return {
        kind: this.$route.meta.page_category,
        menus: [
          {
            kind: 'ServiceManagement',
            title: '서비스',
            admin: false,
            link: '/service',
            class: 'cms-gnb-item-01',
            active: false
          },
          {
            kind: 'DeviceManagement',
            title: '단말',
            admin: false,
            link: '/device',
            class: 'cms-gnb-item-02',
            active: false
          },
          {
            kind: 'ArZoneManagement',
            title: 'AR영역',
            admin: true,
            link: '/arzone',
            class: 'cms-gnb-item-03',
            active: false
          },
          {
            kind: 'ArObjectManagement',
            title: 'AR대상',
            admin: true,
            link: '/arobject',
            class: 'cms-gnb-item-03',
            active: false
          },
          {
            kind: 'ArTaskManagement',
            title: 'AR업무',
            admin: true,
            link: '/artask',
            class: 'cms-gnb-item-03',
            active: false
          },
          {
            kind: 'ServiceStatistics',
            title: '통계',
            admin: false,
            link: '/statistics',
            class: 'cms-gnb-item-04',
            active: false
          },
          {
            kind: 'CommonManagement',
            title: '공통관리',
            admin: false,
            link: '/common',
            class: 'cms-gnb-item-03',
            active: false
          }
        ]
      }
    },
    computed: {
      SplitUrl () {
        return this.$route.path.split('/').slice(1)
      }
    },
    mounted () {
      this.$nextTick(() => {
        const gnbHeight = document.getElementById('dHead').offsetHeight
        const dbodyHeight = document.getElementById('dBody').offsetHeight

        if (dbodyHeight < gnbHeight) document.getElementById('dBody').setAttribute('style', 'min-height:' + gnbHeight + 'px;')
        window.elementUI.addClass(document.getElementById('snb'), 'open')
        window.elementUI.addClass(document.getElementById('contents'), 'open')
        window.elementUI.addClass(document.getElementById('dFoot'), 'open')
      })
    },
    methods: {
      ActiveClass: function (link) {
        if ('/' + this.SplitUrl[0] === link) {
          return 'actived'
        } else {
          return ''
        }
      }
    }
  }
</script>

<style lang="css" scoped>
  #gnb > ul > li > span {
    opacity: 0;
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    display: block;
    width: 4px;
    height: 100%;
    background: #cc0f0f;
    opacity: 0;
    z-index: 5;
  }
  #gnb > ul > li > a.router-link-active ~ span {
    opacity: 1;
  }

  #gnb > ul > li > a.router-link-active {
    background-color: #262835;
  }
</style>
