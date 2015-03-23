<?php

/**
 * iHomefinderLinkWidget Class
 */
class iHomefinderLinkWidget extends WP_Widget {
	
	public function __construct() {
		$options = array(
			'description' => 'Displays a list of Homes For Sale links in the choosen cities from Themes Options - SEO indexing tool.'
		);
		parent::WP_Widget(
			false,
			$name = 'IDX: SEO City Links',
			$widget_options = $options
		);
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {
		$linkWidth=get_option(iHomefinderConstants::SEO_CITY_LINK_WIDTH,'80');
		//sets vars like $before_widget from $args
		extract($args);
		
		echo $before_widget;
		echo $before_title;

		$linkArray = get_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS);	
		
		if(!empty($linkArray)) {
			echo("<div>");
			foreach($linkArray as $link) {
				
				//create link
				$linkText=$link[iHomefinderConstants::SEO_CITY_LINKS_TEXT];
				$cityZip=$link[iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP];
				$propertyType=$link[iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE];
				$minPrice=$link[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE];
				$maxPrice=$link[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE];
				
				if(!empty($linkText)) {
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
		}
		
		echo $after_title;
		echo $after_widget;
	}

	/**
	 *  Processes form submission in the admin area for configuring
	 *  the widget.
	 *
	 *  @see WP_Widget::update
	 */
	public function update($new_instance, $old_instance) {
		$instance = $new_instance;
		return $instance;
	}

	/**
	 * Create the admin form, for adding the Widget to the blog.
	 *
	 *  @see WP_Widget::form
	 */
	public function form($instance) {
		$cityLinksConfigurationUrl = site_url();
		$cityLinksConfigurationUrl .= "/wp-admin/admin.php?page=ihf-seo-city-links-page";
		echo "<a href='" . $cityLinksConfigurationUrl . "'>Configure City Links</a>";
	}
	
	private function createLinkUrl($searchLinkInfo) {
		//link to all featured listings
		$linkUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
		//$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "cityZip", $searchLinkInfo->getCityZip());
		
		if($searchLinkInfo->hasPostalCode()) {
			$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "zip", $searchLinkInfo->getPostalCode());
		}
		else{
			$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "city", $searchLinkInfo->getCity());
		}  	
		if($searchLinkInfo->hasState()) {
			$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "state", $searchLinkInfo->getState());
		}
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "propertyType", $searchLinkInfo->getPropertyType());
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $searchLinkInfo->getMinPrice());
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $searchLinkInfo->getMaxPrice());
					
		return $linkUrl;
	}  
}
