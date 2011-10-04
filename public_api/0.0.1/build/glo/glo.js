/**
 * Copyright (c) 2011, Glo Framework
 * All rights reserved.
 * Licensed under the BSD License.
 * http://gloframework.com/license
 */
 
/**
 * Glo
 * 
 * Provides client side MVC with intuative routing conventions.
 * Loads YUI, sets up default click behavior, sets up default routing,
 * loads custom routes, rolls up all required javascript requests and 
 * loads javascript, loads all required classes, initializes the 
 * controller and dispatches.
 *
 * @module glo
 * @main glo
 * @version 0.0.1
 */

/**
 * Provides client side MVC archetecture with intuative routing conventions.
 * Loads YUI, sets up default click behavior, sets up default routing,
 * loads custom routes, rolls up all required javascript requests and 
 * loads javascript, loads all required classes, initializes the 
 * controller and dispatches.
 * 
 * @class Glo
 * @todo Router pull out routes into a seperate class
 * @todo consider redefining this as an extension of Y.Base
 **/
var Glo = {};
/**
 * Initialize Glo.  This kickstarts the entire app.
 *
 */
Glo.init = function(config) {
    var self = this;
    
    // set the default config settings
    self.config = {
        filter : 'info',
        groups : {},
        modules:  {}
    };
    // override the config settings
    for (var key in config) {
        self.config[key] = config[key];
    }
    // override the overridden so as to make some configs immutable
    // groups is available in YUI v3.4.0 but it doesn't load the modules
    // listed in groups.modules.foo.requires...maybe in v3.4.1
    // in the mean time we will just use 'modules'
    self.config.modules['glo-controller-front'] = {
        fullpath: 'http://api.gloframework.com/0.0.1/build/controller-front/controller-front.js',
        requires: ['controller']
    };
    self.config.modules['glo-view'] = {
        fullpath: 'http://api.gloframework.com/0.0.1/build/view/view.js',
        requires: ['view']
    };
    self.modules = [
        'glo-controller-front',
        'glo-view'
    ];
    /*
    self.config.groups['glo'] = {
        //combine: true,
        base : '/vendor/glo/0.0.1/js/',
        //comboBase : 'http://yui.yahooapis.com/combo?',
        //root : '0.0.1/js/',
        modules :  {
            'basic-plugin' : {
                path : 'basic-plugin.js',
                requries : ['base', 'plugin', 'node']
            },
            'glo-controller' : {
                path : 'glo-controller.js',
                requries : ['controller']
            }
        }
    };
    */

    
    /**
     * Initialize the 
     *
     */
    YUI(self.config).use('node', 'test', 'glo-controller-front', function(Y) {
        Y.on("domready", function () {
            
            // initialize the controller            
            var controller = new Y.Glo.ControllerFront();
            
            // -- Set Controller Conventions -------------------------------------------
            
            // Always dispatch to an initial route on legacy browsers. Only dispatch to an
            // initial route on HTML5 browsers if it's necessary in order to upgrade a
            // legacy hash URL to an HTML5 URL.
            if (controller.html5) {
                controller.upgrade();
            }
            
            // Indicate to GoogleBot that the hash URLs are crawlable
            Y.HistoryHash.hashPrefix = '!';
            
            // Attach a delegated click handler to listen for clicks on all links on the page.
            Y.one('body').delegate('click', function (e) {
                // todo nav links not handled by the client must indicate so in the anchor
            
                // Allow the native behavior on middle/right-click, or when Ctrl or Command
                // are pressed.
                if (e.button !== 1 || e.ctrlKey || e.metaKey) {
                    return;
                }
                e.preventDefault();
                // Remove the non-path portion of the URL, and any portion of the path that
                // isn't relative to this controller's root path.
                var path = controller.removeRoot(e.currentTarget.get('href'));
                // create an entry in the browser history and deligate to the 
                // appropriate view
                controller.save(path);
            }, 'a');
            
            // -- Set Up Routing -------------------------------------------------------

            // register the custom routes that are defined in the config file
            controller.initRoutes();
            
            Y.log('Initialized the webapp. Ready to rumble!', 'debug', 'Glo.init');




            // try to handle with custom routes
/*
            controller.route('/quick-start', function(req, next) {
                Y.use('gloframework-quick-start', function(Y) {
                    var o = new Y.GloFramework.QuickStartView(req);
                });
            });
*/

            
/*             frontController.save('/pie'); */
/*             G.Y.log('executed a fake route'); */
            
            // register all defined routes
/*
            frontController.route('/', function(req, next) {
                G.Y.log(self);
                var o = new G.IndexView(req);
            });
*/
/*
            frontController.route('/quick-start', function(req, next) {
                var o = new G.QuickStartView(req);
            });
*/
/*
            frontController.route('/examples', function(req, next) {
                var o = new G.ExamplesView(req);
            });
            frontController.route('/documentation', function(req, next) {
                var o = new G.DocumentationView(req);
            });
            frontController.route('/contribute', function(req, next) {
                var o = new G.ContributeView(req);
            });
            frontController.route('/feedback', function(req, next) {
                var o = new G.FeedbackView(req);
            });
*/
        });
    });
    
    return;
}

/**
 * Add custom routes.  The routes you add here will override the default route.
 * 
 * @method addRoutes
 * @todo Glo.addRoutes - this is only a stub.  Need to add guts.
 */
/*
Glo.addRoute = function(routes) {
    Glo._routes[] = routes;
    return;
}
*/



/**
 *
 *
 */
String.prototype.camelCase = function() {
    return this.replace(/(\-[a-z])/g, function(match) {
        return match.toUpperCase().replace('-','');
    });
};

String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}

