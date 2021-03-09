module.exports = {
  apps: [
    {
      name: 'trustorn-infura',
      script: './index.js',

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      instances: 1,
      exec_mode: 'fork',
      merge_logs: true,
      time: true,
      autorestart: true,
      watch: false,
      source_map_support: false,
      max_memory_restart: '1024M',
      env: {
        NODE_ENV: 'development',
      },
      env_production: {
        NODE_ENV: 'production',
      },
    },
  ],
};
