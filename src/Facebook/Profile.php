<?php
/**
 * Class representing a facebook profile
*
* @author Andreas Geisler
* @version 0.1
*/
namespace Facebook;

use Facebook\ProfileFeed;

class Profile {
	protected $id = null;
	protected $profileType = null;
	protected $profileFeed = null;

	public function __construct($profileId, $profileType) {
		$this->id = $profileId;
		$this->profileType = $profileType;
	}

	/**
	 * Get post for specific post id
	 * 
	 * @param bigint|varchar $postId
	 * @return array
	 */
	public function getPost($postId = null) {
		if( empty($postId) ) {
			return array();
		}

		return $this->profileFeed->getPost( $postId );
	}

	/**
	 * Get feed posts from profile
	 *
	 * @return array $posts
	 */
	public function getPosts() {
		if( is_null($this->profileFeed) ) {
			$this->profileFeed = new ProfileFeed($this->id, $this->profileType);
		}

		return $this->profileFeed->getLivePosts();
	}
}