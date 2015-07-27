hypeApps
========
![Elgg 1.8](https://img.shields.io/badge/Elgg-1.8.x-orange.svg?style=flat-square)
![Elgg 1.9](https://img.shields.io/badge/Elgg-1.9.x-orange.svg?style=flat-square)
![Elgg 1.10](https://img.shields.io/badge/Elgg-1.10.x-orange.svg?style=flat-square)
![Elgg 1.11](https://img.shields.io/badge/Elgg-1.11.x-orange.svg?style=flat-square)
![Elgg 1.12](https://img.shields.io/badge/Elgg-1.12.x-orange.svg?style=flat-square)


Bootstrap for hypeJunction plugins

* Provides dependency-injection container for earlier Elgg versions
* Provides shims for forward compatibility
* Succeeds hypeFilestore to provide upload handling and fast icon serving


## Installation


### Production

Recommended way to install hypeJunction plugins is through Composer.
For example, to install hypeGraph, add the following to ```composer.json```
in your Elgg root.

```json
{
	"require": {
		"hypejunction/hypegraph": "1.*"
	}
}
```

Using your command-line, run:

```sh
composer install --no-dev
```

### Development

To install an actual git package that you can make pull requests against, add

```json
{
	"config": {
		"preferred-install": "source"
	},
	"require": {
		"hypejunction/hypegraph": "1.*"
	}
}
```

```sh
composer install
```