
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import bblogger
import config
from framework import Query, Engine
import logging

logger = logging.getLogger("rundeb5")

for Exploit in Query().get_by_re('Type','.*'):
    engine = Engine(Exploit(), config)
    if(engine.target_app.chroot_environment == "Debian5"):
        logger.info("%s %s",
                    engine.target_app.name,  
                    engine.target_app.chroot_environment)
        engine.startup()
        engine.xdebug_autotrace_on()
        engine.exploit.exploit()
        engine.xdebug_autotrace_off()
        engine.shutdown()



