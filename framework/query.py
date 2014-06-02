
# Copyright 2013 University of Maryland.  All rights reserved.
# Use of this source code is governed by a BSD-style
# license that can be found in the LICENSE.TXT file.


import pkgutil
import re
import logging

logger = logging.getLogger("Query")

try:
    import framework.Exploits

    # print "Exploits available", Exploits.__all__

    class Query:

        def __init__(self):
            self.exploits = []

            for module_loader, name, ispkg in pkgutil.iter_modules(['framework/Exploits']):
                if not ispkg:
                    try:
                        self.exploits += [module_loader.find_module(name).load_module(name).Exploit]
                    except ImportError as e:
                        logging.error("failed to import exploit module \"%s\"\n%s", name, e)
                        exit(-1)

            return

        def get_by_re(self, attr, re_str):
            rec = re.compile(re_str)
            expl_list = []

            for expl in self.exploits:
                if attr in expl.attributes:
                    if rec.match(expl.attributes[attr]):
                        expl_list += [expl]

            return expl_list

        def get_by_type(self, tname):
            expl_list = []
            for expl in self.exploits:
                if 'Type' in expl.attributes:
                    if expl.attributes['Type'] == tname:
                        expl_list += [expl]

            return expl_list

        def get_by_name(self, name):
            for expl in self.exploits:
                if 'Name' in expl.attributes:
                    if expl.attributes['Name'] == name:
                        return expl

            return None

except ImportError:
    logging.error("not importing exploits")

    class Query:
        def __init__(self):
            raise NotImplementedError("Cannot import exploit modules, Query not possible")
