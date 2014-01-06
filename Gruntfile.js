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
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('prod', ['compass:prod', 'uglify:prod']);
}
