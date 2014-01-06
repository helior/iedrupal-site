module.exports = function(grunt) {
  grunt.initConfig({
    
    compass: {
      prod: {
        options: {
          environment: 'production',
          config: 'src/stylesheets/config.rb',
          basePath: 'src/stylesheets',
          outputStyle: 'compressed'
        }
      },
    
    uglify: {
      prod: {
        options: {
          compress: true,
          preserveComments: false
        },
        files: [{
          expand: true,
          cwd: 'src/scripts',
          src: '**/*.js',
          dest: 'www/assets/js'
        }]
      }
    },

    htmlmin: {
      prod: {
        options: {
          collapseWhitespace: true,
          removeComments: true
        },
        files: [
          {'www/index.html': 'www/index.html'},
          {'www/about/index.html': 'www/about/index.html'},
          {'www/contact/index.html': 'www/contact/index.html'},
          {'www/events/index.html': 'www/events/index.html'},
          {'www/venue/index.html': 'www/venue/index.html'}
        ]
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-htmlmin');

  grunt.registerTask('prod', ['compass:prod', 'uglify:prod', 'htmlmin:prod']);
}
