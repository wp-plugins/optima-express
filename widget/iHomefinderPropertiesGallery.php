<?php
if( !class_exists('iHomefinderPropertiesGallery')) {
	/**
	 * iHomefinderPropertiesGallery Class
	 */
	class iHomefinderPropertiesGallery extends WP_Widget {
		
		private $contextUtility ;
		
	    /** constructor */
	    function iHomefinderPropertiesGallery() {
	    	$options=array('description'=>'Display a list of properties.');
	        parent::WP_Widget( false,
	                           $name = 'Optima Express Property Gallery',
	                           $widget_options=$options  );
			$this->contextUtility=IHomefinderWidgetContextUtility::getInstance() ; 	                           
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {

	    	if( $this->contextUtility->isEnabled($instance)){
	    		$galleryType = $instance['galleryType'];
	    		switch ($galleryType) {
	    			case 'hotSheet':
	    				$this->hotSheet($args, $instance);
	    				break;
	    			case 'featuredListing':
	    				$this->featuredListing($args, $instance);
	    				break;
	    			case 'namedSearch':
	    				$this->namedSearch($args, $instance);
	    				break;
	    			case 'linkSearch':
	    				$this->linkSearch($args, $instance);
	    				break;

	    		}
	    	}

	    }


         /**
          * Get a cached version of the widget output.
          * @param $instance
          */
         function getCachedVersion(){
             $cacheKey=$this->getCacheKey();
             IHomefinderLogger::getInstance()->debug( 'get cached version cacheKey ' . $cacheKey );
             // Fetch a saved transient
             $propertyGalleryContent = get_transient($cacheKey);
             return $propertyGalleryContent   ;
         }

		 function getCacheKey( ){
	    	$widgetId=$this->id;
        	$cacheKey=iHomefinderConstants::PROPERTY_GALLERY_CACHE . "_" .  $widgetId;
        	$cacheKey=md5($cacheKey);
        	IHomefinderLogger::getInstance()->debug('get $cacheKey ' . $cacheKey) ;
        	return $cacheKey;
        }

         function updateCache( $propertyGalleryContent ){
         	$cacheKey=$this->getCacheKey();
			IHomefinderLogger::getInstance()->debug( 'updating cache cacheKey ' . $cacheKey );
         	set_transient($cacheKey, $propertyGalleryContent, IHomefinderConstants::PROPERTY_GALLERY_CACHE_TIMEOUT);
         }

         function hotSheet($args, $instance) {
         	global $blog_id;
         	global $post;

         	if( IHomefinderPermissions::getInstance()->isHotSheetEnabled()){
         		$currentPageId = $post->ID;
         		extract( $args );
         		$title = apply_filters('widget_title', $instance['name']);
         		$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
         		$hotSheetId  = esc_attr($instance['hotSheetId']) ;
         		$linkText = esc_attr($instance['linkText']);

         		//link to all listings in the hotsheet
         		$nameInUrl=preg_replace("[^A-Za-z0-9-]", "-", $title) ;

         		$nameInUrl=str_replace(" ", "-", $nameInUrl) ;

         		$linkUrl =  IHomefinderUrlFactory::getInstance()->getHotsheetSearchResultsUrl(true) . '/' . $nameInUrl . '/'.$hotSheetId ;

         		$propertyGalleryContent = $this->getCachedVersion($instance);
         		if( empty($propertyGalleryContent)){
         			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
         			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=hotsheet-results' ;
         			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", 1);
         			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
         			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "numListingsLimit", $numberOfListingsToDisplay );
         			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "hotSheetId", $hotSheetId );
         			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
         			iHomefinderLogger::getInstance()->debug("hotsheet url: " . $ihfUrl);
         			$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
         			$propertyGalleryContent = $contentInfo->view;

         			$this->updateCache($propertyGalleryContent);
         		}
         		echo $before_widget;
         		if ( $title ){
         			echo $before_title . $title . $after_title;

         		}
         		echo "<br/>" . $propertyGalleryContent . "<br/>";
         		echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
         		echo $after_widget;
         	}
         }

         function featuredListing($args, $instance) {
             global $blog_id;
             global $post;

             if( IHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()){
             	$currentPageId = $post->ID;
             	extract( $args );
             	$title = apply_filters('widget_title', $instance['name']);
             	$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
             	$linkText = esc_attr($instance['linkText']);

             	//link to all featured properties
             	$linkUrl = IHomefinderUrlFactory::getInstance()->getFeaturedSearchResultsUrl(true) ;

             	$propertyGalleryContent = $this->getCachedVersion($instance);

             	if( empty($propertyGalleryContent)){
             		IHomefinderLogger::getInstance()->debug( ' Featured Listings Widget NOT CACHED' );
             		$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
             		$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=featured-search' ;
             		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", 1);
             		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
             		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "numListingsLimit", $numberOfListingsToDisplay );
             		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
             		$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
             		$propertyGalleryContent = $contentInfo->view;
             		$this->updateCache($propertyGalleryContent);
             	}
             	echo $before_widget;
             	if ( $title ){
             		echo $before_title . $title . $after_title;
             	}
             	echo "<br/>" . $propertyGalleryContent . "<br/>";
             	echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
             	echo $after_widget;
             }
         }

         function linkSearch($args, $instance) {
           global $blog_id;
           global $post;

           if( IHomefinderPermissions::getInstance()->isLinkSearchEnabled()){
           	$title = apply_filters('widget_title', $instance['name']);

           	extract( $args );
           	$cityId = esc_attr($instance['cityId']);
           	$bed = esc_attr($instance['bed']);
           	$bath = esc_attr($instance['bath']);
           	$minPrice = esc_attr($instance['minPrice']);
           	$maxPrice = esc_attr($instance['maxPrice']);
           	$propertyType = esc_attr($instance['propertyType']);
           	$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
           	$linkText = esc_attr($instance['linkText']);

           	//link to all featured listings
           	$linkUrl = IHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "cityID", $cityId);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "propertyType", $propertyType);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "bedrooms", $bed);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "bathcount", $bath);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $minPrice);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $maxPrice);

           	echo $before_widget;
           	echo $before_title;
           	echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
           	echo $after_title;
           	echo $after_widget;
           }
         }


         function namedSearch($args, $instance) {
           global $blog_id;
           global $post;

           if( IHomefinderPermissions::getInstance()->isNamedSearchEnabled()){
           	$title = apply_filters('widget_title', $instance['name']);

           	extract( $args );
           	$cityId = esc_attr($instance['cityId']);
           	$bed = esc_attr($instance['bed']);
           	$bath = esc_attr($instance['bath']);
           	$minPrice = esc_attr($instance['minPrice']);
           	$maxPrice = esc_attr($instance['maxPrice']);
           	$propertyType = esc_attr($instance['propertyType']);
           	$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
           	$linkText = esc_attr($instance['linkText']);

           	//link to all featured listings
           	$linkUrl = IHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true) ;
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "cityID", $cityId);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "propertyType", $propertyType);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "bedrooms", $bed);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "bathcount", $bath);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $minPrice);
           	$linkUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $maxPrice);

           	$propertyGalleryContent = $this->getCachedVersion($instance);
           	if( empty($propertyGalleryContent)){
           		$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();

           		$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=listing-search-results' ;
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "cityID", $cityId);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bedrooms", $bed);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bathcount", $bath);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "minListPrice", $minPrice);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "maxListPrice", $maxPrice);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "propertyType", $propertyType);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "numListingsLimit", $numberOfListingsToDisplay );
           		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", true);

           		$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
           		$propertyGalleryContent = $contentInfo->view;
           		$this->updateCache($propertyGalleryContent);
           	}

           	echo $before_widget;
           	if ( $title ){
           		echo $before_title . $title . $after_title;

           	}
           	echo "<br/>" . $propertyGalleryContent . "<br/>";
           	echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
           	echo $after_widget;
           }
         }

         function getGalleryFormData(){
            $authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
            $ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=search-form-lists&authenticationToken=' .  $authenticationToken ;
            $galleryFormData = iHomefinderRequestor::remoteRequest($ihfUrl);
            return $galleryFormData;
         }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	    function update($new_instance, $old_instance) {    	
                $instance = $old_instance;
                $instance['galleryType'] = strip_tags(stripslashes($new_instance['galleryType']));
                $instance['listingID'] = strip_tags(stripslashes($new_instance['listingID']));
                $instance['name'] = strip_tags(stripslashes($new_instance['name']));
                $instance['propertiesShown'] = strip_tags(stripslashes($new_instance['propertiesShown']));
                $instance['cityId'] = strip_tags(stripslashes($new_instance['cityId']));
                $instance['propertyType'] = strip_tags(stripslashes($new_instance['propertyType']));
                $instance['bed'] = strip_tags(stripslashes($new_instance['bed']));
                $instance['bath'] = strip_tags(stripslashes($new_instance['bath']));
                $instance['minPrice'] = strip_tags(stripslashes($new_instance['minPrice']));
                $instance['maxPrice'] = strip_tags(stripslashes($new_instance['maxPrice']));
                $instance['hotSheetId'] = strip_tags(stripslashes($new_instance['hotSheetId']));
                $instance['linkText'] = strip_tags(stripslashes($new_instance['linkText']));
                
				$instance = $this->contextUtility->updateContext($new_instance, $instance);
				
                $cacheKey=$this->getCacheKey();
                delete_transient($cacheKey);

	        return $instance;
	    }

	    /**
	     * Create the admin form, for adding the Widget to the blog.
	     *
	     *  @see WP_Widget::form
	     */
	    function form($instance) {
	    		    	
                $galleryType = ($instance) ? esc_attr($instance['galleryType']) : '';
                $listingID = ($instance) ? esc_attr($instance['listingID']) : '';
                $name = ($instance) ? esc_attr($instance['name']) : '';
                $propertiesShown = ($instance) ? esc_attr($instance['propertiesShown']) : '3';
                $cityId = ($instance) ? esc_attr($instance['cityId']) : '';
				$propertyType = ($instance) ? esc_attr($instance['propertyType']) : '';
                $bed = ($instance) ? esc_attr($instance['bed']) : '';
                $bath = ($instance) ? esc_attr($instance['bath']) : '';
                $minPrice = ($instance) ? esc_attr($instance['minPrice']) : '';
                $maxPrice = ($instance) ? esc_attr($instance['maxPrice']) : '';
                $hotSheetId = ($instance) ? esc_attr($instance['hotSheetId']) : '';
                $linkText = ($instance) ? esc_attr($instance['linkText']) : 'View all';

                $galleryFormData = $this->getGalleryFormData();
                $hotsheetsList=$galleryFormData->hotsheetsList ;
                $citiesList=$galleryFormData->citiesList ;
                $propertyTypesList=$galleryFormData->propertyTypesList ;
	        ?>
	        


           <script type="text/javascript">
                function togglePropertyFormFields( current_radio ) {
                    if ( current_radio == 'hotSheet') {
                    	jQuery('div.widgetName').show();
                    	jQuery('div.linkText').show();
                        jQuery('div.hotSheet').show();
                        jQuery('div.numberProperties').show();
                        jQuery('div.namedSearch').hide();
                    }
                    else if ( current_radio == 'namedSearch') {
                    	jQuery('div.widgetName').show();
                    	jQuery('div.linkText').show();
                    	jQuery('div.namedSearch').show();
                        jQuery('div.hotSheet').hide();
                        jQuery('div.numberProperties').show();
                    }
                    else if ( current_radio == 'linkSearch') {
                    	jQuery('div.widgetName').hide();
                    	jQuery('div.linkText').show();
                    	jQuery('div.namedSearch').show();
                        jQuery('div.hotSheet').hide();
                    	jQuery('div.numberProperties').hide();
                    }
                    else if ( current_radio == 'featuredListing') {
                    	jQuery('div.numberProperties').show();
                    	jQuery('div.widgetName').show();
                    	jQuery('div.linkText').show();
                    	jQuery('div.namedSearch').hide();
                        jQuery('div.hotSheet').hide();
                    }
                }
            </script>


            <div>
                Gallery type:<br />
                <?php
                    //set selected gallery type
                	if( $galleryType == null || $galleryType == "" ){
                		if( IHomefinderPermissions::getInstance()->isNamedSearchEnabled()){
                			$galleryType="namedSearch";
                		}
                		else if(IHomefinderPermissions::getInstance()->isLinkSearchEnabled()){
                			$galleryType="linkSearch";
                		}
                		else if(IHomefinderPermissions::getInstance()->isHotSheetEnabled()){
                			$galleryType="hotSheet";
                		}
                		else if(IHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()){
                			$galleryType="featuredListing";
                		}
                		else{
                			$galleryType="";
                		}
                	}
                ?>


                <?php if( IHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()){ ?>
                	<label><input onclick="togglePropertyFormFields(this.value);" <?php if( $galleryType == 'featuredListing' ) echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="featuredListing" name="<?php echo $this->get_field_name( 'galleryType' ); ?>" /> Featured Properties Gallery</label><br/>
                <?php }?>
                <?php if( IHomefinderPermissions::getInstance()->isHotSheetEnabled()){ ?>
                	<label><input onclick="togglePropertyFormFields(this.value);" <?php if( $galleryType == 'hotSheet' ) echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="hotSheet" name="<?php echo $this->get_field_name( 'galleryType' ); ?>" /> Top Picks Gallery</label><br />
                <?php }?>
                <?php if( IHomefinderPermissions::getInstance()->isNamedSearchEnabled()){ ?>
                	<label><input onclick="togglePropertyFormFields(this.value);" <?php if( $galleryType == 'namedSearch' ) echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="namedSearch" name="<?php echo $this->get_field_name( 'galleryType' ); ?>" /> Saved Search Gallery</label><br />
                <?php }?>
                <?php if( IHomefinderPermissions::getInstance()->isLinkSearchEnabled()){ ?>
                	<label><input onclick="togglePropertyFormFields(this.value);" <?php if( $galleryType == 'linkSearch' ) echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="linkSearch" name="<?php echo $this->get_field_name( 'galleryType' ); ?>" /> Saved Search Link</label>
                <?php }?>
            </div>

			<div id="widgetName" class="widgetName" <?php if( $galleryType == 'linkSearch' ) echo 'style="display:none;"'; ?>>
            	<label>Gallery Title:</label>
                <input class="widefat" type="text" value="<?php echo $name; ?>" name="<?php echo $this->get_field_name( 'name' ) ; ?>" />
            </div>

            <div id="numberProperties" class="numberProperties" <?php if( $galleryType == 'linkSearch' ) echo 'style="display:none;"'; ?>>
                <label>Number of Properties Shown:</label>
                <select name="<?php echo $this->get_field_name( 'propertiesShown' ); ?>">
                <?php
	    			for ( $i=1; $i<11; $i+=1) {
    					echo "<option value='" . $i  . "'";
    					if( $propertiesShown == $i ){
    						echo " selected='true'";
    					}
    					echo ">" . $i . "</option>" ;
				}
				?>
                </select>
			</div>
       		<div id="linkText" class="linkText">
                <label>Link Text:</label>
                <input class="widefat" type="text" value="<?php echo $linkText; ?>" name="<?php echo $this->get_field_name( 'linkText' ); ?>" />
			</div>

            <div id="hotSheet" class="hotSheet" <?php if( $galleryType != 'hotSheet' ) echo 'style="display:none;"'; ?>>
                <label>Top Picks:</label>
                <select name="<?php echo $this->get_field_name('hotSheetId'); ?>">
                <?php
	    			foreach ($hotsheetsList as $i => $value) {
    					echo "<option value='" . $hotsheetsList[$i]->hotsheetId . "'";
    					if( $hotsheetsList[$i]->hotsheetId == $hotSheetId ){
    						echo " selected='true'";
    					}
    					echo ">" . $hotsheetsList[$i]->displayName . "</option>" ;
				}
				?>
                </select>
            </div>

            <div id="namedSearch" class="namedSearch" <?php if( $galleryType != 'namedSearch' && $galleryType != 'linkSearch' ) echo 'style="display:none;"'; ?>>
                <label>City:</label><br/>
                <select name="<?php echo $this->get_field_name('cityId'); ?>" size="5" style="height: 100px;">
                <?php
	    			foreach ($citiesList as $i => $value) {
    					echo "<option value='" . $citiesList[$i]->cityId . "'";
    					if( $citiesList[$i]->cityId == $cityId ){
    						echo " selected='true'";
    					}
    					echo ">" . $citiesList[$i]->displayName . "</option>" ;
				}
				?>
                </select>
                <br/>
                <label>Property Type:</label><br/>
                <select name="<?php echo $this->get_field_name('propertyType'); ?>" >
                <?php
	    			foreach ($propertyTypesList as $i => $value) {
    					echo"<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'";
	    			    if( $propertyTypesList[$i]->propertyTypeCode == $propertyType ){
    						echo " selected='true'";
    					}
    					echo ">" . $propertyTypesList[$i]->displayName . "</option>";
				}
				?>
                </select>
                <br/>
                <label>Bed:</label><br/>
                <input class="widefat" type="text" value="<?php echo $bed; ?>" name="<?php echo $this->get_field_name( 'bed' ); ?>" />
                <br/>
                <label>Bath:</label><br/>
                <input class="widefat" type="text" value="<?php echo $bath; ?>" name="<?php echo $this->get_field_name( 'bath' ); ?>" />
                <br/>
                <label>Minimum Price:</label><br/>
                <input class="widefat" type="text" value="<?php echo $minPrice; ?>" name="<?php echo $this->get_field_name( 'minPrice' ); ?>" />
                <br/>
                <label>Maximum Price:</label><br/>
                <input class="widefat" type="text" value="<?php echo $maxPrice; ?>" name="<?php echo $this->get_field_name( 'maxPrice' ); ?>" />
            </div>
            <?php 
	            //The following call echos a select context for pages to display.
    	        echo ( $this->contextUtility->getPageSelector($this, $instance, IHomefinderConstants::GALLERY_WIDGET_TYPE));
    	    ?>


            <?php
	    }

	} // class iHomefinderPropertiesGallery
}//end if( !class_exists('iHomefinderPropertiesGallery'))
?>
