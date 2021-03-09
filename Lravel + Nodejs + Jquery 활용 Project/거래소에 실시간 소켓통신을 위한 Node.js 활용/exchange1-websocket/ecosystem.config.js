module.exports = {
  apps: [
    {
      name: 'exchange1-websocket',
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
        PORT: 8080,
        NODE_ENV: 'development',
      },
      env_production: {
        PORT: 8080,
        NODE_ENV: 'production',
      },
    },
  ],

  deploy: {
    development: {
      user: 'root',
      host: [{ host: '45.76.49.103', port: '22' }],
      ref: 'origin/master',
      repo:
        'git@github-exchange1-websocket:pocketcompany/exchange1-websocket.git',
      path: '/var/www/exchange1/exchange1-websocket',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env development',
    },
    /*
    production: {
      user: 'wmaster',
      host: [{ host: '167.99.69.139', port: '4522' }],
      ref: 'origin/master',
      repo: 'git@github.com:cointouse/realtime-pusher.git',
      path: '~/repository/realtime-pusher',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env production',
    },
    */
  },
};
