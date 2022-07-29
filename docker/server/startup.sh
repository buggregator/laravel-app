#!/bin/sh

if [ "$DB_HOST" = "127.0.0.1" ]; then
  if [ ! -d "/run/mysqld" ]; then
    mkdir -p /run/mysqld
  fi

  if [ -d /server/mysql ]; then
    echo "[i] MySQL directory already present, skipping creation"
  else
    echo "[i] MySQL data directory not found, creating initial DBs"

    mysql_install_db --user=root > /dev/null

    if [ "$MYSQL_ROOT_PASSWORD" = "" ]; then
      MYSQL_ROOT_PASSWORD=${DB_PASSWORD}
      echo "[i] MySQL root Password: $MYSQL_ROOT_PASSWORD"
    fi

    MYSQL_DATABASE=${DB_DATABASE:-""}
    MYSQL_USER=${DB_USERNAME:-""}
    MYSQL_PASSWORD=${DB_PASSWORD:-""}

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
        echo "GRANT ALL PRIVILEGES ON *.* to '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';"
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
fi

# Start the helper process
./rr serve

# now we bring the primary process back into the foreground
# and leave it there
fg %1
