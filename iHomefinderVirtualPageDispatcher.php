<?php
if( !class_exists('IHomefinderVirtualPageDispatcher')) {

	/**
	 *
	 * This singleton class is used to filter the content of iHomefinder VirtualPages.
	 * We use the iHomefinderVirtualPageFactory class to retrieve the
	 * proper VirtualPage implementation.
	 *
	 * @author ihomefinder
	 */
	class IHomefinderVirtualPageDispatcher {

		private static $instance ;
		private $ihfAdmin ;

		private $currentVirtualPage = null;
		private $content = null;
		private $title = null;
		private $initialized=false;
		
		private $genericErrorPageContent="Error 123: Unable to load content. Please visit the <a href='http://www.ihomefinder.com/support/optima-express/error-messages/' target='_blank'>support guide</a>, or contact customer support.";

		private function __construct(){
			$this->ihfAdmin = IHomefinderAdmin::getInstance();
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderVirtualPageDispatcher();
			}
			return self::$instance;
		}

		public function init(){
			global $wp_query ;
			
			if( !$this->initialized ){
				if( $type = get_query_var(IHomefinderConstants::IHF_TYPE_URL_VAR) ) {
					$this->currentVirtualPage= IHomefinderVirtualPageFactory::getInstance()->getVirtualPage($type);
					$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
					$this->content=$this->currentVirtualPage->getContent($authenticationToken);
					$this->title=$this->currentVirtualPage->getTitle();
					$this->initialized=true;
				}
			}
		}
		
		/**
		 * Cleanup state after filtering.  This fixes an issue
		 * where widgets display different loop content, such
		 * as featured posts.
		 */
		private function afterFilter(){
			$this->initialized=false;
		}
		
		public function clearComments($comments){
			//If this is a virtual page, clear out any comments
			if( get_query_var(IHomefinderConstants::IHF_TYPE_URL_VAR) ) {
				$comments=array();
			}
			return $comments;
		}
		
		/**
		 * Load JavaScript using Wordpress script queues
		 * jquery-ui-tabs is included so that the srep plugin functions properly
		 */
		function loadJavaScript(){
			wp_enqueue_script('jquery');			
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6'); 			
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
		function postCleanUp($posts){
			$this->init();
			if( $this->initialized ){
				$title = $this->currentVirtualPage->getTitle();
				$_postArray['post_title'] = $this->getTitle() ;
				//This value will get replaced with remote content.  If it is not replaced, then an error
				//has occurred and we leave the following default text.
				$_postArray['post_content'] = $this->content ;
				$_postArray['post_excerpt'] = $this->content ;
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
				$_postObject=(object) $_postArray ;
				$_postObject=get_post($_postObject);

				$posts= array();
				$posts[0]=$_postObject;
			}
			return $posts ;
		}
		
		function getTitle(){
			$this->init();
			if( $this->initialized ){
				$virtualPageTitle=$this->currentVirtualPage->getTitle();
				if( $virtualPageTitle != null && '' != $virtualPageTitle){
					$title=$virtualPageTitle ;
				}
			}
			return $this->title ;
		}
		
		/**
		 * Sets the page template used for our virtual pages
		 * The page templates are set in Wordpress admin.
		 * 
		 * @param $pageTemplate
		 */
		function getPageTemplate($pageTemplate){					
			$this->init();
			$virtualPageTemplate=null;
			
			if( $this->initialized ){
				$virtualPageTemplate=$this->currentVirtualPage->getPageTemplate();
				if( IHomefinderUtility::getInstance()->isStringEmpty($virtualPageTemplate)){
					$virtualPageTemplate=IHomefinderVirtualPageHelper::getInstance()->getDefaultTemplate() ;
				}
				//If the $virtualPageTemplate is NOT empty, then reset $pageTemplate
				if( !IHomefinderUtility::getInstance()->isStringEmpty($virtualPageTemplate)){
					$templates=array($virtualPageTemplate);
					//gets the disk location of the template
					$pageTemplate=  locate_template(  $templates ) ; 
				}				
			}
			return $pageTemplate ;
		}

		/**
		 * For the ihf plugin page, we replace the content, with data retrieved from
		 * the iHomefinder servers.
		 *
		 * This function uses a Factory to get the correct VirtualPage implementation.
		 *
		 * @param $content
		 */
		function getContent( $content ) {
			$this->init();
			if( $this->initialized ){
				$content = $this->content;
			}
			//reset init params
			$this->afterFilter() ;
			return $content;
		}

		/**
		 * For the ihf plugin page, we replace the excerpt, with data retrieved from
		 * the iHomefinder servers.
		 *
		 * This function uses a Factory to get the correct VirtualPage implementation.
		 *
		 * @param $content
		 */
		function getExcerpt( $excerpt ) {
			$this->init();
			if( $this->initialized ){
				$excerpt = $this->content;
			}
			//reset init params
			$this->afterFilter() ;
			return $excerpt;
		}		
		
		function getContentByType( $content, $type ) {
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderVirtualPageDispatcher.getContentByType');

			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			if( $type ) {
				$ihfVirtualPage = IHomefinderVirtualPageFactory::getInstance()->getVirtualPage($type);
				$content=$ihfVirtualPage->getContent( $authenticationToken);

			}

			IHomefinderLogger::getInstance()->debug('Complete function IHomefinderVirtualPageDispatcher.getContentByType');
			return $content;
		}
	}
}//end if( !class_exists('IHomefinderVirtualPageDispatcher'))
?>