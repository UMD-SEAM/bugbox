import pkgutil
import os
import logging

logger = logging.getLogger("Targets")

class TargetModuleNotFound(Exception):

    def __init__(self, value):
        self.value = value
        return
    
    def __str__(self):
        return repr(self.value)

class TargetPluginNotFound(Exception):

    def __init__(self, value):
        self.value = value
        return
    
    def __str__(self):
        return repr(self.value)

def get_target_class(target_name):
    #print "searching for modules in cwd:", __path__
    for (module_loader, name, ispkg) in pkgutil.walk_packages(__path__):
        if ispkg:
            try:
                module = module_loader.find_module(name).load_module(name)
                if (module.Target.name == target_name):
                    return module.Target
            except ImportError:
                logger.error("failed to import module \"%s\"", name)
                exit(-1)

    raise TargetModuleNotFound(target_name)


from framework.Targets.target import Target
from framework.Targets.apachetarget import ApacheTarget

