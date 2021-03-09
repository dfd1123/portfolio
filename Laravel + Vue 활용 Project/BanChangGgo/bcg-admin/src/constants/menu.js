
const data = [
  {
    id: 'dashboards',
    icon: 'iconsminds-home',
    label: 'menu.dashboards',
    to: '/app/dashboards/main'
  },
  {
    id: 'planners',
    icon: 'iconsminds-plaster',
    label: 'menu.planners',
    to: '/app/planners',
    subs: [
      {
        icon: '',
        label: 'menu.user',
        to: '/app/planners/user'
      }, {
        icon: '',
        label: 'menu.checklist',
        to: '/app/planners/checklist'
      }, {
        icon: '',
        label: 'menu.schedule',
        to: '/app/planners/schedule'
      }/*, {
        icon: '',
        label: 'menu.symptom',
        to: '/app/planners/symptom'
      }, {
        icon: '',
        label: 'menu.spinfo',
        to: '/app/planners/spinfo'
      } */
    ]
  },
  {
    id: 'reports',
    icon: 'iconsminds-plant',
    label: 'menu.reports',
    to: '/app/reports',
    subs: [{
      icon: '',
      label: 'menu.batch',
      to: '/app/reports/batch'
    }, {
      icon: '',
      label: 'menu.report',
      to: '/app/reports/report',
      subs: [
        {
          icon: '',
          label: 'menu.list',
          to: '/app/reports/report/list'
        }, {
          icon: '',
          label: 'menu.plan',
          to: '/app/reports/report/plan'
        }, {
          icon: '',
          label: 'menu.write',
          to: '/app/reports/report/write'
        }
      ]
    }, {
      icon: '',
      label: 'menu.info',
      to: '/app/reports/info',
      subs: [
        {
          icon: '',
          label: 'menu.disease',
          to: '/app/reports/info/disease'
        }, {
          icon: '',
          label: 'menu.medicine',
          to: '/app/reports/info/medicine'
        }, {
          icon: '',
          label: 'menu.nutrition',
          to: '/app/reports/info/nutrition'
        }, {
          icon: '',
          label: 'menu.health',
          to: '/app/reports/info/health'
        }
      ]
    }
    ]
  },
  {
    id: 'customers',
    icon: 'iconsminds-consulting',
    label: 'menu.customers',
    to: '/app/customers',
    subs: [
      {
        icon: '',
        label: 'menu.notice',
        to: '/app/customers/notice'
      }
    ]
  },
  {
    id: 'etcs',
    icon: 'iconsminds-testimonal',
    label: 'menu.etcs',
    to: '/app/etcs',
    subs: [
      {
        icon: '',
        label: 'menu.admin',
        to: '/app/etcs/admin'
      },
      {
        icon: '',
        label: 'menu.term',
        to: '/app/etcs/term'
      }
    ]
  }
]
export default data
