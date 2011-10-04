/**
 * Copyright (c) 2011, Glo Framework
 * All rights reserved.
 * Licensed under the BSD License.
 * http://gloframework.com/license
 */

/**
 *
 * @module glo-model
 */
YUI.add('glo-model', function(Y) {

    /**
     * The purpose of this class is to enforce a set of default conventions
     * for all models.
     * 
     * @class Glo.Model
     * @constructor
     * @extends YUI.Model
     **/
    Y.namespace('Glo').Model = Y.Base.create('gloModel', Y.Controller, [],
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
                Y.Glo.Model.superclass.initializer.apply(this, arguments);
            }
            
            // -- Public Methods -------------------------------------------------------
        },
        {
            // -- Static Properties ----------------------------------------------------
            
            
            // -- Static Methods -------------------------------------------------------
        
        }
    );
    
}, '0.0.1', { requires: ['model'] });
