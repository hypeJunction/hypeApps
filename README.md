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


### Composer

Nearly all hypeJunction plugins can be installed with Composer. Update your `composer.json` with the following dependencies:

Elgg 1.9 - 1.12:

```json
{
	"require": {
		"hypejunction/ambercal_settings_transfer": "~1.0",
		"hypejunction/elgg_stars": "~3.0",
		"hypejunction/elgg_tokeninput": "~3.0",
		"hypejunction/hypeapps": "~4.0",
		"hypejunction/hypedbexplorer": "~3.0",
		"hypejunction/hypedropzone": "~4.0",
		"hypejunction/hypefaker": "~1.0",
		"hypejunction/hypegallery": "~3.0",
		"hypejunction/hypegeo": "~1.0",
		"hypejunction/hypegraph": "~1.0",
		"hypejunction/hypeinbox": "~4.0",
		"hypejunction/hypeinteractions": "~3.0",
		"hypejunction/hypelists": "~3.0",
		"hypejunction/hypemaps": "~2.0",
		"hypejunction/hypeplaces": "~3.0",
		"hypejunction/hypeprototyper": "~4.0",
		"hypejunction/hypeprototyperui": "~4.0",
		"hypejunction/hypeprototypervalidators": "~4.0",
		"hypejunction/hypescraper": "~4.0",
		"hypejunction/hypewall": "~4.0"
	}
}
```



### Self-contained Release Package

If you are unfamiliar with Composer, use pre-packaged self-contained zip release.
Releases contain all the required files.
