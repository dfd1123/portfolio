module.exports = {
  apps: [
    {
      name: "ws-pub-spowide",
      script: "./index.js",

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      node_args: "--max_old_space_size=8192",
      instances: 1,
      exec_mode: "fork",
      merge_logs: true,
      time: true,
      autorestart: true,
      watch: false,
      source_map_support: false,
      max_memory_restart: "8192M",
      env: {
        NODE_ENV: "development"
      },
      env_production: {
        NODE_ENV: "production"
      }
    }
  ]
};
