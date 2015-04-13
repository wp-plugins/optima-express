<?php

/**
 *
 * This singleton class is used to filter the content of iHomefinder VirtualPages.
 * We use the iHomefinderVirtualPageFactory class to retrieve the
 * proper VirtualPage implementation.
 *
 * @author ihomefinder
 */
class iHomefinderVirtualPageDispatcher {

	private static $instance;

	private $virtualPage = null;
	private $content = null;
	private $excerpt = null;
	private $title = null;
	private $initialized = false;
	
	private $genericErrorPageContent="Error 123: Unable to load content. Please visit the <a href='http://www.ihomefinder.com/support/optima-express/error-messages/' target='_blank'>support guide</a>, or contact customer support.";

	private function __construct() {
		
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderVirtualPageDispatcher();
		}
		return self::$instance;
	}

	public function init() {
		global $wp_query;
		$postsCount = $wp_query->post_count;
		$type = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		//we only try to initialize, if we are accessing a virtual page
		//which does not have any true posts in the global posts array	
		if(!$this->initialized && $postsCount == 0 && !empty($type)) {
			$this->virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage($type);
			$this->content = (string) $this->virtualPage->getContent();
			$this->excerpt = (string) $this->content;
			$this->title = (string) $this->virtualPage->getTitle();
			iHomefinderEnqueueResource::getInstance()->addToHeader($this->virtualPage->getHead());
			iHomefinderEnqueueResource::getInstance()->addToMetaTags($this->virtualPage->getMetaTags());
			$this->initialized = true;
			//turn off some filters on ihf pages
			$this->removeFilters();
		}
	}
	
	private function removeFilters() {
		$tags = array("the_content", "the_excerpt");
		$functionNames = array("wpautop", "wptexturize", "convert_chars");
		foreach($tags as $tag) {
			foreach($functionNames as $functionName) {
				remove_filter($tag, $functionName);
			}
		}
	}
	
	/**
	 * Cleanup state after filtering.  This fixes an issue
	 * where widgets display different loop content, such
	 * as featured posts.
	 */
	private function afterFilter() {
		$this->initialized = false;
	}
	
	/**
	 * We identify iHomefinder requests based on the query_var
	 * iHomefinderConstants::IHF_TYPE_URL_VAR.
	 * Set the proper title and update the posts array to contain only
	 * a single posts.  This will get updated in another action later
	 * during processing.  We cannot set the post content here, because
	 * Wordpress does some odd formatting of the post_content, if we
	 * add it here (see the getContent method below, where content is properly set)
	 *
	 * @param $posts
	 */
	public function postCleanUp($posts) {
		$this->init();
		if($this->initialized) {
			$_postArray['post_title'] = $this->title;
			//This value will get replaced with remote content.  If it is not replaced, then an error
			//has occurred and we leave the following default text.
			$_postArray['post_content'] = $this->content;
			$_postArray['post_excerpt'] = $this->excerpt;
			$_postArray['post_status'] = 'publish';
			$_postArray['post_type'] = 'page';
			$_postArray['is_page'] = 1;
			$_postArray['is_single'] = 1;
			$_postArray['comment_status'] = 'closed';
			$_postArray['comment_count'] = 0;
			$_postArray['ping_status'] = 'closed';
			$_postArray['post_category'] = array(1); // the default 'Uncategorized'
			$_postArray['post_parent'] = 0;
			$_postArray['post_author'] = 0;
			$_postArray['post_date'] = current_time('mysql');
			$_postArray['ID'] = 0;
			$_postObject = (object) $_postArray;
			$_postObject = get_post($_postObject);

			$posts= array();
			$posts[0] = $_postObject;
		}
		return $posts;
	}
	
	/**
	 * Sets the page template used for our virtual pages
	 * The page templates are set in Wordpress admin.
	 * 
	 * @param $pageTemplate
	 */
	public function getPageTemplate($pageTemplate) {					
		$this->init();
		if($this->initialized) {
			$virtualPageTemplate = $this->virtualPage->getPageTemplate();
			if(iHomefinderUtility::getInstance()->isStringEmpty($virtualPageTemplate)) {
				$virtualPageTemplate = iHomefinderVirtualPageHelper::getInstance()->getDefaultTemplate();
			}
			//If the $virtualPageTemplate is NOT empty, then reset $pageTemplate
			if(!iHomefinderUtility::getInstance()->isStringEmpty($virtualPageTemplate)) {
				$templates = array($virtualPageTemplate);
				//gets the disk location of the template
				$pageTemplate = locate_template($templates); 
			}				
		}
		return $pageTemplate;
	}

	/**
	 * For the ihf plugin page, we replace the content, with data retrieved from
	 * the iHomefinder servers.
	 *
	 * @param $content
	 */
	public function getContent($content) {
		$this->init();
		if($this->initialized) {
			$content = $this->content;
		}
		//reset init params
		$this->afterFilter();
		return $content;
	}

	/**
	 * For the ihf plugin page, we replace the excerpt, with data retrieved from
	 * the iHomefinder servers.
	 *
	 * @param $content
	 */
	public function getExcerpt($excerpt) {
		$this->init();
		if($this->initialized) {
			$excerpt = $this->excerpt;
		}
		//reset init params
		$this->afterFilter();
		return $excerpt;
	}
	
	/**
	 * If this is a virtual page, clear out any comments
	 */
	public function clearComments($comments) {
		if(get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR)) {
			$comments = array();
		}
		return $comments;
	}
	
}