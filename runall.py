import bblogger
import config
from framework import Query, Engine
import logging

logger = logging.getLogger("runxss")

for Exploit in Query().get_by_re('Type','.*'):
    logger.info("Starting exploit %s", Exploit.attributes['Name'])
    engine = Engine(Exploit(), config)
    engine.startup()
    engine.xdebug_autotrace_on()
    print "******** RUNNING ENGINE ********"
    print engine.exploit, type(engine.exploit)
    engine.exploit.exploit()
    engine.xdebug_autotrace_off()
    engine.shutdown()



