
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import bblogger
import config
from framework import Query, Engine
import logging

logger = logging.getLogger("runxss")

for Exploit in Query().get_by_type('XSS'):
    logger.info("Starting exploit %s", Exploit.attributes['Name'])
    engine = Engine(Exploit(), config)
    engine.startup()
    engine.xdebug_autotrace_on()
    engine.exploit.exploit()
    engine.xdebug_autotrace_off()
    engine.shutdown()



