<?php
if( !class_exists('IHomefinderMenu')) {
	class IHomefinderMenu {
		
		private static $instance ;
		
		private $communityPagesMenuItemName = "Communities" ;
		private $defaultMenuName= "Optima Express";
		
		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderMenu();
			}
			return self::$instance;
		}		
		
		public function getOptimaExpressMenu(){
			$menuName= $this->defaultMenuName;
			$optimaExpressMenu = wp_get_nav_menu_object($menuName);
			return $optimaExpressMenu ;
		}
		
		public function getOptimaExpressMenuId(){
			$menuName= $this->defaultMenuName;
			$optimaExpressMenu = wp_get_nav_menu_object($menuName);
			return $optimaExpressMenu->term_id ;
		}		
		
		/**
		 * Creates or retrieves the Optima Express menu.  If the menu has
		 * not been created, it creates the menu with default values.
		 * 
		 * If the menu already exists, then we do not populate with the default urls,
		 * because the end user may have customized this menu.
		 * 
		 * @return Optima Express menu
		 */		
		public function updateOptimaExpressMenu(){
			$optimaExpressMenu = $this->getOptimaExpressMenu();
			if( !$optimaExpressMenu){
				$menuName= $this->defaultMenuName;
				$menuArgs = array(
					'description' => $menuName . '  default menu',
					'menu-name'   => $menuName );

				$optimaExpressMenuId=wp_update_nav_menu_object(0, $menuArgs);
				$optimaExpressMenu = wp_get_nav_menu_object($optimaExpressMenuId);
				$this->addOptimaExpressMenuItems($optimaExpressMenu->term_id);
			}

			return $optimaExpressMenu ;
		}
		
		/**
		 * 
		 * Get the menuItem object for Community Pages
		 * 
		 * This is a container menu item that is the parent for
		 * all Community Pages menu items
		 */
		private function getCommunityPagesContainer(){
			$optimaExpressMenu = $this->getOptimaExpressMenu();
			$args=array( 'title' => $this->communityPagesMenuItemName );
			$menuItems=wp_get_nav_menu_items($optimaExpressMenu->term_id, $args );
			foreach( $menuItems as $oneMenuItem){
				if( $oneMenuItem->title == $this->communityPagesMenuItemName ){
					return $oneMenuItem ;
				}
			}
			return false;
		}
		
		public function addCommunityPageMenuItem(){
			$optimaExpressMenu = $this->getOptimaExpressMenu();
			$communityPagesMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenu->term_id, $this->communityPagesMenuItemName, "" );
			return $communityPagesMenuItemId ;
		}
		
		public function addPageToCommunityPages($postId){
			 $menuID=IHomefinderMenu::getInstance()->getOptimaExpressMenuId() ;
			 
			 $communityPagesMenuItem=$this->getCommunityPagesContainer();
 		 	 $communityPagesMenuItemId=$communityPagesMenuItem->ID ;
			 
			 $itemData =  array(
	    		'menu-item-object-id' => $postId,
	    		'menu-item-parent-id' => $communityPagesMenuItemId,
	    		'menu-item-position'  => 2,
	    		'menu-item-object' => 'page',
	    		'menu-item-type'      => 'post_type',
	    		'menu-item-status'    => 'publish');
			 
			 wp_update_nav_menu_item($menuID, 0, $itemData);			
		}
		
		/**
		 * Returns an array of menu items that are children of
		 * the Community Pages menu item.
		 * 
		 * Used in admin to dispaly a list of Community Pages.
		 */
		public function getCommunityPagesMenuItems(){
			$communityPages = array();
			
			$optimaExpressMenu = $this->getOptimaExpressMenu();
			
			$communityPagesMenuItem=$this->getCommunityPagesContainer();
 		 	$communityPagesMenuItemId=$communityPagesMenuItem->ID ;
 		 	
 		 	
			
			$menu_items = wp_get_nav_menu_items($optimaExpressMenu->term_id);

		    //var_dump($communityPagesMenuItemId );
			
			//var_dump($menu_items );
			foreach ( (array) $menu_items as $key => $menu_item ) {
				//var_dump($menu_item);
				if( $menu_item->menu_item_parent == $communityPagesMenuItemId ){
					$communityPages[]=$menu_item;
				}
			}
 		 	return $communityPages ;
		}
		
		
		private function addOptimaExpressMenuItems($optimaExpressMenuId){

			//Home Link
			$homeMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Home', IHomefinderUrlFactory::getInstance()->getBaseUrl() );

			//Featured Listings Page
			$featuredMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Featured Listings', IHomefinderUrlFactory::getInstance()->getFeaturedSearchResultsUrl(true));
			
			//Property Search secion
			$findHomeMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Property Search', "#" );
			$searchMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Search', IHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true), $findHomeMenuItemId);

			if( IHomefinderPermissions::getInstance()->isMapSearchEnabled() ){	
				$mapSearchMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Map Search',  IHomefinderUrlFactory::getInstance()->getMapSearchFormUrl(true), $findHomeMenuItemId );
			}
				
			
			$openHomesMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Open Homes', IHomefinderUrlFactory::getInstance()->getOpenHomeSearchFormUrl(true), $findHomeMenuItemId);
			$advancedSearchMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Advanced Search', IHomefinderUrlFactory::getInstance()->getListingsAdvancedSearchFormUrl(true), $findHomeMenuItemId );			
			
			//Email Alerts			
			if( IHomefinderPermissions::getInstance()->isEmailUpdatesEnabled()){		
				if( IHomefinderPermissions::getInstance()->isOfficeEnabled()){
					//If office enabled, then add email alerts to the search menu.
					$mapSearchMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Email Alerts',  IHomefinderUrlFactory::getInstance()->getOrganizerEditSavedSearchUrl(true), $findHomeMenuItemId );
				}
				else{
					$advancedSearchMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Email Alerts', IHomefinderUrlFactory::getInstance()->getOrganizerEditSavedSearchUrl(true) );
				}
			}
			
			//Parent for Community Pages
			if( IHomefinderPermissions::getInstance()->isCommunityPagesEnabled()){		
				$communityPagesMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, $this->communityPagesMenuItemName, "#" );
			}
			
			//Buyers and Sellers section
			$buyersAndSellersMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Buyers & Sellers', "#");
			if( IHomefinderPermissions::getInstance()->isOrganizerEnabled() ){
				$valuationMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Property Organizer', IHomefinderUrlFactory::getInstance()->getOrganizerLoginUrl(true), $buyersAndSellersMenuItemId);
			}
			
			$valuationMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Valuation Request', IHomefinderUrlFactory::getInstance()->getValuationFormUrl(true), $buyersAndSellersMenuItemId);
			
			//Contact Page
			$contactMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Contact', IHomefinderUrlFactory::getInstance()->getContactFormUrl(true));
			
			if( IHomefinderPermissions::getInstance()->isOfficeEnabled()){
				//Add top level office linkg
				$officeListMenuItemId=$this->addOneOptimaExpressMenuItem($optimaExpressMenuId, 'Our Team', IHomefinderUrlFactory::getInstance()->getOfficeListUrl(true));
			}			
		}

		private function addOneOptimaExpressMenuItem( $menuId, $name, $url, $parentId=0 ){
			global $wpdb ;
			//We build relative URLs that start with a slash.
			$url=IHomefinderUrlFactory::getInstance()->makeRelativeUrl($url);
			$menuItem=$this->buildOptimaExpressMenuItem( $name, $url, $parentId ) ;
			$menuItemId=wp_update_nav_menu_item($menuId,0,$menuItem);
			//$wpdb->insert($wpdb->term_relationships, array("object_id" => $menuItemId, "term_taxonomy_id" => $menuId ), array("%d", "%d"));
			return $menuItemId ;
		}

		private function buildOptimaExpressMenuItem($name, $url, $parentId = 0  ){
			$menuItem = array(
				'menu-item-parent-id' => $parentId,
				'menu-item-type' => 'custom',
				'menu-item-title' => $name,
				'menu-item-url' => $url,
				'menu-item-attr-title' => $name,
				'menu-item-description' => $name,
				'menu-item-status' => 'publish'
			);
			return $menuItem;
		}
	}//end class
}//end if
?>