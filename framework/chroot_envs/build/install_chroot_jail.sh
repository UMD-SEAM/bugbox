#!/bin/bash

INSTALL_ROOT=/usr/lib/bugbox/framework/chroot_envs/build

case "$1" in
    Debian5)
	TARGET_DIR=$INSTALL_ROOT/Debian5
	PACKAGES_FILE=$INSTALL_ROOT/debian5_packages.txt
	PATCH_FILE=$INSTALL_ROOT/Debian5.patch
	DISTRIBUTION=lenny
	MIRROR=http://archive.debian.org/debian
    ;;
    Debian7)
	TARGET_DIR=$INSTALL_ROOT/Debian7
	PACKAGES_FILE=$INSTALL_ROOT/debian7_packages.txt
	PATCH_FILE=$INSTALL_ROOT/Debian7.patch
	DISTRIBUTION=wheezy
	MIRROR=http://debian.lcs.mit.edu/debian/
    ;;
    *)
	echo "unknown debian target \`$1'" >&2
	exit 1
    ;;
esac

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
chroot $TARGET_DIR dpkg --clear-selections
chroot $TARGET_DIR dpkg --set-selections < $PACKAGES_FILE
chroot $TARGET_DIR apt-get -u -y dselect-upgrade


ret=$?
if [[ $ret != 0 ]] ; then
    echo "apt-get failed to install packages"
    exit $ret
fi

rm $TARGET_DIR/usr/sbin/policy-rc.d 

echo "Unmounting /dev, /dev/pts, /proc"
umount $TARGET_DIR/proc
umount $TARGET_DIR/dev/pts
umount $TARGET_DIR/dev

if [ -e $PATCH_FILE ]
then
    echo "Applying patch file $PATCH_FILE"
    (cd $TARGET_DIR && patch -f -p1 < $PATCH_FILE)
fi
