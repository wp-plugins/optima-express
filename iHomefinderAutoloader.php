<?php

/**
 * autoloads iHomefinder classes
 */
class iHomefinderAutoloader {
	
	private static $instance;
	
	/*
	 * we store an array indexed by class name of class paths instead of using a PSR-4 autoloader
	 * because we want to support versions of PHP that don't support namespacing
	 */
	private $classes = array(
			//core files
			"iHomefinderAdmin" => "iHomefinderAdmin.php",
			"iHomefinderAjaxHandler" => "iHomefinderAjaxHandler.php",
			"iHomefinderConstants" => "iHomefinderConstants.php",
			"iHomefinderInstaller" => "iHomefinderInstaller.php",
			"iHomefinderListingInfo" => "iHomefinderListingInfo.php",
			"iHomefinderLogger" => "iHomefinderLogger.php",
			"iHomefinderMenu" => "iHomefinderMenu.php",
			"iHomefinderPermissions" => "iHomefinderPermissions.php",
			"iHomefinderEnqueueResource" => "iHomefinderEnqueueResource.php",
			"iHomefinderRequestor" => "iHomefinderRequestor.php",
			"iHomefinderRemoteResponse" => "iHomefinderRemoteResponse.php",
			"iHomefinderRewriteRules" => "iHomefinderRewriteRules.php",
			"iHomefinderSearchLinkInfo" => "iHomefinderSearchLinkInfo.php",
			"iHomefinderSearchFormFieldsUtility" => "iHomefinderSearchFormFieldsUtility.php",
			"iHomefinderFormData" => "iHomefinderFormData.php",
			"iHomefinderShortcodeSelector" => "iHomefinderShortcodeSelector.php",
			"iHomefinderShortcodeDispatcher" => "iHomefinderShortcodeDispatcher.php",
			"iHomefinderStateManager" => "iHomefinderStateManager.php",
			"iHomefinderSubscriber" => "iHomefinderSubscriber.php",
			"iHomefinderUrlFactory" => "iHomefinderUrlFactory.php",
			"iHomefinderUtility" => "iHomefinderUtility.php",
			"iHomefinderVirtualPageDispatcher" => "iHomefinderVirtualPageDispatcher.php",
			"iHomefinderVirtualPageFactory" => "iHomefinderVirtualPageFactory.php",
			"iHomefinderLayoutManager" => "iHomefinderLayoutManager.php",
			"iHomefinderCacheUtility" => "iHomefinderCacheUtility.php",
			"iHomefinderVariable" => "iHomefinderVariable.php",
			"iHomefinderVariableUtility" => "iHomefinderVariableUtility.php",
			"iHomefinderWidgetUtility" => "iHomefinderWidgetUtility.php",
			//widgets
			"iHomefinderPropertiesGallery" => "widget/iHomefinderPropertiesGallery.php",
			"iHomefinderQuickSearchWidget" => "widget/iHomefinderQuickSearchWidget.php",
			"iHomefinderLinkWidget" => "widget/iHomefinderLinkWidget.php",
			"iHomefinderSearchByAddressWidget" => "widget/iHomefinderSearchByAddressWidget.php",
			"iHomefinderSearchByListingIdWidget" => "widget/iHomefinderSearchByListingIdWidget.php",
			"iHomefinderContactFormWidget" => "widget/iHomefinderContactFormWidget.php",
			"iHomefinderMoreInfoWidget" => "widget/iHomefinderMoreInfoWidget.php",
			"iHomefinderAgentBioWidget" => "widget/iHomefinderAgentBioWidget.php",
			"iHomefinderSocialWidget" => "widget/iHomefinderSocialWidget.php",
			"iHomefinderHotsheetListWidget" => "widget/iHomefinderHotsheetListWidget.php",
			//virtual pages
			"iHomefinderVirtualPageInterface" => "virtualPage/iHomefinderVirtualPageInterface.php",
			"iHomefinderAbstractVirtualPage" => "virtualPage/iHomefinderAbstractVirtualPage.php",
			"iHomefinderDefaultVirtualPageImpl" => "virtualPage/iHomefinderDefaultVirtualPageImpl.php",
			"iHomefinderFeaturedSearchVirtualPageImpl" => "virtualPage/iHomefinderFeaturedSearchVirtualPageImpl.php",
			"iHomefinderHotsheetVirtualPageImpl" => "virtualPage/iHomefinderHotsheetVirtualPageImpl.php",
			"iHomefinderHotsheetListVirtualPageImpl" => "virtualPage/iHomefinderHotsheetListVirtualPageImpl.php",
			"iHomefinderAdvancedSearchFormVirtualPageImpl" => "virtualPage/iHomefinderAdvancedSearchFormVirtualPageImpl.php",
			"iHomefinderSearchFormVirtualPageImpl" => "virtualPage/iHomefinderSearchFormVirtualPageImpl.php",
			"iHomefinderMapSearchVirtualPageImpl" => "virtualPage/iHomefinderMapSearchVirtualPageImpl.php",
			"iHomefinderQuickSearchFormVirtualPageImpl" => "virtualPage/iHomefinderQuickSearchFormVirtualPageImpl.php",
			"iHomefinderSearchResultsVirtualPageImpl" => "virtualPage/iHomefinderSearchResultsVirtualPageImpl.php",
			"iHomefinderListingDetailVirtualPageImpl" => "virtualPage/iHomefinderListingDetailVirtualPageImpl.php",
			"iHomefinderListingSoldDetailVirtualPageImpl" => "virtualPage/iHomefinderListingSoldDetailVirtualPageImpl.php",
			"iHomefinderOrganizerLoginFormVirtualPageImpl" => "virtualPage/iHomefinderOrganizerLoginFormVirtualPageImpl.php",
			"iHomefinderOrganizerLogoutVirtualPageImpl" => "virtualPage/iHomefinderOrganizerLogoutVirtualPageImpl.php",
			"iHomefinderOrganizerLoginSubmitVirtualPageImpl" => "virtualPage/iHomefinderOrganizerLoginSubmitVirtualPageImpl.php",
			"iHomefinderOrganizerEditSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSavedSearchVirtualPageImpl.php",
			"iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl.php",
			"iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl.php",
			"iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl.php",
			"iHomefinderOrganizerViewSavedSearchVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedSearchVirtualPageImpl.php",
			"iHomefinderOrganizerViewSavedSearchListVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedSearchListVirtualPageImpl.php",
			"iHomefinderOrganizerViewSavedListingListVirtualPageImpl" => "virtualPage/iHomefinderOrganizerViewSavedListingListVirtualPageImpl.php",
			"iHomefinderOrganizerDeleteSavedListingVirtualPageImpl" => "virtualPage/iHomefinderOrganizerDeleteSavedListingVirtualPageImpl.php",
			"iHomefinderOrganizerResendConfirmationVirtualPageImpl" => "virtualPage/iHomefinderOrganizerResendConfirmationVirtualPageImpl.php",
			"iHomefinderOrganizerActivateSubscriberVirtualPageImpl" => "virtualPage/iHomefinderOrganizerActivateSubscriberVirtualPageImpl.php",
			"iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl" => "virtualPage/iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl.php",
			"iHomefinderOrganizerHelpVirtualPageImpl" => "virtualPage/iHomefinderOrganizerHelpVirtualPageImpl.php",
			"iHomefinderOrganizerEditSubscriberVirtualPageImpl" => "virtualPage/iHomefinderOrganizerEditSubscriberVirtualPageImpl.php",
			"iHomefinderContactFormVirtualPageImpl" => "virtualPage/iHomefinderContactFormVirtualPageImpl.php",
			"iHomefinderValuationFormVirtualPageImpl" => "virtualPage/iHomefinderValuationFormVirtualPageImpl.php",
			"iHomefinderOpenHomeSearchFormVirtualPageImpl" => "virtualPage/iHomefinderOpenHomeSearchFormVirtualPageImpl.php",
			"iHomefinderSoldFeaturedListingVirtualPageImpl" => "virtualPage/iHomefinderSoldFeaturedListingVirtualPageImpl.php",
			"iHomefinderSupplementalListingVirtualPageImpl" => "virtualPage/iHomefinderSupplementalListingVirtualPageImpl.php",
			"iHomefinderOfficeListVirtualPageImpl" => "virtualPage/iHomefinderOfficeListVirtualPageImpl.php",
			"iHomefinderOfficeDetailVirtualPageImpl" => "virtualPage/iHomefinderOfficeDetailVirtualPageImpl.php",
			"iHomefinderAgentListVirtualPageImpl" => "virtualPage/iHomefinderAgentListVirtualPageImpl.php",
			"iHomefinderAgentDetailVirtualPageImpl" => "virtualPage/iHomefinderAgentDetailVirtualPageImpl.php",
			"iHomefinderAgentOrOfficeListingsVirtualPageImpl" => "virtualPage/iHomefinderAgentOrOfficeListingsVirtualPageImpl.php",
			"iHomefinderAbstractPropertyOrganizerVirtualPage" => "virtualPage/iHomefinderAbstractPropertyOrganizerVirtualPage.php",
			//admin pages
			"iHomefinderAdminAbstractPage" => "adminPage/iHomefinderAdminAbstractPage.php",
			"iHomefinderAdminPageInterface" => "adminPage/iHomefinderAdminPageInterface.php",
			"iHomefinderAdminInformation" => "adminPage/iHomefinderAdminInformation.php",
			"iHomefinderAdminActivate" => "adminPage/iHomefinderAdminActivate.php",
			"iHomefinderAdminControlPanel" => "adminPage/iHomefinderAdminControlPanel.php",
			"iHomefinderAdminPageConfig" => "adminPage/iHomefinderAdminPageConfig.php",
			"iHomefinderAdminConfiguration" => "adminPage/iHomefinderAdminConfiguration.php",
			"iHomefinderAdminBio" => "adminPage/iHomefinderAdminBio.php",
			"iHomefinderAdminSocial" => "adminPage/iHomefinderAdminSocial.php",
			"iHomefinderAdminEmail" => "adminPage/iHomefinderAdminEmail.php",
			"iHomefinderAdminCommunityPages" => "adminPage/iHomefinderAdminCommunityPages.php",
			"iHomefinderAdminSeoCityLinks" => "adminPage/iHomefinderAdminSeoCityLinks.php"
	);
	
	private function __construct() {
		spl_autoload_register(array($this, "load"));
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function load($className) {
		if(array_key_exists($className, $this->classes)) {
			//var_dump("including " . $className);
			include $this->classes[$className];
		}
	}
	
}