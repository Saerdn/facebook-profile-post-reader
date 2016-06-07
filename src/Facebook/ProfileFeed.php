<?php
/**
 * Class representing a ProfileFeed
 * 
 * @author andreas.geisler
 * @version 0.1
 */
namespace Facebook;

use Facebook\Request;
use Facebook\Post;

class ProfileFeed {
	protected $profileId = null;
    protected $profileType = null;
	protected $posts = array();

	public function __construct( $profileId, $profileType ) {
		$this->profileId = $profileId;
        $this->profileType = $profileType;
		Request::setAppCredentials(APPID, APPSECRET);
	}

	/**
	 * Get post for a specific post id
	 *
	 * @param int
	 * @return Post
	 */
	public function getPost($postId = null) {
		if( empty($postId) || !isset($this->posts[$postId]) ) {
			return array();
		}

		return $this->posts[$postId];
	}
	
	/**
	 * Read posts from live feed of profile
	 * 
	 * @return array
	 */
	public function getLivePosts() {
		$request = new Request();
		$profileFeedData = $request->readProfileFeed($this->profileId);

		if( empty($profileFeedData['data']) ) {
			return $this->posts;
		}

		// TODO: Move openDbc and closeDbc to proper place
		Post::openDbc();
		foreach( $profileFeedData['data'] as $postData ) {

			// Check if $postData is really from desired profile (and not just a posting from another Profile to this one's wall)
			if( $postData['from']['id'] != $this->profileId ) {
                continue;
			}

			// Smuggle the profiletype into the data
            if( isset($this->profileType) ) {
                $postData['profiletype'] = $this->profileType;
            }

			$post = new Post();
			$post->setData($postData);
			$this->posts[$postData['id'] ] = $post;
		}
		Post::closeDbc();

		return $this->posts;
	}

}