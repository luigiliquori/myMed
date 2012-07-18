#!/usr/bin/python
import sys
import os.path
import re

# Find all files
def find(directory):
    filenames = []
    for root, dirs, files in os.walk(directory):
        for basename in files:
            filename = os.path.join(root, basename)
            filenames.append(filename)
    return filenames

def uncamel_case(name):
    s1 = re.sub('(.)([A-Z][a-z]+)', r'\1_\2', name)
    return re.sub('([a-z0-9])([A-Z])', r'\1_\2', s1).lower()

# Global list of templates
gTemplates = []

class EdgePattern :
    def __init__(self, pattern, nodeType, edgeType) :
        self.pattern = re.compile(pattern)
        self.nodeType = nodeType
        self.edgeType = edgeType

class Template :
    def __init__(self, name, file_pattern) :
        self.name = name
        self.file_pattern = re.compile(file_pattern)
        self.edge_patterns = []

        # Register itself
        gTemplates.append(self)

    def addEdge(self, p):
        self.edge_patterns.append(p)


# Global list of nodes
gNodes = {}

class Node:
    def __init__(self, template, filename, id) :

        self.id = id
        self.template = template
        self.filename = filename
        self.edges = []
        
        print "New node: %s:%s, %s" % (template.name, id, filename)
        
        # Register itself
        gNodes["%s:%s" % (template.name, id)] = self

    def parseEdges(self) :
        
        # Open file
        f=open(self.filename)

        # Loop on lines
        line_no = 0
        for line in f:
            line_no += 1

            # Loop on edge patterns
            for ep in self.template.edge_patterns :
                m = ep.pattern.search(line)

                # Match ?
                if (m) :
                    id = uncamel_case(m.group(1))
                    print "New Edge: %s:%s -> %s:%s [%s] (%s:%d)" % (
                        self.template.name,
                        self.id, 
                        ep.nodeType,
                        id, 
                        ep.edgeType,
                        self.filename, 
                        line_no)


        f.close()


# Define templates
contrTpl = Template("Controller", ".*/controllers/.*?([\w]+)Controller.class.php")
contrTpl.addEdge(EdgePattern('renderView\([\'"](.*)[\'"]\)', 'View', 'render')),
contrTpl.addEdge(EdgePattern('redirectTo\([\'"](.*)[\'"]\)', 'Controller', 'redirect')),

viewTpl = Template("View", ".*/views/.*?([\w]+)View.php")
viewTpl.addEdge(EdgePattern('action=(\w+)', 'Controller', 'link')),
viewTpl.addEdge(EdgePattern('url\("(\w+)"', 'Controller', 'url_link')),
viewTpl.addEdge(EdgePattern('url\("(\w+)"', 'Controller', 'url_link')),

# Loop on all files to find node
for filename in find('.') :
    
    # Loop on patterns
    for template in gTemplates :
        match = template.file_pattern.match(filename)
        if match :
            id = uncamel_case(match.group(1))
            Node(template, filename, id)
            # One pattern matched => skip the others
            break


# Loop into nodes files to find edges
for node in gNodes.values() :
    node.parseEdges()


