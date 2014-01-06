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
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');

  grunt.registerTask('prod', ['compass:prod']);
}
