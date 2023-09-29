#!/bin/sh
set -e
# Execute wait-for-mysql.sh before the if statement
sh /wait-for-mysql.sh mysql

ls html
# Check if the migration_flag file does not exist
if [ ! -f "/html/database/migration_flag" ]; then
  # Execute the migration commands
  /usr/local/bin/php /html/artisan migrate
  /usr/local/bin/php /html/artisan migrate --seed
  /usr/local/bin/php /html/artisan storage:link

  # Create the migration_flag file
  touch /html/database/migration_flag
fi
/usr/local/bin/php /var/www/html/artisan config:cache
/usr/local/bin/php /var/www/html/artisan route:cache
/usr/local/bin/php /var/www/html/artisan view:cache
/usr/local/bin/php /var/www/html/artisan optimize

# Execute the CMD from the Dockerfile
exec "$@"