<?php
if( !class_exists('IHomefinderRequestor')){
	class IHomefinderRequestor{
				
		public static function remoteRequest( $ihfUrl, $ajaxRequest=false ){
			IHomefinderLogger::getInstance()->debug("Begin IHomefinderRequestor.remoteRequest: " );
				
			//We don't try to get subscriber information for ajax requests
			//because of cookie related complications.
			if( !strpos(strtolower($ihfUrl), "subscriberid=")  ){
				$subscriber = IHomefinderStateManager::getInstance()->getCurrentSubscriber();

				if( !is_null($subscriber) && '' != $subscriber){
					$subscriberId=$subscriber->getId();
					IHomefinderLogger::getInstance()->debug('subscriberId: ' . $subscriberId );
					$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId );
				}
			}
			
			if( !strpos(strtolower($ihfUrl), "jsessionid=") ){
				$ihfSessionId=IHomefinderStateManager::getInstance()->getIhfSessionId();
				$ihfUrl=str_replace( IHomefinderLayoutManager::getInstance()->getExternalUrl(), IHomefinderLayoutManager::getInstance()->getExternalUrl() . ";jsessionid=" . $ihfSessionId, $ihfUrl );				
			}
						
			//If the url does not have the lead capture id then try to add it
			$ihfUrlHasLeadCapture=strrpos($ihfUrl, "leadCaptureId=");
			if($ihfUrlHasLeadCapture === false){
				$leadCaptureId = IHomefinderStateManager::getInstance()->getLeadCaptureId();
				IHomefinderLogger::getInstance()->debug("leadCaptureId=" . $leadCaptureId );
				if( !is_null($leadCaptureId) && '' != $leadCaptureId){
					IHomefinderLogger::getInstance()->debug('leadCaptureId: ' . $leadCaptureId );
					$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "leadCaptureId", $leadCaptureId );
				}
			}
				
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "version", IHomefinderConstants::VERSION );
			$userAgent=$_SERVER['HTTP_USER_AGENT'];
			if( $userAgent != null ){
				$userAgent=urlencode($userAgent);
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "uagent", $userAgent ) ;	
			}
			
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "loadJQuery", "false" ) ;
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "leadCaptureSupport", "true" ) ;
			
			IHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);
			//if( $ajaxRequest ){echo( $ihfUrl );die();}
			//echo( $ihfUrl ); die();
			$ihfid=site_url() + ";" + "WordpressPlugin";
			$requestArgs = array("timeout"=>"200", "ihfid"=> $ihfid );
			IHomefinderLogger::getInstance()->debug("before request");
			$response = wp_remote_get($ihfUrl, $requestArgs);

			IHomefinderLogger::getInstance()->debug("after request");
			
			
			if( is_wp_error($response)){
				$contentInfo=null;
			}
			else{
				$responseBody = wp_remote_retrieve_body( $response );
				IHomefinderLogger::getInstance()->debug('responseBody: ' . $responseBody );
				try{
					$contentType=wp_remote_retrieve_header($response, "content-type");
					//$ihfSessionId=wp_remote_retrieve_header($response, "ihfSessionId");		
					if( $contentType != null && $contentType == "text/xml;charset=UTF-8"){
						$contentInfo=simplexml_load_string($responseBody);	
					}
					else{
						$contentInfo=json_decode($responseBody);
					}
				}catch (Exception $e){
					var_dump($e);
				}
			}
			
			//if( $ajaxRequest ){var_dump($contentInfo);die();}
			
			IHomefinderLogger::getInstance()->debug("after get body");
			
				
			//Save the leadCaptureId, if we get it back.
			if( isset( $contentInfo->leadCaptureId ) ){
				IHomefinderLogger::getInstance()->debug("calling saveLeadCaptureId with leadCaptureId=" . $contentInfo->leadCaptureId);
				IHomefinderStateManager::getInstance()->saveLeadCaptureId($contentInfo->leadCaptureId);
			}
			
			if( isset( $contentInfo->ihfSessionId ) ){
				IHomefinderStateManager::getInstance()->saveIhfSessionId($contentInfo->ihfSessionId);
			}		
			
			if( isset( $contentInfo->searchContext ) ){
				IHomefinderStateManager::getInstance()->setSearchContext($contentInfo->searchContext);
			}	

			if( isset( $contentInfo->listingInfo ) ){
				$listingInfo=$contentInfo->listingInfo;
				$listingNumber="";
				$listingAddress="";
				$boardId="";
				$clientPropertyId="";
				$sold="false";
				
				$hasListingInfo=false;
				if( isset( $listingInfo->listingNumber ) && isset( $listingInfo->boardId ) ){
					$listingNumber=$listingInfo->listingNumber ;
					$boardId=$listingInfo->boardId ;
					$hasListingInfo=true;
					if( isset( $listingInfo->clientPropertyId ) ){
						$clientPropertyId=$listingInfo->clientPropertyId ;
					}
					if( isset( $listingInfo->listingAddress ) ){
						$listingAddress=$listingInfo->listingAddress ;
					}
					if( isset( $listingInfo->sold ) ){
						$sold=$listingInfo->sold ;
					}
					$listingInfo=
						new iHomefinderListingInfo($listingNumber, $boardId, $listingAddress, $clientPropertyId, $sold );
						IHomefinderStateManager::getInstance()->setCurrentListingInfo($listingInfo);					
				}
			}	
				
			if( !IHomefinderRequestor::isError($contentInfo) && isset( $contentInfo->subscriberInfo )){
				$subscriberData=$contentInfo->subscriberInfo ;
				$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email );
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);
			}
			
			if( !IHomefinderRequestor::isError($contentInfo) && isset( $contentInfo->searchSummary )){
				$searchSummary=$contentInfo->searchSummary ;
				IHomefinderStateManager::getInstance()->saveSearchSummary($searchSummary);
			}

			IHomefinderRequestor::loadJavaScriptAndCss($contentInfo);
			
			IHomefinderLogger::getInstance()->debug("End IHomefinderRequestor.remoteRequest: " );
				
			return $contentInfo ;
		}
		
		/**
		 * 
		 * Enqueue CSS and JavaScript if included in the contentInfo
		 * @param unknown_type $contentInfo
		 */
		private static function loadJavaScriptAndCss( $contentInfo ){
			if( isset($contentInfo->css )){
				$cssList=$contentInfo->css;				
				foreach ($cssList->item as $item) {
					wp_enqueue_style($item->name, $item->url );
				}
			}
				
			if( isset($contentInfo->javascript )){
				$javascriptList=$contentInfo->javascript;				
				foreach ($javascriptList->item as $item) {
					$name=$item->name;
					$url=$item->url;
					$depends=false;
					if( isset($item->depends)){
						$depends=array();
						foreach( $item->depends as $oneDependency ){
							array_push($depends, $oneDependency);
						}
					}
					wp_enqueue_script($name, $url, $depends);
				}
			}			
			return;			
		}
		
		public static function remotePostRequest( $ihfUrl, $postData ){
			IHomefinderLogger::getInstance()->debug("Begin IHomefinderRequestor.remoteRequest: " );

			IHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);
			$requestArgs = array('timeout'=>'200', 'body'=>$postData );
			$response = wp_remote_post($ihfUrl, $requestArgs);
			
			IHomefinderLogger::getInstance()->debug("IHomefinderRequestor.remoteRequest post data " );
			IHomefinderLogger::getInstance()->debugDumpVar($postData);
			IHomefinderLogger::getInstance()->debugDumpVar($response);

			if( is_wp_error($response)){
				$contentInfo=null;
			}
			else{
				$responseBody = wp_remote_retrieve_body( $response );
				
				$contentType=wp_remote_retrieve_header($response, "content-type");
				if( $contentType != null && $contentType == "text/xml;charset=UTF-8"){
					$contentInfo=simplexml_load_string($responseBody);	
				}
				else{
					$contentInfo=json_decode($responseBody);
				}
								
				IHomefinderLogger::getInstance()->debugDumpVar($responseBody);
			}
				
			IHomefinderLogger::getInstance()->debug("IHomefinderRequestor.remoteRequest response " );
			IHomefinderLogger::getInstance()->debug("End IHomefinderRequestor.remoteRequest: " );
				
			return $contentInfo ;
		}

		public static function appendQueryVarIfNotEmpty( $ihfUrl, $queryVarName, $queryVarValue){
			if(isset($queryVarValue, $queryVarName )){
				$queryVarValue=urlencode($queryVarValue);
				$trimmedValue=trim($queryVarValue);
				if( '' != $trimmedValue ){
					if( strpos( $ihfUrl, "?")){
						$ihfUrl = $ihfUrl . "&" . $queryVarName . "=" . $trimmedValue ;
					} else {
						$ihfUrl = $ihfUrl . "?" . $queryVarName . "=" . $trimmedValue ;
					}
				}

			}
			return $ihfUrl ;
		}

		public static function isError($contentInfo){
			$result=false;
			if(is_null($contentInfo) || property_exists($contentInfo, "error")){
				$result=true;
			}
			return $result ;
		}

		/**
		 *
		 * Extract the content from the response.
		 * @param $contentInfo
		 */
		public static function getContent($contentInfo){
			$content='';
			
			if(is_null($contentInfo)){
				//We could reach this code, if the iHomefinder services are down.
				$content = "<br/>Sorry we are experiencing system issues.  Please try again.<br/>";
			}
			else if (property_exists($contentInfo, "error")){
				//Report the error from iHomefinder
				$content = "<br/>" . $contentInfo->error . "</br/>";
			}
			else if( property_exists($contentInfo, "view")){
				//success, display the view
				$content = $contentInfo->view ;
			}
			
			return $content ;
		}
		
			/**
		 *
		 * Extract JSON from the response for ajax requests.
		 * @param $contentInfo
		 */
		public static function getJson($contentInfo){
			$json='';
			
			if( property_exists($contentInfo, "json")){
				//success, return the json
				$json = $contentInfo->json ;
			}
			
			return $json ;
		}		
		
		
		public static function addVarsToUrl($url, $arrayOfVars){
			foreach($arrayOfVars as $key=>$val) {
				$paramValue=null;
				if( is_array($val)){
					foreach( $val as $value ){
						if( $paramValue != null ){
							$paramValue .=  ",";
						}
						$paramValue .=  $value;
					}
				} else {
					$paramValue=$val;
				}
				$url = iHomefinderRequestor::appendQueryVarIfNotEmpty($url, $key, $paramValue );
			}
				
			return $url ;
		}
		
		
	}
}
?>