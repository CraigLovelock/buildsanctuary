module.exports = function(grunt) {

    // 1. All configuration goes here 
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

				concat: {
						dist: {
								src: [
										'precompile/js/*.js', // All JS in the libs folder
								],
								dest: 'public_html/production_assets/js/production.js',
						},
				},

				uglify: {
						build: {
								src: 'public_html/production_assets/js/production.js',
								dest: 'public_html/production_assets/js/production.min.js'
						}
				},

				imagemin: {
						dynamic: {
								files: [{
										expand: true,
										cwd: 'public_html/images',
										src: ['**/*.{png,jpg,gif,svg,jpeg}'],
										dest: 'public_html/production_assets/images/'
								}]
						}
				},

				concat_css: {
						options: {
								// Task-specific options go here.
						},
						all: {
								src: ["precompile/css/main.css", "precompile/css/jquery-ui.css", "precompile/css/jquery.tagit.css", "precompile/css/custom.css"],
								dest: "public_html/production_assets/css/global.css"
						},
				},

				watch: {

						scripts: {
								files: ['precompile/js/*.js',],
								tasks: ['concat', 'uglify'],
								options: {
										spawn: false,
								},
						},

						css: {
								files: ['precompile/css/*.scss'],
								tasks: ['concat_css'],
								options: {
										spawn: false,
								}
						}

				}

    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-imagemin');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-concat-css');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'imagemin']);

};