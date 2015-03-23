<?php

if( !class_exists('iHomefinderHotsheetListWidget') ) {

	/**
	 * iHomefinderHotsheetListWidget Class
	 */
	class iHomefinderHotsheetListWidget extends WP_Widget {
	
	private $contextUtility;
	private $cacheUtility;
	
	public function __construct() {
		$options=array('description'=>'List of Saved Search Pages');
		parent::WP_Widget(
			false,
			$name = 'IDX: Saved Search Page List',
			$widget_options=$options
		);
		$this->contextUtility=IHomefinderWidgetContextUtility::getInstance();
		$this->cacheUtility = new IHomefinderCacheUtility();
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {
		
		global $blog_id;
		global $post;
		
		if( $this->contextUtility->isEnabled( $instance )) {
		
			$includeAll = filter_var($instance['includeAll'], FILTER_VALIDATE_BOOLEAN);
			
			$before_widget = $args["before_widget"];
			$after_widget = $args["after_widget"];
			$before_title = $args["before_title"];
			$after_title = $args["after_title"];
			
			$title = apply_filters('widget_title', $instance['title']);
			
			$content = $this->cacheUtility->getItem($this->id);
			if( empty($content)){
				$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
				$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=hotsheet-list' ;
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
				if( $includeAll === false &&
					array_key_exists("hotsheetIds", $instance) &&
					is_array($instance["hotsheetIds"])
				) {
					foreach( $instance["hotsheetIds"] as $index => $hotsheetId ) {
						$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "hotsheetIds", $hotsheetId );
					}
				}
				iHomefinderLogger::getInstance()->debug("url: " . $ihfUrl);
				$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
				$content = (string) $contentInfo->view;
				$this->cacheUtility->updateItem( $this->id, $content, 3600 );
			}
			
			echo $before_widget;
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			
			if( IHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()){
				echo "<br/>" ;	
				echo $content;
				echo "<br/>" ;
			} else {
				echo $content;
			}
			
			echo $after_widget;
		}
	}
	
	/**
	 *  Processes form submission in the admin area for configuring
	 *  the widget.
	 *
	 *  @see WP_Widget::update
	 */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['hotsheetIds'] = $new_instance['hotsheetIds'];
		$instance['includeAll'] = $new_instance['includeAll'];
		
		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);
		
		//delete the cached item
		$this->cacheUtility->deleteItem( $this->id );
		
		return $instance;
	}

    /**
     * Create the admin form, for adding the Widget to the blog.
     *
     *  @see WP_Widget::form
     */
    public function form($instance) {
		
		$title = esc_attr($instance['title']);
		$hotsheetIds = $instance['hotsheetIds'];
		
		$includeAll = true;
		if( $instance['includeAll'] !== null ) {
			$includeAll = filter_var($instance['includeAll'], FILTER_VALIDATE_BOOLEAN);
		}
		
		$galleryFormData = IHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$clientHotsheets = $galleryFormData->getHotsheetList();
		
		?>
		<p>
			<?php _e('Title:'); ?>
			<input
			class="widefat"
			id="<?php echo $this->get_field_id('title'); ?>"
			name="<?php echo $this->get_field_name('title'); ?>"
			type="text"
			value="<?php echo $title; ?>"
			/>
		</p>
		<p>
			<?php
			$includeAllTrueChecked = "";
			$includeAllFalseChecked = "";
			if( $includeAll === true ) {
				$includeAllTrueChecked = "checked=\"checked\"";
			} else {
				$includeAllFalseChecked = "checked=\"checked\"";
			}
			?>
			<label>
				<input
				type="radio"
				name="<?php echo $this->get_field_name('includeAll'); ?>"
				value="true"
				onclick="jQuery(this).closest('form').find('.hotsheetList').hide()"
				<?php echo $includeAllTrueChecked ?>
				/>
				Show all Saved Search Pages
			</label>
			<br />
			<label>
				<input
				type="radio"
				name="<?php echo $this->get_field_name('includeAll'); ?>"
				value="false"
				onclick="jQuery(this).closest('form').find('.hotsheetList').show()"
				<?php echo $includeAllFalseChecked ?>
				/>
				Show Selected Saved Search Pages
			</label>
		</p>
		<?php
		$hotsheetListStyle = "";
		if($includeAll) {
			$hotsheetListStyle = "display: none;";
		}
		?>
		<p
		class="hotsheetList"
		style="<?php echo $hotsheetListStyle ?>"
		>
			<label>Saved Search Pages:</label>
			<select
			class="widefat"
			name="<?php echo $this->get_field_name('hotsheetIds'); ?>[]"
			multiple="multiple">
				<?php
				foreach ($clientHotsheets as $index => $clientHotsheet) {
					$hotsheetIdSelected = "";
					if( is_array($hotsheetIds) && in_array($clientHotsheet->hotsheetId, $hotsheetIds) ) {
						$hotsheetIdSelected = "selected=\"selected\"";
					}
					?>
					<option value="<?php echo $clientHotsheet->hotsheetId ?>" <?php echo $hotsheetIdSelected ?>>
						<?php echo $clientHotsheet->displayName ?>
					</option>
					<?php
				}
				?>
			</select>
		</p>
		<?php
		$this->contextUtility->getPageSelector($this, $instance, IHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE );
	}
	
  }
  
}
?>