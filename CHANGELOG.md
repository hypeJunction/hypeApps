<a name="5.1.0"></a>
# [5.1.0](https://github.com/hypeJunction/hypeApps/compare/5.0.2...v5.1.0) (2015-12-25)


### Bug Fixes

* **icons:** remove erraneous third parameter ([566a506](https://github.com/hypeJunction/hypeApps/commit/566a506))

### Features

* **releases:** improved automated releases ([d210b3a](https://github.com/hypeJunction/hypeApps/commit/d210b3a))



<a name="5.0.2"></a>
## [5.0.2](https://github.com/hypeJunction/hypeApps/compare/5.0.2...v5.0.2) (2015-12-25)




<a name="5.0.2"></a>
## [5.0.2](https://github.com/hypeJunction/hypeApps/compare/4.3.4...5.0.2) (2015-11-18)


### Bug Fixes

* **integration:** settings.php location may have changed ([5bb515f](https://github.com/hypeJunction/hypeApps/commit/5bb515f))



<a name="4.3.4"></a>
## [4.3.4](https://github.com/hypeJunction/hypeApps/compare/4.3.3...4.3.4) (2015-10-29)


### Bug Fixes

* **icons:** default to file entity mimetype before assuming jpeg ([ffc99e7](https://github.com/hypeJunction/hypeApps/commit/ffc99e7))
* **icons:** do not attempt a crop if props are empty ([be268ff](https://github.com/hypeJunction/hypeApps/commit/be268ff))
* **icons:** only create empty file before writing to it ([c355c56](https://github.com/hypeJunction/hypeApps/commit/c355c56))
* **icons:** when cropping do not resize image to outside bounds ([0058e03](https://github.com/hypeJunction/hypeApps/commit/0058e03))



<a name="4.3.3"></a>
## [4.3.3](https://github.com/hypeJunction/hypeApps/compare/4.3.2...4.3.3) (2015-10-15)


### Bug Fixes

* **batch:** do not enforce order if sort order is not set ([0a79d06](https://github.com/hypeJunction/hypeApps/commit/0a79d06))
* **files:** fallback to default mime type if resource is unreadable (for remote urls) ([fadf1f2](https://github.com/hypeJunction/hypeApps/commit/fadf1f2))
* **vendors:** bring back classmap to ensure that .gitignore rules in wideimage do not break us ([08ec3b3](https://github.com/hypeJunction/hypeApps/commit/08ec3b3))



<a name="5.0.1"></a>
## [5.0.1](https://github.com/hypeJunction/hypeApps/compare/5.0.0...5.0.1) (2015-10-10)


### Bug Fixes

* **di:** hook registration lost in merge ([901455b](https://github.com/hypeJunction/hypeApps/commit/901455b))



<a name="5.0.0"></a>
# [5.0.0](https://github.com/hypeJunction/hypeApps/compare/4.3.1...5.0.0) (2015-10-10)




<a name="4.3.2"></a>
## [4.3.2](https://github.com/hypeJunction/hypeApps/compare/5.0.1...4.3.2) (2015-10-10)


### Bug Fixes

* **vendors:** wideimage now has a proper tag ([e7d2b90](https://github.com/hypeJunction/hypeApps/commit/e7d2b90))



<a name="4.3.1"></a>
## [4.3.1](https://github.com/hypeJunction/hypeApps/compare/4.3.0...4.3.1) (2015-10-08)


### Bug Fixes

* **core:** move hooks into invokable classes ([c85ae68](https://github.com/hypeJunction/hypeApps/commit/c85ae68))
* **data:** sanitizers and validators should not need an object to perform ([ed3e1e8](https://github.com/hypeJunction/hypeApps/commit/ed3e1e8))
* **files:** do not call ElggFile::detectMimeType() statically ([9c1eedc](https://github.com/hypeJunction/hypeApps/commit/9c1eedc))



<a name="4.3.0"></a>
# [4.3.0](https://github.com/hypeJunction/hypeApps/compare/4.2.13...4.3.0) (2015-10-05)


### Bug Fixes

* **batch:** batch result now returns correct count ([fb05a67](https://github.com/hypeJunction/hypeApps/commit/fb05a67))
* **deps:** ignore platform requirements so we can install wideimage ([202cf64](https://github.com/hypeJunction/hypeApps/commit/202cf64))
* **exporter:** terminate early if $data is falsy ([78bf521](https://github.com/hypeJunction/hypeApps/commit/78bf521))



<a name="4.2.13"></a>
## [4.2.13](https://github.com/hypeJunction/hypeApps/compare/4.2.12...4.2.13) (2015-09-24)


### Bug Fixes

* **docs:** fix function return in docs to reflect actual value ([206a5a6](https://github.com/hypeJunction/hypeApps/commit/206a5a6))



<a name="4.2.12"></a>
## [4.2.12](https://github.com/hypeJunction/hypeApps/compare/4.2.11...4.2.12) (2015-09-03)


### Bug Fixes

* **actions:** canDelete is only available after 1.11 ([2ae28e8](https://github.com/hypeJunction/hypeApps/commit/2ae28e8))



<a name="4.2.11"></a>
## [4.2.11](https://github.com/hypeJunction/hypeApps/compare/4.2.10...4.2.11) (2015-08-24)


### Bug Fixes

* **icons:** classname missing scope ([f3c9db1](https://github.com/hypeJunction/hypeApps/commit/f3c9db1))



<a name="4.2.10"></a>
## [4.2.10](https://github.com/hypeJunction/hypeApps/compare/4.2.9...4.2.10) (2015-08-11)


### Bug Fixes

* **autoloader:** require autoload.php once for Elgg 1.8 ([1dfd889](https://github.com/hypeJunction/hypeApps/commit/1dfd889))



<a name="4.2.9"></a>
## [4.2.9](https://github.com/hypeJunction/hypeApps/compare/4.1.3...4.2.9) (2015-08-10)


### Bug Fixes

* **actions:** delete action sniffs the referrer to decide on forward location ([5ec2084](https://github.com/hypeJunction/hypeApps/commit/5ec2084))
* **core:** fix comparison operator ([4223d61](https://github.com/hypeJunction/hypeApps/commit/4223d61))
* **grunt:** commit changes before release ([d33f042](https://github.com/hypeJunction/hypeApps/commit/d33f042))
* **grunt:** commit changes before release ([82a19c8](https://github.com/hypeJunction/hypeApps/commit/82a19c8))
* **icons:** add missing method to output raw icon ([bf44ea2](https://github.com/hypeJunction/hypeApps/commit/bf44ea2))



<a name="4.1.2"></a>
## [4.1.2](https://github.com/hypeJunction/hypeApps/compare/4.1.1...4.1.2) (2015-07-27)


### Bug Fixes

* **icons:** vendor lib now matches the path of the Elgg vendor lib ([e51b2ae](https://github.com/hypeJunction/hypeApps/commit/e51b2ae))



<a name="4.1.1"></a>
## [4.1.1](https://github.com/hypeJunction/hypeApps/compare/4.1.0...4.1.1) (2015-07-27)


### Bug Fixes

* **actions:** use statements are missing ([7c9eb23](https://github.com/hypeJunction/hypeApps/commit/7c9eb23))



<a name="4.1.0"></a>
# [4.1.0](https://github.com/hypeJunction/hypeApps/compare/130d37c...4.1.0) (2015-07-27)


### Bug Fixes

* **actions:** show validation exception messages to the user ([130d37c](https://github.com/hypeJunction/hypeApps/commit/130d37c))
* **classes:** remove static factory from abstract class ([02da7e4](https://github.com/hypeJunction/hypeApps/commit/02da7e4))
* **core:** add gitattributes ([f0a64ef](https://github.com/hypeJunction/hypeApps/commit/f0a64ef))
* **icons:** icon sizes for files should check the subtype and not the class instance ([506d0d8](https://github.com/hypeJunction/hypeApps/commit/506d0d8))



