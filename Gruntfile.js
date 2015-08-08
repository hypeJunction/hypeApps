module.exports = function (grunt) {

	var package = grunt.file.readJSON('package.json');

	// Project configuration.
	grunt.initConfig({
		pkg: package,
		// Bump version numbers
		version: {
			pkg: {
				src: ['package.json', 'composer.json'],
			},
			manifest: {
				options: {
					pkg: grunt.file.readJSON('package.json'),
					prefix: '\<version\>'
				},
				src: ['manifest.xml'],
			}
		},
		clean: {
			release: {
				src: ['build/', 'releases/', 'vendor/', 'composer.lock']
			}
		},
		copy: {
			release: {
				src: [
					'**',
					'!**/.git*',
					'!releases/**',
					'!build/**',
					'!node_modules/**',
					'!package.json',
					'!config.rb',
					'!sass/**',
					'!tests/**',
					'!composer.json',
					'!composer.lock',
					'!package.json',
					'!phpunit.xml',
					'!Gruntfile.js',
				],
				dest: 'build/',
				expand: true
			},
		},
		compress: {
			release: {
				options: {
					archive: 'releases/<%= pkg.name %>-<%= pkg.version %>.zip'
				},
				cwd: 'build/',
				src: ['**/*'],
				dest: '<%= pkg.name %>/',
				expand: true
			}
		},
		gh_release: {
			options: {
				token: process.env.GITHUB_TOKEN,
				repo: package.repository.repo,
				owner: package.repository.owner
			},
			release: {
				tag_name: '<%= pkg.version %>',
				target_commitish: 'dev',
				name: 'Release <%= pkg.version %>',
				body: 'Self-contained ZIP distribution for <%= pkg.name %>',
				draft: false,
				prerelease: false,
				asset: {
					name: '<%= pkg.name %>-<%= pkg.version %>.zip',
					file: 'releases/<%= pkg.name %>-<%= pkg.version %>.zip',
					'Content-Type': 'application/zip'
				}
			}
		}
	});

	// Load all grunt plugins here
	grunt.loadNpmTasks('grunt-version');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-clean');
	grunt.loadNpmTasks('grunt-contrib-compress');
	grunt.loadNpmTasks('grunt-composer');
	grunt.loadNpmTasks('grunt-gh-release');

	grunt.registerTask('readpkg', 'Read in the package.json file', function () {
		grunt.config.set('pkg', grunt.file.readJSON('package.json'));
	});

	// Release task
	grunt.registerTask('release', function () {
		var target = grunt.option('target') || 'patch';
		grunt.task.run(['version::' + target, 'readpkg', 'clean:release', 'composer:install:no-dev:prefer-dist', 'copy:release', 'compress:release', 'gh_release']);
	});
};