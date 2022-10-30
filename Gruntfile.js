/*!
 * @package    olympus-poseidon
 * @subpackage Gruntfile.js
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 */

'use strict';

module.exports = function (grunt) {
    require('load-grunt-config')(grunt, {
        configPath: require('path').join(__dirname, 'tasks'),
        config: grunt.file.readJSON('tasks/options.json')
    });
};
