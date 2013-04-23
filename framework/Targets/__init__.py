
import pkgutil
import os

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



def get_target_module(target_name):
    #print "searching for modules in cwd:", __path__
    for (module_loader, name, ispkg) in pkgutil.walk_packages(__path__):
        if ispkg:
            try:
                module = module_loader.find_module(name).load_module(name)
                if (module.name == target_name):
                    return module
            except ImportError:
                print "Error: failed to import module ", name
                exit()

    raise TargetModuleNotFound(target_name)


def get_plugin(target_app, plugin_name):
    try:
        for name, src, dest in target_app.plugins:
            if name == plugin_name:
                return (src, dest)
            raise TargetPluginNotFound(plugin_name)
    except AttributeError:
        raise TargetPluginNotFound(plugin_name)

