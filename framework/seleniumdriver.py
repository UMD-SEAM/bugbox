
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

    def __init__(self, visible=False, javascript=True, user_agent=None):
        self.display = None
        if not visible:
            self.display = Display(visible=0, size=(800, 600))
            self.display.start()

        webdriver.Firefox.__init__(self)
        fp = webdriver.FirefoxProfile()

        # disable javascript
        if javascript:
            fp.set_preference("javascript.enabled", True)

        # set user-agent string
        if user_agent:
            fp.set_preference("general.useragent.override", user_agent)

        webdriver.Firefox.__init__(self, firefox_profile=fp)

    def get_element(self, by_xpath=None, by_class=None, by_id=None, by_link_text=None, attempts=4, delay=4):
        # alternatively I could use selenium.webdriver.support.ui import WebDriverWait

        query = ""

        if not (by_xpath or by_id or by_link_text or by_class):
            logger.error("get_element missing parameter")
            return

        for i in range(0, attempts):

            try:
                if by_xpath:
                    query = by_xpath
                    elem = self.find_element_by_xpath(by_xpath)
                    logger.info("Found %s element", by_xpath)
                    return elem

                elif by_class:
                    query = by_class
                    elem = self.find_element_by_class_name(by_class)
                    logger.info("Found %s element", by_class)
                    return elem

                elif by_id:
                    query = by_id
                    elem = self.find_element_by_id(by_id)
                    logger.info("Found %s element", by_id)
                    return elem

                elif by_link_text:
                    query = by_link_text
                    elem = self.find_element_by_link_text(by_link_text)
                    logger.info("Found %s element", by_link_text)
                    return elem

                break

            except selenium.common.exceptions.NoSuchElementException:
                logger.warning("Element not found... try again (%s)", attempts-i)
                time.sleep(delay)

        raise selenium.common.exceptions.NoSuchElementException("Element not found: %s" % (query,))

    def get_alert(self, attempts=4, delay=4):
        for i in range(0, attempts):
            alert = self.switch_to_alert()
            try:
                text = alert.text
                return alert
            except selenium.common.exceptions.NoAlertPresentException:
                logger.warning("Alert not found... try again (%s)", attempts-i)
                time.sleep(delay)

        raise selenium.common.exceptions.NoAlertPresentException

    def cleanup(self):
        self.quit()
        # self.close()
        if self.display:
            self.display.stop()
        return
