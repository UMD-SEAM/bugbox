import pkgutil
import re

try:
    import framework.Exploits

    #print "Exploits available", Exploits.__all__
    
    class Query:
        
        def __init__(self):
            
            self.exploits = []

            for module_loader, name, ispkg in pkgutil.iter_modules(['framework/Exploits']):
                if not ispkg:
                    try:
                        self.exploits += [module_loader.find_module(name).load_module(name).Exploit]
                    except ImportError as e:
                        print "Error: failed to import exploit module ", name
                        print "Exception:", e
                        exit()
                        
            return
        
        def get_by_re(self, attr, re):
            
            rec = re.compile(re)
            expl_list = []
            
            for expl in self.exploits:
                if expl.attributes.has_key(attr):
                    if rec.match(expl.attributes[attr]):
                        expl_list += [expl]
                        
            return expl_list

        def get_by_type(self, tname):
            
            expl_list = []
            for expl in self.exploits:
                if expl.attributes.has_key('Type'):
                    if expl.attributes['Type'] == tname:
                        expl_list += [expl]
                    
            return expl_list

        def get_by_name(self, name):
            
            for expl in self.exploits:
                if expl.attributes.has_key('Name'):
                    if expl.attributes['Name'] == name:
                        return expl
                    
            return None

except ImportError:
    print "not importing Exploits"
    class Query:
        def __init__(self):
            raise NotImplementedError("Cannot import exploit modules, Query not possible")

