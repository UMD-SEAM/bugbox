
import os
import sys
import Targets
import datetime

OKGREEN = '\033[92m'
FAIL = '\033[91m'
GRAY = '\033[90m'
ENDC = '\033[0m'


class Engine:
    
    def __init__(self, exploit, config):

        self.chroot_dirs = config.chroot_dirs
        self.live_systems_dir = config.live_systems_dir
        self.xdebug_dir = config.xdebug_dir
        self.traces_dir = config.traces_dir

        self.exploit = exploit
        try:
            target_app = Targets.get_target_module(self.exploit.attributes['Target'])
        except Targets.TargetModuleNotFound as e:
            print "[%sError%s]: No module found for target application \"%s\"" % (FAIL, ENDC, e.value,)
            exit() 

        self.chroot_environment = target_app.chroot_environment
        self.exploitname = self.exploit.attributes['Name'].replace(' ', '_').replace('.','_')
        
        #this is to not break existing exploits, a better convention could be had
        self.application_dir = target_app.name.replace(' ', '_').replace('.','_').split('_')[0].lower() 
        self.target_system_dir = "%s/%s" % (self.live_systems_dir, self.exploitname)
        self.application_dir_mapping = target_app.application_dir_mapping

        try:
            self.database_restore_file = target_app.database_filename
            self.database_name = target_app.database_name
        except AttributeError:
            self.database_restore_file = None
            self.database_name = None

        if self.exploit.attributes.has_key('Plugin'):
            try:
                pname = self.exploit.attributes['Plugin']
                self.plugin_src, self.plugin_dest = Targets.get_plugin(target_app, pname)
            except Targets.TargetPluginNotFound as e:
                print "[%sError%s]: Plugin \"%s\" not found for target application \"%s\"" % (FAIL, 
                                                                                              ENDC, 
                                                                                              pname,
                                                                                              e.value)
                exit() 
        else:
            self.plugin_src = None
            
                    
        return

    def startup(self):
        
        start_script = ["[ \"$(ls -A %s)\" ]  && "
                        "echo \"Error: live_systems not empty\" "
                        "&& exit 1 "
                        "|| exit 0"                             %(self.live_systems_dir,),
                        "mkdir %s"                              %(self.target_system_dir,),
                        "mount --bind %s/%s %s"                 %(self.chroot_dirs, 
                                                                  self.chroot_environment, 
                                                                  self.target_system_dir),
                        "mount --bind /dev %s/dev"              %(self.target_system_dir,),
                        "mount --bind /dev/pts %s/dev/pts"      %(self.target_system_dir,),
                        "mount --bind /proc %s/proc"            %(self.target_system_dir,),
                        "mkdir %s%s/%s"                         %(self.target_system_dir,
                                                                  self.application_dir_mapping[1],
                                                                  self.application_dir),
                        "mount --bind %s %s%s/%s"               %(self.application_dir_mapping[0], 
                                                                  self.target_system_dir,
                                                                  self.application_dir_mapping[1],
                                                                  self.application_dir),
                        "chroot %s /etc/init.d/apache2 start"   %(self.target_system_dir,)]

        if self.database_name:
            start_script += ["mysql -u root -pconnection452 %s < %s" %(self.database_name,
                                                                       self.database_restore_file)]

        if self.plugin_src: 
            start_script += ["mkdir %s/%s"            %(self.target_system_dir,
                                                        self.plugin_dest),
                             "mount -o bind %s %s/%s" %(self.plugin_src,
                                                        self.target_system_dir,
                                                        self.plugin_dest)]


        self.execute_commands(start_script)

        return


    def shutdown(self):
        
        if self.is_running():

            self.exploit.cleanup(self.target_system_dir)
            stop_script = ["[ -z \"$(ls -A %s)\" ]  && "
                           "echo \"Error: live_systems is empty\" "
                           "&& exit 1 "
                           "|| exit 0"                          %(self.live_systems_dir,),
                           "chroot %s /etc/init.d/apache2 stop" %(self.target_system_dir,)]
            
            if self.plugin_src: 
                stop_script += ["umount %s/%s"  %(self.target_system_dir,
                                                  self.plugin_dest),
                                "rm -rf %s/%s"  %(self.target_system_dir,
                                                  self.plugin_dest)]

            stop_script += ["umount %s/proc"                     %(self.target_system_dir,),
                            "umount %s/dev/pts"                  %(self.target_system_dir,),
                            "umount %s/dev"                      %(self.target_system_dir,),
                            "umount %s%s/%s"                     %(self.target_system_dir,
                                                                   self.application_dir_mapping[1],
                                                                   self.application_dir),
                            "[ \"$(ls -A %s%s/%s)\" ] "
                            "&& echo \"Directory not empty!\" "
                            "&& exit 1 "
                            "|| rm -rf %s%s/%s"                  %(self.target_system_dir,
                                                                   self.application_dir_mapping[1],
                                                                   self.application_dir,
                                                                   self.target_system_dir,
                                                                   self.application_dir_mapping[1],
                                                                   self.application_dir),
                            "umount %s"                          %(self.target_system_dir,),
                            "[ \"$(ls -A %s)\" ] "
                            "&& echo \"Directory not empty!\" "
                            "&& exit 1 "
                            "|| rm -rf %s"                       %(self.target_system_dir, 
                                                                   self.target_system_dir)]


            self.execute_commands(stop_script)

        else:
            print "[%sError%s]: attempting to shutdown a system that is not running." % (FAIL, ENDC)

        return

    def is_running(self):
        return os.path.isdir(self.target_system_dir)


    def xdebug_autotrace_on(self):
        if self.is_running():
            autotrace_on_script = ["cp %s/xdebug.ini.on %s/etc/php5/mods-available/xdebug.ini" % (self.xdebug_dir, self.target_system_dir),
                                   "chroot %s /etc/init.d/apache2 restart" %(self.target_system_dir,)]
            self.execute_commands(autotrace_on_script)
        else:
            print "[%sError%s]: attempting to turn on autotrace for a system that is not running" % (FAIL, ENDC)

        return


    def xdebug_autotrace_off(self):
        if self.is_running():
            datestr = datetime.datetime.now().strftime('%Y_%m_%d')
            movetodir = "%s/%s_%s" %(self.traces_dir, self.exploitname, datestr)
            autotrace_on_script = ["cp %s/xdebug.ini.off %s/etc/php5/mods-available/xdebug.ini" % (self.xdebug_dir, self.target_system_dir),
                                   "chroot %s /etc/init.d/apache2 restart" % (self.target_system_dir,),
                                   "mkdir -p %s" % (movetodir,),
                                   "mv %s/tmp/traces/* %s" % (self.target_system_dir, movetodir,)]

            self.execute_commands(autotrace_on_script)
        else:
            print "[%sError%s]: attempting to turn off autotrace for a system that is not running" % (FAIL, ENDC)

        return


    def execute_commands(self, cmdlist):
        for cmd in cmdlist:
            print "EXEC: %s%s%s" % (GRAY, cmd, ENDC),
            ret = os.system(cmd)
            sys.stdout.flush()
            if ret == os.EX_OK:
                print "[%sSuccess%s]" % (OKGREEN, ENDC)
            else:
                print "\n[%sError%s] Nonzero exit status" %(FAIL, ENDC), ret
                exit()
           

        return

    def parse_args(self, argv):

        if len(argv) != 2:
            print "Usage: python %s [options]" % (argv[0],)
            print "Options:"
            print "\tstart:\t\tStart exploit instance"
            print "\tstop:\t\tStop exploit instance"
            print "\texploit:\tRun the exploit"
            print "\tcheck:\t\tCheck if the corresponding environment is running"
            print "\txdebug_on:\tTurn on xdebug autotrace"
            print "\txdebug_off:\tTurn off and collect xdebug autotrace"
            exit()


        elif argv[1] == "xdebug_off":
            self.xdebug_autotrace_off()

        elif argv[1] == "check":
            if self.is_running():
                print "System is running (%s)" % (self.target_system_dir,)
            else:
                print "System is not running (%s)" % (self.target_system_dir,)
                
        return
