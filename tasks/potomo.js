/*!
 * @package    olympus-poseidon
 * @subpackage potomo.js
 * @author     Achraf Chouk <achrafchouk@gmail.com>
 * @since      0.0.1
 */

'use strict';

module.exports = function (grunt, configs) {
    var srcpath = configs.paths.src + '/' + configs.paths.i18n,
        jsons = grunt.file.expand({filter: "isFile"}, srcpath + '/*.json');

    // read all files and generate PO files
    jsons.forEach(function (jsonfile) {
        var names      = jsonfile.split('/').pop().split('.'),
            json       = '',
            language   = '',
            textdomain = configs.textdomain;

        if (2 >= names.length) {
            language = names[0];
            json     = grunt.file.readJSON(srcpath + '/' + language + '.json');
        } else {
            language   = names[1];
            textdomain = names[0];
            json       = grunt.file.readJSON(srcpath + '/' + textdomain + '.' + language + '.json');
        }

        var text     = '';

        for (var item in json) {
            text += 'msgid "' + item + '"' + "\r\n";
            text += 'msgstr "' + json[item].replace(/\"/g, '\\"') + '"' + "\r\n";
            text += "\r\n";
        }

        grunt.file.write(configs.paths.i18n + '/' + textdomain + '-' + language + '.po', text);

        if ("en_US" === language) {
            grunt.file.write(configs.paths.i18n + '/' + textdomain + '-default.po', text);
        }
    });

    return {
        app: {
            options: {
                poDel: true
            },
            files: [{
                cwd: configs.paths.i18n,
                dest: configs.paths.i18n,
                expand: true,
                ext: '.mo',
                src: '*.po'
            }]
        }
    }
};
