#!/bin/sh

DB_HOST=${DB_HOST:-"127.0.0.1"}
DB_CONNECTION=${DB_CONNECTION:-"mysql"}

if [ "$DB_CONNECTION" = "mysql" ] && ( [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ] ); then

  echo "[i] Starting local MySQL server..."

  if [ ! -d "/run/mysqld" ]; then
    mkdir -p /run/mysqld
  fi

  if [ -d /server/mysql ]; then
    echo "[i] MySQL directory already present, skipping creation"
  else
    echo "[i] MySQL data directory not found, creating initial DBs"

    mysql_install_db --user=root > /dev/null

    MYSQL_DATABASE=${DB_DATABASE:-"homestead"}
    MYSQL_USER=${DB_USERNAME:-"homestead"}
    MYSQL_PASSWORD=${DB_PASSWORD:-"secret"}

    if [ "$MYSQL_ROOT_PASSWORD" = "" ]; then
      MYSQL_ROOT_PASSWORD=${MYSQL_PASSWORD}
      echo "[i] MySQL root Password: $MYSQL_ROOT_PASSWORD"
    fi

    tfile=`mktemp`
    if [ ! -f "$tfile" ]; then
        return 1
    fi

  cat << EOF > $tfile
USE mysql;
FLUSH PRIVILEGES;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY "$MYSQL_ROOT_PASSWORD" WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
ALTER USER 'root'@'localhost' IDENTIFIED BY '';
EOF

    if [ "$MYSQL_DATABASE" != "" ]; then
      echo "[i] Creating database: $MYSQL_DATABASE"
      echo "CREATE DATABASE IF NOT EXISTS \`$MYSQL_DATABASE\` CHARACTER SET utf8 COLLATE utf8_general_ci;" >> $tfile

      if [ "$MYSQL_USER" != "" ]; then
        echo "[i] Creating user: $MYSQL_USER with password $MYSQL_PASSWORD"
        echo "GRANT ALL PRIVILEGES ON *.* to '$MYSQL_USER'@'localhost' IDENTIFIED BY '$MYSQL_PASSWORD';" >> $tfile
      fi

      echo "FLUSH PRIVILEGES;" >> $tfile
    fi

    /usr/bin/mysqld --user=root --bootstrap --verbose=0 < $tfile
    rm -f $tfile
  fi

  # Returns true once mysql can connect.
  mysql_ready() {
      mysqladmin ping --host=127.0.0.1 > /dev/null 2>&1
  }

  # turn on bash's job control
  set -m

  # Start the primary process and put it in the background
  /usr/bin/mysqld --defaults-file=/etc/mysql/my.cnf &

  while !(mysql_ready)
  do
     sleep 1
     echo "waiting for mysql ..."
  done
else
  echo "[i] Using an external database connection [$DB_CONNECTION]. Skip local MySQL starting..."
fi

echo "[i] Starting Buggregator server..."
./rr serve

fg %1
