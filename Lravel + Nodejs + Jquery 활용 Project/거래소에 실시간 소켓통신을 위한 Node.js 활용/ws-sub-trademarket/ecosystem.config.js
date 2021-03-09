module.exports = {
  apps: [
    {
      name: "ws-sub-spowide",
      script: "./index.js",

      // Options reference: https://pm2.io/doc/en/runtime/reference/ecosystem-file/
      /**
       * instances:
       * 0/max to spread the app across all CPUs
       * -1 to spread the app across all CPUs - 1
       * number to spread the app across number CPUs
       */
      node_args: "--max_old_space_size=4096",
      instances: -1,
      exec_mode: "cluster",
      merge_logs: true,
      time: true,
      autorestart: true,
      watch: false,
      source_map_support: false,
      max_memory_restart: "4096M",
      env: {
        NODE_ENV: "development"
      },
      env_production: {
        NODE_ENV: "production"
      }
    }
  ]
};
