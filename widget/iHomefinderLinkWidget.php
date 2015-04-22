<?php

/**
 * iHomefinderLinkWidget Class
 */
class iHomefinderLinkWidget extends WP_Widget {
	
	public function __construct() {
		$options = array(
			"description" => "Displays a list of Homes For Sale links in the choosen cities from Themes Options - SEO indexing tool."
		);
		parent::WP_Widget(
			false,
			$name = "IDX: SEO City Links",
			$widget_options = $options
		);
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {
		//sets vars like $before_widget from $args
		extract($args);
		
		echo $before_widget;
		echo $before_title;

		$linkArray = get_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS);	
		
		if(!empty($linkArray)) {
			?>
			<div>
			<?php
			foreach($linkArray as $link) {
				//create link
				$linkText = $link[iHomefinderConstants::SEO_CITY_LINKS_TEXT];
				$cityZip = $link[iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP];
				$propertyType = $link[iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE];
				$minPrice = $link[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE];
				$maxPrice = $link[iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE];
				
				if(!empty($linkText)) {
					$searchLinkInfo = new iHomefinderSearchLinkInfo($linkText, $cityZip, $propertyType, $minPrice, $maxPrice);
					$linkUrl = $this->createLinkUrl($searchLinkInfo);		
					?>
					<div class="ihf-seo-link">
						<a href="<?php echo $linkUrl ?>"><?php echo $searchLinkInfo->getLinkText() ?></a>
					</div>
					<?php
				}
				
			}
			?>
			</div>
			<?php
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
		?>
		<p>
			<a href="<?php echo $cityLinksConfigurationUrl ?>">Configure City Links</a>
		</p>
		<?php
	}
	
	private function createLinkUrl($searchLinkInfo) {
		$resultsUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
		$data = array();
		if($searchLinkInfo->hasPostalCode()) {
			$data["zip"] = $searchLinkInfo->getPostalCode();
		} else {
			$data["city"] = $searchLinkInfo->getCity();
		}  	
		if($searchLinkInfo->hasState()) {
			$data["state"] = $searchLinkInfo->getState();
		}
		$data["propertyType"] = $searchLinkInfo->getPropertyType();
		if($searchLinkInfo->getMinPrice() != null) {
			$data["minListPrice"] = $searchLinkInfo->getMinPrice();
		}
		if($searchLinkInfo->getMaxPrice() != null) {
			$data["maxListPrice"] = $searchLinkInfo->getMaxPrice();
		}
		$linkUrl = $resultsUrl . "?" . http_build_query($data);
		return $linkUrl;
	}  
}
