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



#
# Zend related build tool steps
#
# run Zend_Tool to create the server side of the app
bash /home/jason/gloframework/vendor/ZendFramework-$ZEND_VERSION-minimal/bin/zf.bash create project $PROJECT_NAME`



#
# YUI related build tool steps
run YUI_Tool to create the client side of the app



#
# Glo related file structure additions
#
# Create production apache config file
cp gloframework/src/etc/public.conf $PROJECT_NAME/etc/public.conf

# Remove the Zend generated README.txt file from the docs directory
# This file only contains an example of how to configure apache.
# Let's just create the actual file to use.  How about that.
rm $PROJECT_NAME/docs/README.txt

# add all vendor code to the new project
cp -r gloframework/src/vendor $PROJECT_NAME/vendor

# make updates to $PROJECT_NAME/public/index.php
# - add $PROJECT_NAME/vendor to the include path
# - add $PROJECT_NAME/vendor/ZendFramework-1.11.10-minimal/library to the include path







#
#
#
## find php: pear first, command -v second, straight up php lastly
#if test "@php_bin@" != '@'php_bin'@'; then
#    PHP_BIN="@php_bin@"
#elif command -v php 1>/dev/null 2>/dev/null; then
#    PHP_BIN=`command -v php`
#else
#    PHP_BIN=php
#fi

# find zf.php: pear first, same directory 2nd, 
#if test "@php_dir@" != '@'php_dir'@'; then
#    PHP_DIR="@php_dir@"
#else
#    SELF_LINK="$0"
#    SELF_LINK_TMP="$(readlink "$SELF_LINK")"
#    while test -n "$SELF_LINK_TMP"; do
#        SELF_LINK="$SELF_LINK_TMP"
#        SELF_LINK_TMP="$(readlink "$SELF_LINK")"
#    done
#    PHP_DIR="$(dirname "$SELF_LINK")"
#fi

#"$PHP_BIN" -d safe_mode=Off -f "$PHP_DIR/zf.php" -- "$@"