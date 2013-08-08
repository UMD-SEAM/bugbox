#!/bin/bash
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

CHROOT_JAILS=(Debian5 Debian7)
FOLDERS=(apache2 php5)
RESOURCES_ROOT=./resources

for JAIL  in ${CHROOT_JAILS[@]}
do
    if [ -e $JAIL.patch ]; then
	rm $JAIL.patch
    fi
	
    for FOLDER in ${FOLDERS[@]}
    do
	#diff -Nrua $JAIL/etc/$FOLDER ~/vulncorpus/framework/chroot_envs/$JAIL/etc/$FOLDER >> $JAIL.patch
	diff -rua --unidirectional-new-file $JAIL/etc/$FOLDER ~/vulncorpus/framework/chroot_envs/$JAIL/etc/$FOLDER >> $RESOURCES_ROOT/$JAIL.patch
    done
    
done
