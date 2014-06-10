#!/bin/bash

# This script does what the debian `postinst` does, but without having the debian package.
# Despite that, it still does some Debian-specific things...

if (( $EUID != 0 )); then
    echo "Please run as root or using sudo!"
    exit
fi

CHROOT_JAILS=(Debian5 Debian7)
BUGBOX_ROOT=$( cd "$( dirname "$0" )" && pwd )
RESOURCES=$BUGBOX_ROOT/framework/chroot_envs/build/resources
CHROOT_ROOT=$BUGBOX_ROOT/framework/chroot_envs

for JAIL in ${CHROOT_JAILS[@]}
do
    $CHROOT_ROOT/build/install_chroot_jail.sh $JAIL "$CHROOT_ROOT"
done

if [ -e /etc/bash_completion.d ] ; then
    echo "Installing bash completion file"
    ln -s $RESOURCES/bbcompletion /etc/bash_completion.d
fi

echo "Installing selenium service"
cp $RESOURCES/selenium /etc/init.d
insserv selenium

if [[ ! -e /var/run/selenium.pid ]] ; then
    /etc/init.d/selenium start
fi

echo "Setting application permissions"
find $BUGBOX_ROOT/framework/Targets -type d \( -path '*/application' -o -path '*/plugins/*/plugin' \) -exec chown -R www-data:www-data {} \;


MSF_DIR=$BUGBOX_ROOT/lib/metasploit-framework
PATCH_FILE=$BUGBOX_ROOT/lib/msf.diff

echo "Cloning Metasploit repository"
mkdir $MSF_DIR
git clone https://github.com/rapid7/metasploit-framework.git $MSF_DIR
(cd $MSF_DIR && git checkout 40e801d345ecf4ddc233b26c6bc2aac6741556d1)
(cd $MSF_DIR && patch -f -R -p1 < $PATCH_FILE)

echo "Configuring MYSQL"
RAND_PASSWORD=`date +%s | sha256sum | base64 | head -c 16`
echo $RAND_PASSWORD > .mysqlpass
echo -e "CREATE USER 'dbroot'@'localhost' IDENTIFIED BY '$RAND_PASSWORD';\nGRANT ALL PRIVILEGES ON *.* TO 'dbroot'@'localhost' WITH GRANT OPTION;" | mysql --defaults-file=/etc/mysql/debian.cnf
