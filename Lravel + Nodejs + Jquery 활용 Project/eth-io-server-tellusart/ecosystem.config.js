module.exports = {
  apps: [
    {
      name: 'eth-io-server',
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
      host: [{ host: '178.128.214.133', port: '22' }],
      ref: 'origin/master',
      repo:
        'git@config-eth-io-server-tellusart:pocketcompany/eth-io-server-tellusart.git',
      path: '/var/www/fund4/eth-io-server',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env development',
    },
    production: {
      key: '~/.ssh/LightsailDefaultKey-ap-northeast-2.pem',
      user: 'ubuntu',
      host: [{ host: '13.125.90.117', port: '22' }],
      ref: 'origin/master',
      repo:
        'git@config-eth-io-server-tellusart:pocketcompany/eth-io-server-tellusart.git',
      path: '~/etc/eth-io-server',
      'post-deploy':
        'npm install && pm2 reload ecosystem.config.js --env production',
    },
  },
};
