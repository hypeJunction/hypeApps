<?php

/**
 * Bootstrap for hypeJunction plugins
 *
 * @author Ismayil Khayredinov <ismayil.khayredinov@gmail.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */
try {
	require_once __DIR__ . '/lib/autoloader.php';
	hypeApps()->boot();
} catch (Exception $ex) {
	elgg_log($ex->getMessage(), 'ERROR');
	if (elgg_is_admin_logged_in()) {
		register_error($ex->getMessage());
	}
	hypeApps()->deactivate();
}
