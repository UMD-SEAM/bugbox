#!/usr/bin/python
import unittest
from tests import exploit_verification

def main():
    loader = unittest.TestLoader()
    tests = loader.loadTestsFromModule(exploit_verification)
    print(tests)
    testRunner = unittest.runner.TextTestRunner()
    testRunner.run(tests)
    print('yay')
    
if __name__ == "__main__":
    main()
