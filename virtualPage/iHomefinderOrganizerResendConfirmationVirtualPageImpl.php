<?php

class iHomefinderOrganizerResendConfirmationVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Resend Confirmation Email";
	}
	
	public function getPermalink() {
		return "property-organizer-resend-confirmation-email";	
	}
	
	public function getContent() {
		$email = iHomefinderUtility::getInstance()->getRequestVar("email");
		$afterLoginUrl = iHomefinderUtility::getInstance()->getRequestVar("afterLoginUrl");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-resend-confirm-email")
			->addParameter("email", $email)
			->addParameter("afterLoginUrl", $afterLoginUrl)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}