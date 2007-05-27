#!/bin/sh

# deleting
if [[ -e web ]]
then
	rm -fR web/*
else
	mkdir web
fi

# Building the rst files
r2w devweb/rest2web.ini -w
#cp -r devweb/style web
#cp -r devweb/static web

# Building the PhpDocumentorDocs
phpdoc -c trunk/phpdoc.ini

# Run Test and write Code Coverage
#mkdir web/codecoverage
#php trunk/UnitTest/runtests.php -c web/codecoverage -x web/testlog.xml -r trunk 

#Only for the egroupware2.de Server
#cd /home/thkoch/egroupware2/web
#ln -s .. demo

