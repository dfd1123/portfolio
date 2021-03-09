module.exports = {
  apps: [
    {
      name: 'eth-io-server-cointouse',
      script: './index.js',

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      args: '',
      // instances: 1,
      // exec_mode: 'cluster',
      merge_logs: true,
      autorestart: true,
      watch: false,
      max_memory_restart: '2048M',
      env: {
        NODE_ENV: 'development',
      },
      env_production: {
        NODE_ENV: 'production',
      },
    },
  ],

  deploy: {
    production: {
      user: 'hmaster',
      host: [{ host: '45.76.180.74', port: '4522' }],
      ref: 'origin/master',
      repo: 'git@github.com:cointouse/eth-io-server-sharebits.git',
      path: '/home/hmaster/eth-io-server',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env production',
    },
  },
};
