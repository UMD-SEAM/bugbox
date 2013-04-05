#!/bin/bash

target_dir="`pwd`/../sys_parts/chroot_bases"

if [ $# -eq 1 ]; then
    
    if [ $1 = "mount" ]; then
	for file in `find $target_dir -maxdepth 1 -mindepth 1 -type d`; do
    
	    mount -o bind /dev $file/dev
	    mount -o bind /dev/pts $file/dev/pts
	    mount -o bind /proc $file/proc
	
	done


    elif [ $1 = "umount" ]; then
    
	for file in `find $target_dir -maxdepth 1 -mindepth 1 -type d`; do
    
	    umount $file/dev
	    umount $file/dev/pts
	    umount $file/proc
	
	done
    
    fi

else
    
    echo "you either mount or unmount not nothing"

fi


