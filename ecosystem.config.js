module.exports = {
  apps: [
    {
      name: 'pasiones-platform',
      script: 'npm',
      args: 'start',
      cwd: '/home/redsocial.novapasion.com/app',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'production',
        PORT: 3000
      },
      error_file: '/home/redsocial.novapasion.com/logs/pm2-error.log',
      out_file: '/home/redsocial.novapasion.com/logs/pm2-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      merge_logs: true,
      kill_timeout: 5000
    }
  ]
}
