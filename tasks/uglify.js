/*!
 * @package    olympus-poseidon
 * @subpackage uglify.js
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 */

'use strict';

module.exports = function (grunt, configs) {
    return {
        app: {
            files: [{
                cwd: configs.paths.src + '/assets/js',
                dest: configs.paths.assets + '/js',
                expand: true,
                src: '*.js'
            }]
        }
    }
};
