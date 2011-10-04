/**
 * Copyright (c) 2011, Glo Framework
 * All rights reserved.
 * Licensed under the BSD License.
 * http://gloframework.com/license
 */
 
/**
 *
 * @module glo-controller
 */
YUI.add('glo-controller-front', function(Y) {
    Y.namespace('Glo').ControllerFront = Y.Base.create('gloControllerFront', Y.Controller, [],
        {
            // prototype properties to add/override
            initializer : function(config) {
                Y.Glo.ControllerFront.superclass.initializer.apply(this, arguments);
            },
            
            /**
             * Initializes all routes defined in the config file.  Should be 
             * called before save.
             *
             * @method initRoutes
             */
            initRoutes : function() {
                var appNamespace = Y.config.namespace;
                var modules = Y.config.modules;
                for (var module in modules) {
                    if (!inArray(module, Glo.modules)) {
                        var thisModuleName = module;
                        var thisModule = modules[module];
                        this.route(thisModule.pattern, function(req, next) {
                            // @todo - error reporting - 404 - show error explaining that no file was found and what to do to create it
                            Y.use(thisModuleName, function(Y) {
                                var o = new Y[appNamespace][thisModule.controller.camelCase().ucfirst()]['View' + thisModule.action.camelCase().ucfirst()](req);
                            });
                        });                        
                    }
                }
            },
            
            save : function(url) {
                //Y.log(url, 'debug', 'Y.Glo.ControllerFront.save');
                // If the controller has a route that matches this path, then use the
                // controller to update the URL and handle the route. 
                if (!this.hasRoute(url)) {
                    Y.log('Route was NOT found.  Will handle ' + url + ' with a default route', 'debug', 'ControllerFront.save');
                    // custom routes weren't able to handle this request so use 
                    // the default route
                    this._registerDefaultRoute(url);
                } else {
                    Y.log('Route was found.  Will handle ' + url + ' with a custom route', 'debug', 'ControllerFront.save');
                }
                Y.Glo.ControllerFront.superclass.save.apply(this, arguments);
            },
            
            /**
             * Register a route based on the url given and a set of conventions.
             *
             * 
             *
             * @method _defaultRoute
             * @private
             * @param url {String} the url 
             * @return void
             */
            _registerDefaultRoute : function(url) {
                // validate the url
                // @todo - do not allow presence of a domain in the url
                // @todo - url must be absolute...url must start with /
                // @todo - check for urls that don't look like they are from this site. 
                //          and throw an exception.  Look in config for jsBase.  
                //          If jsBase is defined then check it against the first part 
                //          of this url (if it looks like http://...)
                //          If jsBase is not defined then assume the jsBase is at /js.
                
                this.route(url, function(req, next) {
                    // parse the url
                    var urlParts = req.path.split('/');
                    // define the namespace
                    var appNamespace = Y.config.namespace;
                    // define the action controller name
                    var actionController = urlParts[1];
                    if (!actionController) {
                        actionController = 'index';
                    }
                    // define the view name
                    var viewName = urlParts[2];
                    if (!viewName) {
                        viewName = 'index';
                    }
                    viewName = 'view-' + viewName;
                    // build the module name from the namespace, action controller, and view name
                    var module = appNamespace.toLowerCase() + '.' + actionController.toLowerCase() + '.' + viewName.toLowerCase();
                    Y.log('module: ' + module, 'debug', 'ControllerFront._registerDefaultRoute');
                    // build the file name from the namespace, the action controller and the view name
                    var fullPath = '/js/views/' + actionController.toLowerCase() + '/' + viewName.toLowerCase() + '.js';
                    Y.log('fullPath: ' + fullPath);
                    // append to the config
                    var config = {
                        'modules' : {}
                    };
                    config.modules[module] = {
                        fullpath: fullPath,
                        requires: ['glo-view']
                    };
                    Y.applyConfig(config);
                    // @todo - error reporting - 404 - show error explaining that no file was found and what to do to create it
                    Y.use(module, function(Y) {
                        Y.log(Y.config);
                        var o = new Y[appNamespace][actionController.camelCase().ucfirst()][viewName.camelCase().ucfirst()](req);
                    });
                });
            }
        },
        {
            // static methods and properties go here
        }
    );
}, '0.0.1', { requires: ['controller'] });
