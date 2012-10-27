<?php
/**
 * Constant that is checked in included files to prevent direct access.
 */
const _JEXEC = 1;

// Define the paths we need
const JPATH_BASE = __DIR__;
const JPATH_ROOT = __DIR__;
define('JPATH_LIBRARIES', dirname(JPATH_ROOT) . '/libraries');

// Set up the environment
error_reporting(E_ALL);
ini_set('display_errors', true);
const JDEBUG = false;

// Import the Joomla Platform.
require_once JPATH_LIBRARIES . '/import.php';

// Register the prefix for our classes
JLoader::registerPrefix('G', JPATH_BASE);

// Get the application
$app = JApplicationWeb::getInstance('GApplicationWeb');

// Execute the application
$app->execute();
