
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import os
import sys
import Targets
import datetime
import logging

if (hasattr(sys.stdout, 'isatty') and sys.stdout.isatty()):
    GRAY = '\033[90m'
    ENDC = '\033[0m'
    ERROR = '\033[91m'
else:
    GRAY = ''
    ENDC = ''
    ERROR = ''

OKLVL = 22
logger = logging.getLogger("Engine")


def create_lockfile(exploit_name):
    # TODO: This is backwards - the exception should be if the
    #       file *does* exist!
    try:
        open('.lock')
        return False
    except IOError:
        # lockfile does not yet exist, create it
        fd = file('.lock', 'w')
        fd.write(exploit_name)
        fd.close()

    return True


def remove_lockfile():
    os.remove('.lock')
    return


def get_running():

    try:
        fd = file('.lock', 'r')
        running = fd.readline().rstrip('\n')
        fd.close()
        return running
    except IOError:
        return None
        # lockfile does not exist - there is appartently nothing running


class Engine:

    class StartUpException(Exception):
        pass

    class ShutDownException(Exception):
        pass

    def __init__(self, exploit, config):
        self.chroot_dirs = config.chroot_dirs
        self.live_systems_dir = config.live_systems_dir
        self.xdebug_dir = config.xdebug_dir
        self.traces_dir = config.traces_dir

        self.exploit = exploit
        try:
            Target = Targets.get_target_class(self.exploit.attributes['Target'])
        except Targets.TargetModuleNotFound as e:
            logger.error("No module found for target application \"%s\"", e.value)
            exit(-1)

        self.target_app = Target()

        self.chroot_environment = self.target_app.chroot_environment
        self.exploitname = self.exploit.attributes['Name'].replace(' ', '_').replace('.', '_')

        # this is to not break existing exploits, a better convention could be had

        self.target_system_dir = "%s/%s" % (self.live_systems_dir, self.exploitname)
        self.application_dir_mapping = self.target_app.application_dir_mapping
        self.application_dir = self.target_app.application_dir

        if 'Plugin' in self.exploit.attributes:
            try:
                pname = self.exploit.attributes['Plugin']
                self.target_app.set_plugin(pname)
            except Targets.TargetPluginNotFound as e:
                logger.error("Plugin \"%s\" not found for target application \"%s\"",
                             pname,
                             self.target_app.name)
                exit(-1)


    def startup(self):
        logger.info("Running application startup for exploit %s", self.exploitname)

        # Check to make sure that the System's Apache server isn't running
        # before we try to start ours.
        # TODO: Architecturally, this isn't the best place - but it fits best for now...
        retvalue = os.system("sudo service apache2 status")
        if retvalue == 0:
            logger.error("There is already a web server running! You need to stop your web server.")
            logger.error("This program will not stop your existing webserver, because that is a Very Bad Idea (TM)")
            raise self.StartUpException("Problem starting application")


        if not get_running():
            create_lockfile(self.exploitname)
            start_script = ["mkdir %s"                              % (self.target_system_dir,),
                            "mount --bind %s/%s %s"                 % (self.chroot_dirs,
                                                                       self.chroot_environment,
                                                                       self.target_system_dir),
                            "mount --bind /dev %s/dev"              % (self.target_system_dir,),
                            "mount --bind /dev/pts %s/dev/pts"      % (self.target_system_dir,),
                            "mount --bind /proc %s/proc"            % (self.target_system_dir,),
                            "mkdir %s%s/%s"                         % (self.target_system_dir,
                                                                       self.application_dir_mapping[1],
                                                                       self.application_dir),
                            # "mount --bind %s %s%s/%s"              % (self.application_dir_mapping[0],
                            "cp -pR %s/* %s%s/%s"                   % (self.application_dir_mapping[0],
                                                                       self.target_system_dir,
                                                                       self.application_dir_mapping[1],
                                                                       self.application_dir)]

            start_script += self.target_app.get_start_service_script(self.target_system_dir)

            self.execute_commands(start_script)
            logger.info("Running exploit setup")
            self.exploit.setup(self.target_system_dir)
        else:

            logger.error("There is already a system running under %s", self.live_systems_dir)
            raise self.StartUpException("Problem starting application")

    def test(self):
        return self.exploit.test()

    def shutdown(self):
        if get_running() == self.exploitname:
            if self.check_chroot_in_use():
                logger.error("Shutdown failed: one or more processes is using a resource in %s", self.target_system_dir)
                exit(-1)

            stop_script = self.target_app.get_stop_service_script(self.target_system_dir)

            stop_script += ["umount %s/proc"                     % (self.target_system_dir,),
                            "umount %s/dev/pts"                  % (self.target_system_dir,),
                            "umount %s/dev"                      % (self.target_system_dir,),
                            "rm -rf %s%s/%s"                     % (self.target_system_dir,
                                                                    self.application_dir_mapping[1],
                                                                    self.application_dir),
                            "umount %s"                          % (self.target_system_dir,),
                            "[ \"$(ls -A %s)\" ] "
                            "&& echo \"Directory not empty!\" "
                            "&& exit 1 "
                            "|| rm -rf %s"                       % (self.target_system_dir,
                                                                    self.target_system_dir),
                            "rm -rf .tmpbuff"]

            self.execute_commands(stop_script)

            remove_lockfile()
        else:
            logger.error("attempting to shutdown a system that is not running.")
            raise self.ShutDownException("Problem during application shutdown")

    def exploit():
        self.exploit.exploit()
        if not self.exploit.verify():
            logger.error("Verify failed: exploit did not succeed")
        return

    def check_chroot_in_use(self):
        checkcmd = "if [ ! -z `lsof -Fcp +D %s | tr '\\n' ' ' | "           \
                   "sed -e 's/p\\([0-9]\\+\\) c\\([^ ]\\+\\)/\\2(\\1) /g' " \
                   "-e 's/apache2.* //g'` ]; then exit 1; fi" % (self.target_system_dir,)

        return not (self.execute_command(checkcmd) == os.EX_OK)

    def xdebug_autotrace_on(self):

        if get_running():
            autotrace_on_script = ["sed -i 's/xdebug\.auto_trace=0/xdebug\.auto_trace=1/' "
                                   "%s/etc/php5/mods-available/xdebug.ini" % (self.target_system_dir),
                                   "chroot %s /etc/init.d/apache2 restart" % (self.target_system_dir,)]
            self.execute_commands(autotrace_on_script)
        else:
            logger.error("attempting to turn on autotrace for a system that is not running")

    def xdebug_autotrace_off(self):
        if get_running():
            datestr = datetime.datetime.now().strftime('%Y_%m_%d')
            movetodir = "%s/%s_%s" % (self.traces_dir, self.exploitname, datestr)
            autotrace_on_script = ["mkdir -p %s" % (movetodir,),
                                   "mv %s/tmp/traces/* %s" % (self.target_system_dir, movetodir,),
                                   "sed -i 's/xdebug\.auto_trace=1/xdebug\.auto_trace=0/' "
                                   "%s/etc/php5/mods-available/xdebug.ini" % (self.target_system_dir),
                                   "chroot %s /etc/init.d/apache2 restart" % (self.target_system_dir,)]

            self.execute_commands(autotrace_on_script)
        else:
            print "[%sError%s]: attempting to turn off autotrace for a system that is not running" % (ERROR, ENDC)

    def execute_command(self, command):
        logger.info("EXEC: %s%s%s", GRAY, command, ENDC)
        return os.system(command)

    def execute_commands(self, cmdlist):

        for cmd in cmdlist:
            ret = self.execute_command(cmd)
            if ret != os.EX_OK:
                logger.error("Nonzero exit status %s", str(ret))
                exit(-1)

        return
