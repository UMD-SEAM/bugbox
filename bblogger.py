
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

import logging
import sys


class ColoredFormatter(logging.Formatter):

    def __init__(self, msg):
        logging.Formatter.__init__(self, msg)

        if (hasattr(sys.stdout, 'isatty') and sys.stdout.isatty()):
            self.COLORS = {
                'WARNING':  '\033[33m',
                'INFO':     '\033[34m',
                'DEBUG':    '\033[34m',
                'CRITICAL': '\033[33m',
                'ERROR':    '\033[91m',
                'OK':       '\033[92m',
                'GRAY':     '\033[90m',
                'ULINE':    '\033[4m',
                'ENDC':     '\033[0m'
                }
        else:
            self.COLORS = {
                'WARNING':  '',
                'INFO':     '',
                'DEBUG':    '',
                'CRITICAL': '',
                'ERROR':    '',
                'OK':       '',
                'GRAY':     '',
                'ULINE':    '',
                'ENDC':     ''
                }

        return

    def format(self, record):
        levelname = record.levelname
        if levelname in self.COLORS:
            levelname_color = "%s%s%s" % (self.COLORS[levelname],
                                          levelname.lower(),
                                          self.COLORS['ENDC'])
            record.levelname = levelname_color
        return logging.Formatter.format(self, record)


# setup logging
OKLVL = 22
logging.addLevelName(OKLVL, 'OK')
logger = logging.getLogger('')  # root level logger
logger.setLevel(logging.INFO)
formatter = ColoredFormatter("[%(levelname)s] <%(name)s> %(message)s")
ch = logging.StreamHandler()
ch.setFormatter(formatter)
logger.addHandler(ch)
ch.setLevel(logging.INFO)
