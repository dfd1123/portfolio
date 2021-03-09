<template>
  <div>
    <!-- lnb -->
    <aside id="snb">
      <ul>
        <li
          v-for="menu in menus"
          :key="menu.title"
        >
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
        </li>
      </ul>
    </aside>
    <!-- //lnb -->
  </div>
</template>
<script>
  export default {
    name: 'SubMenu',
    data: function () {
      return {
        menus: [],
        menuList: {
          service: [
            {
              title: '상품',
              link: '/service/unit'
            },
            {
              title: '고객사',
              link: '/service/customer'
            },
            {
              title: '서비스',
              link: '/service/service'
            },
            {
              title: '확장속성',
              link: '/service/extention'
            },
            {
              title: '사용자',
              link: '/service/useracc'
            },
            {
              title: '사용자서비스가입',
              link: '/service/userservice'
            }
          ],
          device: [
            {
              title: '단말관리',
              link: '/device/device'
            },
            {
              title: '앱관리',
              link: '/device/app'
            }
          ],
          arzone: [
            {
              title: '작업구역관리',
              link: '/arzone/zone'
            }
          ],
          arobject: [
            {
              title: '설비관리',
              link: '/arobject/facility'
            },
            {
              title: '인식데이터',
              link: '/arobject/facility_manager'
            },
            {
              title: '증강컨텐츠',
              link: '/arobject/ardata'
            },
            {
              title: '뷰디자이너',
              link: '/arobject/view'
            }
          ],
          artask: [
            {
              title: '작업관리',
              link: '/artask/work'
            },
            {
              title: 'AR협업통화',
              link: '/artask/videocall'
            }
          ],
          common: [
            {
              title: '공지사항',
              link: '/common/notice'
            },
            {
              title: '문의게시판',
              link: '/common/qna'
            }
          ]
        }
      }
    },
    created () {
      this.updateMenu()
    },
    watch: {
      $route () {
        this.updateMenu()
      }
    },
    computed: {
      SplitUrl () {
        return this.$route.path.split('/').slice(1)
      }
    },
    methods: {
      updateMenu () {
        this.menus = this.menuList[this.SplitUrl[0]]
      }
    }
  }
</script>
<style lang="css" scoped>
  #snb > ul > li > a.router-link-active {
    font-weight: 700;
    border-bottom-color: #cc0f0f;
    opacity: 1;
  }
</style>
