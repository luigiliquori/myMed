README.txt

HOW TO USE IT FOR MYMED:

./build/classes$ javadoc -private -doclet com.jeldoclet.JELDoclet -docletpath . /local/mymed/backend/src/com/mymed/model/data/user/*.java -d /local/mymed/backend/doc/xml/

Author: Jack D. Herrington (jack_herrington@sourceforge.net)
	Toby Patke	   (Toby_patke _?_ hotmail.com)
Last Modified: 15/09/05

For information on the purpose of JELDoclet go to the JELDoclet home page
at: http://jeldoclet.sourceforge.net

For Windows Users:

Use the 'run.bat' batch file to run JELDoclet on a set of Java files.  The
output will go into 'jel.xml'.  Feel free to copy and alter the run.bat
file to suit your needs.

To change the output to produce multiple files instead of a single file, use
the -multiple switch before the -doclet switch.  To produce xml which conforms 
to the schema provided, use the -includeNamespace switch.

For Unix or Mac OS X users:

Check out the 'run.bat' file for the command line invocation options.  It
should be a simple matter of converting the batch file to a shell command
to create a Unix version of 'run.bat'.

------------

ant usage: see build.xml, target "test"

------------

cmdline parameters:

-multiple

if set, each class is saved into a separate file (packagename.classname".xml")

-includeNamespace

if set, a standalone xml document with reference to JelSchema.xsd is created

-d <directory>

the output file(s) are saved to this directory; default: "./"
if not part of the directory name, the system-dependent, appropriate path separator is appended

note: relative directories in connection with ant usage result in xml files created in subdirectories
relative to the usual javadoc directory structure

-outputEncoding <encoding>

the xml file starts with "<?xml version="1.0" encoding="(xxx)" ?>"
(xxx) is the encoding provided ("ISO-8859-1" is a good idea for windows environments)
default: "UTF-8"

-filename <name>

if provided, the "name" is used
-- as filename for single file output (-multiple not set)
-- as filename base for multiple files (-multiple set): the name is prepended to "packagename.classname.xml";
do not use this to create paths !!!
