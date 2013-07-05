
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.

import time
from selenium import webdriver
import selenium.common.exceptions
from pyvirtualdisplay import Display
import logging

logger = logging.getLogger("SeleniumDriver")

class SeleniumDriver (webdriver.Firefox):
    """This is a class that encapsulates the selenium webdriver, adding some useful functionality."""

    def __init__(self, visible=False, javascript=True):
        self.display = None
        if not visible:
            self.display = Display(visible=0, size=(800, 600))
            self.display.start()

        if javascript:
            webdriver.Firefox.__init__(self)
        else:
            fp = webdriver.FirefoxProfile()
            fp.set_preference("javascript.enabled", False)
            webdriver.Firefox.__init__(self, firefox_profile=fp)
        return

    def get_element(self, by_xpath=None, by_class=None, by_id=None, by_link_text=None, attempts=4, delay=4):
        #alternatively I could use selenium.webdriver.support.ui import WebDriverWait

        if not (by_xpath or by_id or by_link_text or by_class):
            logger.error("get_element missing parameter")
            return

        for i in range(0, attempts):

            try:
                if by_xpath:
                    elem = self.find_element_by_xpath(by_xpath)
                    logger.info("Found %s element", by_xpath)
                    return elem

                elif by_class:
                    elem = self.find_element_by_class_name(by_class)
                    logger.info("Found %s element", by_class)
                    return elem

                elif by_id:
                    elem = self.find_element_by_id(by_id)
                    logger.info("Found %s element", by_id)
                    return elem
                
                elif by_link_text:
                    elem = self.find_element_by_link_text(by_link_text)
                    logger.info("Found %s element", by_link_text)
                    return elem

                break
            
            except selenium.common.exceptions.NoSuchElementException:
                logger.warning("Element not found... try again (%s)", attempts-i)
                time.sleep(delay)

    
        raise selenium.common.exceptions.NoSuchElementException

    def cleanup(self):
        self.quit()
        #self.close()
        if self.display:
            self.display.stop()
        return


