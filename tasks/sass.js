/*!
 * @package    olympus-poseidon
 * @subpackage sass.js
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 */

'use strict';

module.exports = function (grunt, configs) {
    const sass = require('node-sass');

    return {
        app: {
            options: {
                implementation: sass,
            },
            files: [{
                cwd: configs.paths.src + '/assets/scss',
                expand: true,
                src: 'import.scss',
                rename: function () {
                    return configs.paths.assets + '/css/poseidon.css';
                }
            }]
        }
    }
};
