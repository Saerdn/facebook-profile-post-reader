<?php
/**
 * Main script for reading Facebook feeds of given profiles
 * 
 * @author Andreas Geisler
 */
require("config.php");
require("autoload.php");
use Facebook\Profile;

foreach($profileIds as $profileId => $profileType) {
	$profile = new Profile($profileId, $profileType);
	$profilePosts = $profile->getPosts(); // Automatically saves new data (And yes! That's not perfect ...)
}