#!/usr/bin/python
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

import sys
import bblogger
import logging
import readline
import os

logger = logging.getLogger("bbmanage")

ULINE = '\033[4m'
ENDC = '\033[0m'

def usage():
    
    return "Usage: %s [command] <options>\n"                            \
           "\t" + ULINE  + "Commands" + ENDC + ":"                      \
           "\t" + ULINE + "Options" + ENDC + ":\n"                      \
           "\tlist\t\t<exploits | targets | types | running>\n"         \
           "\tinfo\t\t<exploit_name>\n"                                 \
           "\tstart\t\t<exploit_name>\n"                                \
           "\texploit\t\t--display --noverify <exploit_name>\n"         \
           "\tstop\n"                                                   \
           "\ttrace_on\n"                                               \
           "\ttrace_off\n"                                              \
           "\tautorun\t\t<exploit_name>"

def create_lockfile(exploit_name):
    
    try:
        open('.lock')
        return False
    except IOError:
        #lockfile does not yet exist, create it
        fd = file('.lock','w')
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
        #lockfile does not yet exist, create it

    return None
    
    

if __name__ == "__main__":
    
    if len(sys.argv) < 2:
        print usage() % (sys.argv[0],)
        exit(-1)

    if len(sys.argv) == 2:

        from framework import Query
        from framework import Engine
        import config

        exploitname = get_running()
        if not exploitname:
            logger.error("Application %s is not currently running", exploitname)
            exit(-1)

        Exploit = Query().get_by_name(exploitname)
        
        if not Exploit:
            logger.error("Unable to find exploit for session %s", exploitname)
            exit(-1)

        if sys.argv[1] == "stop":
            logger.info("Stopping exploit instance (%s)", Exploit.attributes['Name'])
            engine = Engine(Exploit(), config)        
            engine.shutdown()
            remove_lockfile()
            exit()

        elif sys.argv[1] == "trace_on":
            logger.info("Trace on for exploit %s", exploitname)
            engine = Engine(Exploit(), config)        
            engine.xdebug_autotrace_on()
            exit()
            

        elif sys.argv[1] == "trace_off":
            logger.info("Trace off for exploit %s", exploitname)
            engine = Engine(Exploit(), config)        
            engine.xdebug_autotrace_off()
            exit()
            
        
    elif sys.argv[1] == "list":
        if sys.argv[2] == "exploits":
            from framework import Query
            print "%sName%s:\t\t\t%sType%s:\t%sTarget%s:" % (ULINE, ENDC, 
                                                             ULINE, ENDC, 
                                                             ULINE, ENDC)
            for expl in Query().exploits:
                print "%s\t\t%s\t%s" %(expl.attributes['Name'],
                                       expl.attributes['Type'],
                                       expl.attributes['Target'])
            
            exit()
            
        elif sys.argv[2] == "targets":
            from framework import Query
            targets = {}
            print "%sCount%s:\t%sTarget%s:" %(ULINE, ENDC, ULINE, ENDC)
            for expl in Query().exploits:
                t = expl.attributes['Target']
                if targets.has_key(t):
                    targets[t] += 1
                else:
                    targets[t] = 1

            for tar, count in targets.items():
                print "%s\t%s" % (count, tar)

            exit()

        elif sys.argv[2] == "types":
            from framework import Query
            types = {}
            print "%sCount%s:\t%sType%s:" %(ULINE, ENDC, ULINE, ENDC)
            for expl in Query().exploits:
                t = expl.attributes['Type']
                if types.has_key(t):
                    types[t] += 1
                else:
                    types[t] = 1

            for tar, count in types.items():
                print "%s\t%s" % (count, tar)

            exit()


        elif sys.argv[2] == "running":
            running = get_running()
            if running:
                logger.info("Running application %s", running)
            else:
                logger.info("No application currently running")
            exit()

    elif sys.argv[1] == "info":
        from framework import Query
        attr_order = ['Name', 'Description', 'References', 'Target', 'Type', 'VulWikiPage']

        for expl in Query().exploits:
            if expl.attributes['Name'] == sys.argv[2]:
                for attr in attr_order:
                    if expl.attributes.has_key(attr):
                        print "%s%s%s: %s" %(ULINE, attr, ENDC, expl.attributes[attr])
                exit()
            
        logger.error("Error: exploit \"%s\" not found", sys.argv[2])
        exit(-1)

    elif len(sys.argv) > 2:
        # Here are the commands that rely on an instance of the exploit engine

        from framework import Query
        from framework import Engine
        import config

        exploitname = sys.argv[-1]
        Exploit = Query().get_by_name(exploitname)

        if not Exploit:
            logger.error("exploit \"%s\" not found", exploitname)
            exit(-1)


        if sys.argv[1] == "start":
            if create_lockfile(Exploit.attributes['Name']):
                logger.info("Starting exploit instance (%s)", Exploit.attributes['Name'])
                engine = Engine(Exploit(), config)        
                print "Description:\n", Exploit.attributes['Description']
                engine.startup()
                exit()
            else:
                logger.error("An application is already running")
                exit(-1)

        elif sys.argv[1] == "exploit":
            
            visible = False
            noverify = False
            
            if len(sys.argv) == 4:
                visible = sys.argv[2] == "--display"
                noverify = sys.argv[2] == "--noverify"
            elif len(sys.argv) == 5:
                visible = (sys.argv[2] == "--display") or (sys.argv[3] == "--display")
                noverify = (sys.argv[2] == "--noverify") or (sys.argv[3] == "--noverify")
            
            engine = Engine(Exploit(visible), config)        
            logger.info("Running exploit %s", exploitname)
            engine.exploit.exploit()
            
            if not noverify:
                logger.info("Verifying exploit %s", exploitname)
                
                try:
                    if not engine.exploit.verify():
                        logger.error("Verification failed for exploit %s", exploitname)
                except NotImplementedError as e:
                    logger.error("Verification not defined for for exploit %s", exploitname)
                
            exit()

        elif sys.argv[1] == "autorun":
            if create_lockfile(exploitname):
                logger.info("Autorun exploit %s", exploitname)
                engine = Engine(Exploit(), config)        
                engine.startup()
                engine.xdebug_autotrace_on()
                engine.exploit.exploit()
                engine.xdebug_autotrace_off()
                engine.shutdown()
                exit()
            else:
                logger.error("An application is already running")
                exit(-1)

    print usage() % (sys.argv[0],) 
    
        
