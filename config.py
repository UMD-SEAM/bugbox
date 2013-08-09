# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

# Some framework configurations
import os
bugbox_root = os.path.dirname(os.path.realpath(__file__))

# The location where the chroot environments are stored
chroot_dirs = bugbox_root + "/framework/chroot_envs"

# Where the environments will be mounted when running a target service
live_systems_dir = bugbox_root + "/live_systems"

# The location of any xdebug configuration files
xdebug_dir =  bugbox_root + "/framework/xdebug"

# The desired location of xdebug traces
traces_dir = bugbox_root + "/traces"
