module.exports = {
  apps: [
    {
      name: 'realtime-pusher-cointouse',
      script: './index.js',

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      args: '',
      instances: 1,
      exec_mode: 'cluster',
      merge_logs: true,
      autorestart: true,
      watch: false,
      max_memory_restart: '1024M',
      env: {
        NODE_ENV: 'development',
      },
      env_production: {
        NODE_ENV: 'production',
      },
    },
  ],

  deploy: {
    development: {
      user: 'root',
      host: [{ host: '167.179.88.124', port: '22' }],
      ref: 'origin/master',
      repo:
        'git@config-realtime-pusher-cointouse:pocketcompany/realtime-pusher-cointouse.git',
      path: '/root/node/realtime-pusher-cointouse',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env development',
    },
    production: {
      user: 'wmaster',
      host: [{ host: '167.99.69.139', port: '4522' }],
      ref: 'origin/master',
      repo: 'git@github.com:cointouse/realtime-pusher.git',
      path: '~/repository/realtime-pusher',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env production',
    },
  },
};
