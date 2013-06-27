#!/usr/bin/python

import sys
import bblogger
import logging
import readline

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
           "\texploit\t\t<exploit_name <display_on | display_off>>\n"   \
           "\tstop\t\t<exploit_name>\n"                                 \
           "\ttrace_on\t<exploit_name>\n"                               \
           "\ttrace_off\t<exploit_name>\n"                              \
           "\tautorun\t\t<exploit_name>"

if __name__ == "__main__":
    
    if len(sys.argv) < 3:
        print usage() % (sys.argv[0],)
        exit(-1)

        
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
            logger.warn("feature \"running\" not yet implemented")
            pass

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

        Exploit = Query().get_by_name(sys.argv[2])
        
        if not Exploit:
            logger.error("exploit \"%s\" not found", sys.argv[2])
            exit(-1)


        if sys.argv[1] == "start":
            logger.info("Starting exploit instance (%s)", Exploit.attributes['Name'])
            engine = Engine(Exploit(), config)        
            print "Description:\n", Exploit.attributes['Description']
            engine.startup()
            exit()

        elif sys.argv[1] == "exploit":
            
            visible = False
            if len(sys.argv) > 3:
                if sys.argv[3] == "display_on":
                    visible = True
                           
            engine = Engine(Exploit(visible), config)        
            logger.info("Running exploit %s", sys.argv[2])
            engine.exploit.exploit()
            exit()

        elif sys.argv[1] == "stop":
            logger.info("Stopping exploit instance (%s)", Exploit.attributes['Name'])
            engine = Engine(Exploit(), config)        
            engine.shutdown()
            exit()

        elif sys.argv[1] == "trace_on":
            logger.info("Trace on for exploit %s", sys.argv[2])
            engine = Engine(Exploit(), config)        
            engine.xdebug_autotrace_on()
            exit()

        elif sys.argv[1] == "trace_off":
            logger.info("Trace off for exploit %s", sys.argv[2])
            engine = Engine(Exploit(), config)        
            engine.xdebug_autotrace_off()
            exit()

            
        elif sys.argv[1] == "autorun":
            logger.info("Autorun exploit %s", sys.argv[2])
            engine = Engine(Exploit(), config)        
            engine.startup()
            engine.xdebug_autotrace_on()
            engine.exploit.exploit()
            engine.xdebug_autotrace_off()
            engine.shutdown()
            exit()

    print usage() % (sys.argv[0],) 
    
        
