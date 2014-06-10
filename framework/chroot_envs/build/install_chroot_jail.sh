#!/bin/bash
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

CHROOT_ROOT_DEFAULT=/usr/lib/bugbox/framework/chroot_envs

if [ $# -eq 2 ]
then
    CHROOT_ROOT=$2
else
    CHROOT_ROOT=CHROOT_ROOT_DEFAULT
fi

INSTALL_ROOT=$CHROOT_ROOT/build
RESOURCES_ROOT=$INSTALL_ROOT/resources
PYTHON_DEPS=$RESOURCES_ROOT/pythondeps.pip

case "$1" in
    Debian5)
	TARGET_DIR=$INSTALL_ROOT/Debian5
	PACKAGES_FILE=$RESOURCES_ROOT/debian5_packages.txt
	PATCH_FILE=$RESOURCES_ROOT/Debian5.patch
	DISTRIBUTION=lenny
	MIRROR=http://archive.debian.org/debian
    ;;
    Debian7)
	TARGET_DIR=$INSTALL_ROOT/Debian7
	PACKAGES_FILE=$RESOURCES_ROOT/debian7_packages.txt
	PATCH_FILE=$RESOURCES_ROOT/Debian7.patch
	DISTRIBUTION=wheezy
	MIRROR=http://debian.lcs.mit.edu/debian/
    ;;
    *)
	echo "unknown debian target \`$1'" >&2
	exit 1
    ;;
esac

echo "Setting up host system"

echo "Installing python dependencies"
pip install --exists-action=i -r $PYTHON_DEPS
ret=$?
if [[ $ret != 0 ]] ; then
    echo "failed to install required python modules"
    exit $ret
fi


echo "Setting up chroot jails"

if [ ! -e $PACKAGES_FILE ] ; then
    echo "Package list $PACKAGES_FILE does not exist"
    exit 1
fi

echo "Creating $TARGET_DIR directory"
mkdir $TARGET_DIR
echo "Building $DISTRIBUTION chroot jail"
debootstrap $DISTRIBUTION $TARGET_DIR $MIRROR
ret=$?
if [[ $ret != 0 ]] ; then
    echo "debootstrap failed to build chroot $DISTRIBUTION"
    exit $ret
fi    

# prevent services from starting upon installation
echo "#!/bin/sh" > $TARGET_DIR/usr/sbin/policy-rc.d
echo "exit 101" >> $TARGET_DIR/usr/sbin/policy-rc.d
chmod +x $TARGET_DIR/usr/sbin/policy-rc.d

echo "Mounting /dev, /dev/pts, /proc"
mount --bind /dev $TARGET_DIR/dev
ret1=$?
mount --bind /dev/pts $TARGET_DIR/dev/pts
ret2=$?
mount --bind /proc $TARGET_DIR/proc
ret3=$?
if [[ ($ret1 != 0) || ($ret2 != 0) || ($ret3 != 0) ]] ; then
    echo "failed to mount!"
    umount $TARGET_DIR/proc
    umount $TARGET_DIR/dev/pts
    umount $TARGET_DIR/dev
    exit $ret1|$ret2|$ret3
fi

echo "Running apt-get update"
chroot $TARGET_DIR apt-get update
ret=$?
if [[ $ret != 0 ]] ; then
    echo "apt-get failed to update"
    exit $ret
fi


echo "Installing required packages"
chroot $TARGET_DIR apt-get -y install dselect
#chroot $TARGET_DIR dselect access
chroot $TARGET_DIR dselect update
#chroot $TARGET_DIR dpkg --clear-selections
chroot $TARGET_DIR apt-get -y upgrade
chroot $TARGET_DIR dpkg --set-selections < $PACKAGES_FILE
chroot $TARGET_DIR apt-get -u -y dselect-upgrade


ret=$?
if [[ $ret != 0 ]] ; then
    echo "apt-get failed to install packages"
    exit $ret
fi

echo "Setting up traces folder"

chroot $TARGET_DIR mkdir -p /tmp/traces
chroot $TARGET_DIR chown www-data /tmp/traces
chroot $TARGET_DIR chgrp www-data /tmp/traces

rm $TARGET_DIR/usr/sbin/policy-rc.d 


if [ -e $PATCH_FILE ]
then
    echo "Applying patch file $PATCH_FILE"
    (cd $TARGET_DIR && patch -f -p1 < $PATCH_FILE)
fi


echo "Configuring xdebug"


case "$1" in
    Debian5)
	
	echo "Doing Debian 5 specific xdebug configuration"
	echo "Setting xdebug.ini up symlinks"
	mkdir $TARGET_DIR/etc/php5/mods-available
	mv $TARGET_DIR/etc/php5/apache2/conf.d/xdebug.ini $TARGET_DIR/etc/php5/mods-available/xdebug.ini
	rm $TARGET_DIR/etc/php5/conf.d/xdebug.ini
	chroot $TARGET_DIR ln -s /etc/php5/mods-available/xdebug.ini /etc/php5/conf.d/xdebug.ini
	chroot $TARGET_DIR ln -s /etc/php5/mods-available/xdebug.ini /etc/php5/apache2/conf.d/xdebug.ini

	;;

    Debian7)

	echo "Doing Debian 7 specific xdebug configuration"
	#extension_dir=`chroot $TARGET_DIR find /usr/lib/php5 -name 'xdebug.so'`
	#extension_dir=${extension_dir//\//\\\/} # escape for sed
	#sed_expr="s/zend_extension=\/usr\/lib\/php5\/.\+\/xdebug.so/zend_extension=$extension_dir/g"
	#echo "Before change"
	#cat $TARGET_DIR/etc/php5/mods-available/xdebug.ini
	#chroot $TARGET_DIR cat /etc/php5/mods-available/xdebug.ini | sed $sed_expr > $TARGET_DIR/etc/php5/mods-available/xdebug.ini
	#echo "After change"
	#cat $TARGET_DIR/etc/php5/mods-available/xdebug.ini
	echo -e "xdebug.auto_trace=0\nxdebug.trace_output_dir=/tmp/traces\nxdebug.trace_output_name = trace.%c.%p\n" >> $TARGET_DIR/etc/php5/mods-available/xdebug.ini

	;;
    *)
	exit 1
	;;
esac


echo "Unmounting /dev, /dev/pts, /proc"
umount $TARGET_DIR/proc
umount $TARGET_DIR/dev/pts
umount $TARGET_DIR/dev

echo "Copying chroot jail to $CHROOT_ROOT" 
mv $TARGET_DIR $CHROOT_ROOT

exit 0
