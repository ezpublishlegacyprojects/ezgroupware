#!/bin/sh

php UnitTest/runtests.php -c reports/codecoverage -x reports/testlog.xml -r trunk 
