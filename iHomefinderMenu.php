<?php

class iHomefinderMenu {
	
	private static $instance;
	
	private $communityPagesMenuItemName = "Communities";
	private $defaultMenuName = "Optima Express";
	
	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderMenu();
		}
		return self::$instance;
	}		
	
	public function getMenu() {
		$menuName = $this->defaultMenuName;
		$menu = wp_get_nav_menu_object($menuName);
		return $menu;
	}
	
	public function getMenuId() {
		$menuName = $this->defaultMenuName;
		$menu = wp_get_nav_menu_object($menuName);
		return $menu->term_id;
	}		
	
	/**
	 * Creates or retrieves the Optima Express menu. If the menu has
	 * not been created, it creates the menu with default values.
	 * 
	 * If the menu already exists, then we do not populate with the default urls,
	 * because the end user may have customized this menu.
	 * 
	 * @return Optima Express menu
	 */		
	public function updateMenu() {
		$menu = $this->getMenu();
		if(!$menu) {
			$menuName = $this->defaultMenuName;
			$menuArgs = array(
				"description" => $menuName . " default menu",
				"menu-name" => $menuName);

			$menuId = wp_update_nav_menu_object(0, $menuArgs);
			$menu = wp_get_nav_menu_object($menuId);
			$this->addMenuItems($menu->term_id);
		}

		return $menu;
	}
	
	/**
	 * 
	 * Get the menuItem object for Community Pages
	 * 
	 * This is a container menu item that is the parent for
	 * all Community Pages menu items
	 */
	private function getCommunityPagesContainer() {
		$menu = $this->getMenu();
		$args = array("title" => $this->communityPagesMenuItemName);
		$menuItems = wp_get_nav_menu_items($menu->term_id, $args);
		foreach($menuItems as $oneMenuItem) {
			if($oneMenuItem->title == $this->communityPagesMenuItemName) {
				return $oneMenuItem;
			}
		}
		return false;
	}
	
	public function addCommunityPageMenuItem() {
		$menu = $this->getMenu();
		$communityPagesMenuItemId = $this->addMenuItem($menu->term_id, $this->communityPagesMenuItemName, "");
		return $communityPagesMenuItemId;
	}
	
	public function addPageToCommunityPages($postId) {
		 $menuID = iHomefinderMenu::getInstance()->getMenuId();
		 $communityPagesMenuItem = $this->getCommunityPagesContainer();
		 $communityPagesMenuItemId = $communityPagesMenuItem->ID;
		 $itemData = array(
			"menu-item-object-id" => $postId,
			"menu-item-parent-id" => $communityPagesMenuItemId,
			"menu-item-position" => 2,
			"menu-item-object" => "page",
			"menu-item-type" => "post_type",
			"menu-item-status" => "publish"
		 );
		 wp_update_nav_menu_item($menuID, 0, $itemData);			
	}
	
	/**
	 * Returns an array of menu items that are children of
	 * the Community Pages menu item.
	 * 
	 * Used in admin to display a list of Community Pages.
	 */
	public function getCommunityPagesMenuItems() {
		$communityPages = array();
		$menu = $this->getMenu();
		$communityPagesMenuItem = $this->getCommunityPagesContainer();
		$communityPagesMenuItemId = $communityPagesMenuItem->ID;
		$menu_items = (array) wp_get_nav_menu_items($menu->term_id);
		foreach ($menu_items as $key => $menu_item) {
			if($menu_item->menu_item_parent == $communityPagesMenuItemId) {
				$communityPages[] = $menu_item;
			}
		}
		return $communityPages;
	}
	
	
	private function addMenuItems($menuId) {

		//Home Link
		$homeMenuItemId = $this->addMenuItem($menuId, "Home", iHomefinderUrlFactory::getInstance()->getBaseUrl());

		//Featured Listings Page
		$featuredMenuItemId = $this->addMenuItem($menuId, "Featured Listings", iHomefinderUrlFactory::getInstance()->getFeaturedSearchResultsUrl(true));
		
		//Property Search secion
		$findHomeMenuItemId = $this->addMenuItem($menuId, "Property Search", "#");
		$searchMenuItemId = $this->addMenuItem($menuId, "Search", iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true), $findHomeMenuItemId);

		if(iHomefinderPermissions::getInstance()->isMapSearchEnabled()) {	
			$mapSearchMenuItemId = $this->addMenuItem($menuId, "Map Search", iHomefinderUrlFactory::getInstance()->getMapSearchFormUrl(true), $findHomeMenuItemId);
		}
		
		$openHomesMenuItemId = $this->addMenuItem($menuId, "Open Homes", iHomefinderUrlFactory::getInstance()->getOpenHomeSearchFormUrl(true), $findHomeMenuItemId);
		$advancedSearchMenuItemId = $this->addMenuItem($menuId, "Advanced Search", iHomefinderUrlFactory::getInstance()->getListingsAdvancedSearchFormUrl(true), $findHomeMenuItemId);			
		
		//Email Alerts			
		if(iHomefinderPermissions::getInstance()->isEmailUpdatesEnabled()) {		
			if(iHomefinderPermissions::getInstance()->isOfficeEnabled()) {
				//If office enabled, then add email alerts to the search menu.
				$mapSearchMenuItemId = $this->addMenuItem($menuId, "Email Alerts", iHomefinderUrlFactory::getInstance()->getOrganizerEditSavedSearchUrl(true), $findHomeMenuItemId);
			} else {
				$advancedSearchMenuItemId = $this->addMenuItem($menuId, "Email Alerts", iHomefinderUrlFactory::getInstance()->getOrganizerEditSavedSearchUrl(true));
			}
		}
		
		//Parent for Community Pages
		if(iHomefinderPermissions::getInstance()->isCommunityPagesEnabled()) {		
			$communityPagesMenuItemId = $this->addMenuItem($menuId, $this->communityPagesMenuItemName, "#");
		}
		
		//Buyers and Sellers section
		if(iHomefinderPermissions::getInstance()->isOrganizerEnabled() || iHomefinderPermissions::getInstance()->isValuationEnabled()) {
			$buyersAndSellersMenuItemId = $this->addMenuItem($menuId, "Buyers & Sellers", "#");
			if(iHomefinderPermissions::getInstance()->isOrganizerEnabled()) {
				$valuationMenuItemId = $this->addMenuItem($menuId, "Property Organizer", iHomefinderUrlFactory::getInstance()->getOrganizerLoginUrl(true), $buyersAndSellersMenuItemId);
			}
			if(iHomefinderPermissions::getInstance()->isValuationEnabled()) {
				$valuationMenuItemId = $this->addMenuItem($menuId, "Valuation Request", iHomefinderUrlFactory::getInstance()->getValuationFormUrl(true), $buyersAndSellersMenuItemId);
			}
		}
		
		//Contact Page
		if(iHomefinderPermissions::getInstance()->isContactFormEnabled()) {
			$contactMenuItemId = $this->addMenuItem($menuId, "Contact", iHomefinderUrlFactory::getInstance()->getContactFormUrl(true));
		}
		
		if(iHomefinderPermissions::getInstance()->isOfficeEnabled()) {
			//Add top level office linkg
			$officeListMenuItemId = $this->addMenuItem($menuId, "Our Team", iHomefinderUrlFactory::getInstance()->getOfficeListUrl(true));
		}			
	}

	private function addMenuItem($menuId, $name, $url, $parentId = 0) {
		//We build relative URLs that start with a slash.
		if($url !== "#") {
			$url = iHomefinderUrlFactory::getInstance()->makeRelativeUrl($url);
		}
		$menuItem = $this->buildMenuItem($name, $url, $parentId);
		$menuItemId = wp_update_nav_menu_item($menuId, 0, $menuItem);
		return $menuItemId;
	}

	private function buildMenuItem($name, $url, $parentId = 0) {
		$menuItem = array(
			"menu-item-parent-id" => $parentId,
			"menu-item-type" => "custom",
			"menu-item-title" => $name,
			"menu-item-url" => $url,
			"menu-item-attr-title" => $name,
			"menu-item-description" => $name,
			"menu-item-status" => "publish"
		);
		return $menuItem;
	}
}