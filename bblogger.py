import logging



class ColoredFormatter(logging.Formatter):

    def __init__(self, msg):
        logging.Formatter.__init__(self, msg)
        
        self.COLORS = {
            'WARNING' :  '\033[33m',
            'INFO' :     '\033[34m',
            'DEBUG' :    '\033[34m',
            'CRITICAL' : '\033[33m',
            'ERROR' :    '\033[91m',
            'OK' :       '\033[92m',
            'GRAY' :     '\033[90m',
            'ULINE' :    '\033[4m',
            'ENDC' :     '\033[0m'
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
logging.addLevelName(OKLVL,'OK')
logger = logging.getLogger('') # root level logger
logger.setLevel(logging.INFO)
formatter = ColoredFormatter("[%(levelname)s] <%(name)s> %(message)s")
ch = logging.StreamHandler()
ch.setFormatter(formatter)
logger.addHandler(ch)
ch.setLevel(logging.INFO)
