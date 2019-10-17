<?php

/**
 * Plugin Name: Give Functional
 * Description: This plugin is to create a workflow for tickets and retunr the values to the user
 * Version: 1.0
 * Author: Sam Smith
 * Author URI: https://gsamsmith.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 */
// this requires and runs everything in init.php
require dirname(__FILE__) . '/templates/give-rider-list.php';
require dirname(__FILE__) . '/templates/give-top-team.php';
require dirname(__FILE__) . '/templates/give-top-rider.php';
// require dirname(__FILE__) . '/templates/give-test-short.php';
require dirname(__FILE__) . '/templates/give-rider-search.php';
require dirname(__FILE__) . '/includes/give-functional-functions.php';


// upon activation this creates all the needed pages
// register_activation_hook(__FILE__, 'install_pages');
