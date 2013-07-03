#!/usr/bin/python
import unittest,os
from tests import ExploitVerification
from framework import Query
import bblogger

import logging 

logging = logging.getLogger("testbb")

def main():

    suite = unittest.TestSuite()
    loader = unittest.TestLoader()
    
    for exploit in Query().exploits:        
        while len(os.listdir("live_systems/"))!=0:
            sleep(1)
        suite.addTest(ExploitVerification(exploit))
                
    testRunner = unittest.runner.TextTestRunner()
    testRunner.run(suite)
    
if __name__ == "__main__":
    main()
