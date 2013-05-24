
import time
from selenium import webdriver
import selenium.common.exceptions
from pyvirtualdisplay import Display
import logging

logger = logging.getLogger("SeleniumDriver")

class SeleniumDriver (webdriver.Firefox):
    """This is a class that encapsulates the selenium webdriver, adding some useful functionality."""

    def __init__(self, visible=False):
        self.display = None
        if not visible:
            self.display = Display(visible=0, size=(800, 600))
            self.display.start()
        webdriver.Firefox.__init__(self)
        return

    def get_element(self, by_xpath=None, by_id=None, by_link_text=None, attempts=4, delay=4):
        #alternatively I could use selenium.webdriver.support.ui import WebDriverWait

        if not (by_xpath or by_id or by_link_text):
            return

        for i in range(0, attempts):

            try:
                if by_xpath:
                    elem = self.find_element_by_xpath(by_xpath)
                    logger.info("Found %s element", by_xpath)
                elif by_id:
                    elem = self.find_element_by_id(by_id)
                    logger.info("Found %s element", by_id)
                elif by_link_text:
                    elem = self.find_element_by_link_text(by_link_text)
                    logger.info("Found %s element", by_link_text)
                break
            except selenium.common.exceptions.NoSuchElementException:
                if i == attempts-1:
                    raise selenium.common.exceptions.NoSuchElementException
                logger.warning("Element not found... try again (%s)", i)
                time.sleep(delay)

        return elem

    def cleanup(self):
        self.quit()
        #self.close()
        if self.display:
            self.display.stop()
        return


