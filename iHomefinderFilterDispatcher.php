<?php
if( !class_exists('IHomefinderFilterDispatcher')) {

	/**
	 *
	 * This singleton class is used to filter the content of iHomefinder pages.
	 * We use the iHomefinderFilterFactory class to retrieve the
	 * proper filter implementation.
	 *
	 * @author ihomefinder
	 */
	class IHomefinderFilterDispatcher {

		private static $instance ;
		private $ihfAdmin ;

		private $currentFilter = null;
		private $content = null;
		private $title = null;
		private $initialized=false;

		private function __construct(){
			$this->ihfAdmin = IHomefinderAdmin::getInstance();
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderFilterDispatcher();
			}
			return self::$instance;
		}

		public function init(){
			if( !$this->initialized ){

				if( $type = get_query_var(IHomefinderConstants::IHF_TYPE_URL_VAR) ) {
					$this->currentFilter= IHomefinderFilterFactory::getInstance()->getFilter($type);
					$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			    	$this->content=$this->currentFilter->filter('', $authenticationToken);
			    	$this->title=$this->currentFilter->getTitle();
			    	$this->initialized=true;
				}
			}
		}

		/**
		 * We identify iHomefinder requests based on the query_var
		 * iHomefinderConstants::IHF_TYPE_URL_VAR.
		 * Set the proper title and update the posts array to contain only
		 * a single posts.  This will get updated in another action later
		 * during processing.  We cannot set the post content here, because
		 * Wordpress does some odd formatting of the post_content, if we
		 * add it here (see the filter method below, where content is properly set)
		 *
		 *
		 *
		 * @param $posts
		 */
		function postCleanUp($posts){

			$this->init();
			if( $this->initialized ){
				$title = $this->currentFilter->getTitle();
		        $_postArray['post_title'] = $title ;
		        $_postArray['post_content'] = 'ihf' ;
		        $_postArray['post_status'] = 'publish';
		        $_postArray['post_type'] = 'page';
		        $_postArray['comment_status'] = 'closed';
		        $_postArray['ping_status'] = 'closed';
		        $_postArray['post_category'] = array(1); // the default 'Uncategorized'
		        $_postArray['post_parent'] = 0;
		        $_postArray['post_author'] = 0;
		        $_postArray['post_date'] = current_time('mysql');
		        $_postObject=(object) $_postArray ;
		        $_postObject=get_post($_postObject);

	        	$posts= array();
		    	$posts[0]=$_postObject;
			}
			return $posts ;
		}

		/**
		 * For the ihf plugin page, we replace the content, with data retrieved from
		 * the iHomefinder servers.
		 *
		 * This function uses a Factory to get the correct filter implementation.
		 *
		 * @param $content
		 */
		function filter( $content ) {
			$this->init();
			if( $this->initialized ){
		    	$content = $this->content;
			}
			return $content;
		}

		function filterByType( $content, $type ) {
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilterDispatcher.filter');

			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

		    if( $type ) {
		    	$ihfFilter = IHomefinderFilterFactory::getInstance()->getFilter($type);
		    	$content=$ihfFilter->filter($content, $authenticationToken);

		    }

			IHomefinderLogger::getInstance()->debug('Complete function IHomefinderFilterDispatcher.filter');
		    return $content;
		}
	}
}//end if( !class_exists('IHomefinderFilter'))
?>