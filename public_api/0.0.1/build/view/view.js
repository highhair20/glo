/**
 * Copyright (c) 2011, Glo Framework
 * All rights reserved.
 * Licensed under the BSD License.
 * http://gloframework.com/license
 */ 
/**
 *
 * @module glo-view
 */
YUI.add('glo-view', function(Y) {

    /**
     * The purpose of this class is to enforce a set of default conventions
     * for all views.
     * 
     * @class BaseView
     * @constructor
     * @extends YUI.View
     **/
    Y.namespace('Glo').View = Y.Base.create('gloView', Y.View, [],
        {
            // -- Public Properties ----------------------------------------------------
            
            
            // -- Lifecycle Methods ----------------------------------------------------
            /**
             * Place holder for overriding the initializer in the parent class.
             * For now this method just deligate to the parent.
             *
             * @method initializer
             */
            initializer : function(config) {
                Y.Glo.View.superclass.initializer.apply(this, arguments);
            }
            
            // -- Public Methods -------------------------------------------------------
        },
        {
            // -- Static Properties ----------------------------------------------------
            
            
            // -- Static Methods -------------------------------------------------------
        
        }
    );
    
}, '0.0.1', { requires: ['view'] });
