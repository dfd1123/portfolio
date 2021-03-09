
const data = [
  {
    id: 'dashboards',
    icon: 'iconsminds-home',
    label: 'menu.dashboards',
    to: '/master/app/dashboards/main'
  },
  {
    id: 'main',
    icon: 'iconsminds-testimonal',
    label: 'menu.main',
    to: '/master/app/main',
    subs: [
      {
        icon: '',
        label: 'menu.user',
        to: '/master/app/main/user'
      },
      {
        icon: '',
        label: 'menu.batch',
        to: '/master/app/main/batch'
      },
      {
        icon: '',
        label: 'menu.template',
        to: '/master/app/main/template'
      },
      {
        icon: '',
        label: 'menu.total',
        to: '/master/app/main/total'
      },
      {
        icon: '',
        label: 'menu.push',
        to: '/master/app/main/push'
      }
    ]
  },
  {
    id: 'comparison',
    icon: 'iconsminds-testimonal',
    label: 'menu.comparison',
    to: '/master/app/comparison',
    subs: [
      {
        icon: '',
        label: 'menu.student',
        to: '/master/app/comparison/student'
      },
      {
        icon: '',
        label: 'menu.major',
        to: '/master/app/comparison/major'
      },
      {
        icon: '',
        label: 'menu.dept',
        to: '/master/app/comparison/dept'
      },
      {
        icon: '',
        label: 'menu.coll',
        to: '/master/app/comparison/coll'
      },
      {
        icon: '',
        label: 'menu.descmajorcd',
        to: '/master/app/comparison/descmajorcd'
      },
      {
        icon: '',
        label: 'menu.descdeptcd',
        to: '/master/app/comparison/descdeptcd'
      },
      {
        icon: '',
        label: 'menu.desccollcd',
        to: '/master/app/comparison/desccollcd'
      },
      {
        icon: '',
        label: 'menu.numpplmajor',
        to: '/master/app/comparison/numpplmajor'
      },
      {
        icon: '',
        label: 'menu.numppldept',
        to: '/master/app/comparison/numppldept'
      },
      {
        icon: '',
        label: 'menu.numpplcoll',
        to: '/master/app/comparison/numpplcoll'
      }
      /* {
        icon: '',
        label: 'menu.gender',
        to: '/master/app/comparison/gender'
      } */
    ]
  }
]
export default data
