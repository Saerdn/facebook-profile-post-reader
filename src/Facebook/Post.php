<?php
/**
 * Class representing a post of a feed.
 * 
 * @author Andreas Geisler
 * @version 0.1
 */
namespace Facebook;

// Hack for Using date() ...
date_default_timezone_set("GMT");

class Post {
	protected $data = array();
	protected static $dbc = null;
	
	/**
	 * Initialize database connection.
	 * TODO: Outsource to parent class if other objects need this method OR move to common class
	 */
	public static function openDbc() {
		self::$dbc = new \PDO('mysql:dbname='. DATABASE .';host='. HOST .';', DBLOGIN, DBPASS);
	}
	
	/**
	 * Unset close database connection
	 */
	public static function closeDbc() {
		self::$dbc = null;
	}
	
	/**
	 * Set all(!!) data for Post
	 * 
	 * @param array $data
	 */
	public function setData( $data ) {
		$this->data = $data;

		// TODO: Probably not the best idea to automatically load data into db but meh ...
		if( is_array($data) && !empty($data) ) {
			$this->dataToDb();
		}
	}
	
	/**
	 * Get all data of Post
	 * 
	 * @return multitype
	 */
	public function getData() {
		// TODO: Re-read if empty
		return $this->data;
	}
	
	/**
	 * Return value of Post for given key.
	 * Only checks if key exists on first level of array. 
	 * 
	 * @param string $key
	 * @return multitype
	 */
	public function getAttribute($key) {
		if( isset($this->data[$key]) ) {
			return($this->data[$key]);
		}
	}
	
	/**
	 * Save data to db
	 * 
	 * @return bool Did it work?
	 */
	private function dataToDb() {
		if( empty($this->data) || is_null(self::$dbc) ) {
			return false; // Looks like something went wrong when setting the data or someone forgot to set the db connection
		}

		// Extract the db relevant values for: profileId, postId, type, from, message, picture
		// These values should always be set!!
		
		// Split profileId and postId to separate values
		list($profileId, $postId) = explode("_", $this->data['id']);

		// Name of profile
		$profilename = utf8_decode($this->data['from']['name']);
		
		// Either "message", "description", "story", "caption" are set - check which is set, in that order
		if( isset($this->data['message']) ) {
			$message = $this->data['message'];
		} elseif( isset($this->data['story']) ) {
			$message = $this->data['story'];
		}  elseif( isset($this->data['description']) ) {
			$message = $this->data['description'];
		} elseif( isset($this->data['caption']) ) {
			$message = $this->data['caption'];
		} else {
			$message = ''; // Shouldn't happen thou ...
		}

		$message 			  = utf8_decode($message);
		$type				  = ( isset($this->data['type']) )? $this->data['type'] : '';
		$pictureurl 		  =	( isset($this->data['picture']) )? $this->data['picture'] : '';
		$profiletype		  = $this->data['profiletype'];
		$postcreationdatetime = $this->data['created_time'];
		$created			  =	date("Y-m-d");

		// Insert everything in db
		// Disclaimer: Using extra variables ($type, $profileId, ...) instead of directly using $this->data[] for structure and tidiness
		try {
			$stmt = self::$dbc->prepare("INSERT IGNORE INTO `fbpts_posts` (`profileid`, `postid`,`profilename`, `message`, `type`, `profiletype`, `pictureurl`, `postcreationdatetime`, `created`)
										  VALUES(:profileid, :postid, :profilename, :message, :type, :profiletype ,:pictureurl, :postcreationdatetime, :created)");

			$stmt->bindParam(':profileid', $profileId);
			$stmt->bindParam(':postid', $postId);
			$stmt->bindParam(':profilename', $profilename, \PDO::PARAM_STR);
			$stmt->bindParam(':message', $message, \PDO::PARAM_STR);
			$stmt->bindParam(':type', $type, \PDO::PARAM_STR);
			$stmt->bindParam(':profiletype', $profiletype, \PDO::PARAM_STR);
			$stmt->bindParam(':pictureurl', $pictureurl, \PDO::PARAM_STR);
			$stmt->bindParam(':postcreationdatetime', $postcreationdatetime, \PDO::PARAM_STR);
			$stmt->bindParam(':created', $created, \PDO::PARAM_STR);

			$stmt->execute();

			return true;			
		} catch(PDOException $e) {
			// TODO: Logging ...
			// print_r($e);
			return false;
		}

	}
}