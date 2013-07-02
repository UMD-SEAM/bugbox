#!/usr/bin/python
import unittest
from tests import ExploitVerification;
import bblogger

import logging 

logging = logging.getLogger("testbb")

def main():

    suite = unittest.TestSuite()
    loader = unittest.TestLoader()
    
    for exploit in Query().exploits:
        # logger.info("starting exploit %s", Exploit.attributes['Name'])
        suite.addTest(ExploitVerification(exploit))
        
    testRunner = unittest.runner.TextTestRunner()
    testRunner.run(suite)
    
if __name__ == "__main__":
    main()
