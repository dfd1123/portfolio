module.exports = {
  apps: [
    {
      name: 'exchange-order',
      script: './index.js',

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      args: '',
      // instances: 1,
      // exec_mode: 'fork',
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
      repo: 'git@config-exchange-order:pocketcompany/exchange-order.git',
      path: '/root/node/exchange-order',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env development',
    },
    production: {
      user: 'jmaster',
      host: [{ host: '207.148.96.58', port: '4522' }],
      key: 'C:/Users/user/Documents/KEY/ctsssh.pem',
      ref: 'origin/master',
      repo: 'git@github.com:cointouse/exchange-scanner.git',
      path: '/var/www/git/exchange-scanner',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env production',
    },
  },
};
