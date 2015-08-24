<?php

/**
 * Bootstrap for hypeJunction plugins
 *
 * @author Ismayil Khayredinov <info@hypejunction.com>
 * @copyright Copyright (c) 2015, Ismayil Khayredinov
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License v2
 */
try {
	require_once __DIR__ . '/lib/autoloader.php';
	hypeApps()->boot();
} catch (Exception $ex) {
	register_error($ex->getMessage());
}
