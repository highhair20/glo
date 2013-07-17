#!/bin/sh


################################################################################
## Copyright (c) 2011, Glo Framework
## All rights reserved.
## Licensed under the BSD License.
## http://gloframework.com/license
################################################################################



#
# NOTE: 
# This script is written with the assumption that the user has done the 
# following:
#
# 1. Downloaded one of the following from http://gloframework.com/downloads
#       gloframework-0.0.1.zip
#       gloframework-0.0.1-tar.gz
#
# 2. Extracted the compressed file into your desired directory.  
#    For this example let's say you chose to extract it into /home/jason/gloframework
#       unzip gloframework-0.0.1.zip
#
# 3. Changed directory into the directory you would like your project to be built in
#       cd /home/jason/
#
# 4. Run this build tool
#       bash /home/jason/gloframework/bin/glo.sh create project quickstart



#
# 
PROJECT_NAME=gloframework
ZEND_VERSION=1.11.10




# todo - give creds to zends cli tool

#
#
#
## find php: pear first, command -v second, straight up php lastly
if test "@php_bin@" != '@'php_bin'@'; then
    PHP_BIN="@php_bin@"
elif command -v php 1>/dev/null 2>/dev/null; then
    PHP_BIN=`command -v php`
else
    PHP_BIN=php
fi

# find glo.php: pear first, same directory 2nd, 
if test "@php_dir@" != '@'php_dir'@'; then
    PHP_DIR="@php_dir@"
else
    SELF_LINK="$0"
    SELF_LINK_TMP="$(readlink "$SELF_LINK")"
    while test -n "$SELF_LINK_TMP"; do
        SELF_LINK="$SELF_LINK_TMP"
        SELF_LINK_TMP="$(readlink "$SELF_LINK")"
    done
    PHP_DIR="$(dirname "$SELF_LINK")"
fi

"$PHP_BIN" -d safe_mode=Off -f "$PHP_DIR/glo.php" -- "$@"
