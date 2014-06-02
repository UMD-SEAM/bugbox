#!/usr/bin/python

# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import unittest
import os
from tests import ExploitVerification
from framework import Query
import bblogger

import logging

logging = logging.getLogger("testbb")


def main():

    suite = unittest.TestSuite()
    loader = unittest.TestLoader()

    for exploit in Query().exploits:
        suite.addTest(ExploitVerification(exploit))

    testRunner = unittest.runner.TextTestRunner()
    testRunner.run(suite)

if __name__ == "__main__":
    main()
