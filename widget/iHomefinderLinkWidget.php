<?php
if( !class_exists('iHomefinderLinkWidget')) {
	/**
	 * iHomefinderLinkWidget Class
	 */
	class iHomefinderLinkWidget extends WP_Widget {
		
	    /** constructor */
	    function iHomefinderLinkWidget() {
	    	$options=array('description'=>'Displays a list of Homes For Sale links in the choosen cities from Themes Options - SEO indexing tool.');
	        parent::WP_Widget( false,
	                           $name = 'Optima Express SEO City Links',
	                           $widget_options=$options );
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {
	        $linkWidth=get_option(IHomefinderConstants::SE0_CITY_LINK_WIDTH,'80');
	    	echo $before_widget;
           	echo $before_title;

	   		$linkArray=get_option(IHomefinderConstants::SE0_CITY_LINKS_SETTINGS);	
	   		
			
			echo("<div>");
			foreach($linkArray as $link ){
    			
		    	//create link
		    	$linkText=$link[IHomefinderConstants::SE0_CITY_LINKS_TEXT];
		    	$cityZip=$link[IHomefinderConstants::SE0_CITY_LINKS_CITY_ZIP];
		    	$propertyType=$link[IHomefinderConstants::SE0_CITY_LINKS_PROPERTY_TYPE];
		    	$minPrice=$link[IHomefinderConstants::SE0_CITY_LINKS_MIN_PRICE];
		    	$maxPrice=$link[IHomefinderConstants::SE0_CITY_LINKS_MAX_PRICE];
		    	
		    	if( !empty($linkText)){
			    	$searchLinkInfo=new iHomefinderSearchLinkInfo($linkText, $cityZip, $propertyType, $minPrice, $maxPrice);
			    	$linkUrl= $this->createLinkUrl($searchLinkInfo);		
					?>
					<div class="ihf-seo-link" style="width: <?php echo($linkWidth)?>px;">
						<a href="<?php echo($linkUrl)?>"><?php echo($searchLinkInfo->getLinkText())?></a>
					</div>              
		    		<?php 		    		
		    	}
		    	
		    }//foreach loop  
			
           	echo("</div>");
           	
           	echo $after_title;
           	echo $after_widget;
	    }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	    function update($new_instance, $old_instance) {
			$instance = $new_instance;
	        return $instance;
	    }

	    /**
	     * Create the admin form, for adding the Widget to the blog.
	     *
	     *  @see WP_Widget::form
	     */
	    function form($instance) {
	    	$cityLinksConfigurationUrl=site_url();
	    	$cityLinksConfigurationUrl .= "/wp-admin/admin.php?page=ihf-seo-city-links-page";
	    	echo("<a href='" . $cityLinksConfigurationUrl . "'>Configure City Links</a>");
	    }
	    
	    private function createLinkUrl($searchLinkInfo ){
	    	//link to all featured listings
           	$linkUrl = IHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "cityZip", $searchLinkInfo->getCityZip() );
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "propertyType", $searchLinkInfo->getPropertyType() );
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $searchLinkInfo->getMinPrice() );
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $searchLinkInfo->getMaxPrice() );
           	return $linkUrl;
	    }
	    

         
	} // class iHomefinderQuickSearchWidget
}//end if( !class_exists('iHomefinderQuickSearchWidget'))

if( !class_exists('iHomefinderSearchLinkInfo')) {
	class iHomefinderSearchLinkInfo {
		private $linkText;
		private $cityZip;
		private $propertyTyp;
		private $minPrice;
		private $maxPrice;
		private $zip;

		public function __construct($linkText,$cityZip, $propertyType, $minPrice, $maxPrice ){
			$this->linkText=$linkText;
			$this->cityZip=$cityZip;
			$this->propertyType=$propertyType ;
			$this->minPrice=$minPrice;
			$this->maxPrice=$maxPrice;
		}
		
		public function getLinkText(){
			return $this->linkText ;
		}
		public function getCityZip(){
			return $this->cityZip ;
		}
		public function getPropertyType(){
			return $this->propertyType;
		}
		public function getMinPrice(){
			return $this->minPrice;
		}
		public function getMaxPrice(){
			return $this->maxPrice;
		}
		
   		public static function compare($a, $b){
        	$al = strtolower($a->linkText);
        	$bl = strtolower($b->linkText);
        	if ($al == $bl) {
            	return 0;
        	}
        	return ($al > $bl) ? +1 : -1;
    	}		
		
	}
}
?>
