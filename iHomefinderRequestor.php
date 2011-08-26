<?php
if( !class_exists('IHomefinderRequestor')){
	class IHomefinderRequestor{
		public static function remoteRequest( $ihfUrl ){
			IHomefinderLogger::getInstance()->debug("Begin IHomefinderRequestor.remoteRequest: " );
			
			if( !strpos(strtolower($ihfUrl), "subscriberid=")){
				$subscriber = IHomefinderStateManager::getInstance()->getCurrentSubscriber();
				if( !is_null($subscriber) && '' != $subscriber){
					$subscriberId=$subscriber->getId();
					IHomefinderLogger::getInstance()->debug('subscriberId: ' . $subscriberId );
					$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId );
				}	
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "version", IHomefinderConstants::VERSION );
			}
					
			IHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);			
			$requestArgs = array("timeout"=>"20" );
			$response = wp_remote_get($ihfUrl, $requestArgs);

			if( is_wp_error($response)){
				$contentInfo=null;	
			}
			else{
				$responseBody = wp_remote_retrieve_body( $response );
				$contentInfo=json_decode($responseBody);				
			}
			
			IHomefinderLogger::getInstance()->debug("End IHomefinderRequestor.remoteRequest: " );
			
			return $contentInfo ;
		}	
		
		public static function remotePostRequest( $ihfUrl, $postData ){
			IHomefinderLogger::getInstance()->debug("Begin IHomefinderRequestor.remoteRequest: " );
								
			IHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);			
			$requestArgs = array('timeout'=>'20', 'body'=>$postData );
			$response = wp_remote_post($ihfUrl, $requestArgs);
			
			IHomefinderLogger::getInstance()->debug("IHomefinderRequestor.remoteRequest post data " );
			IHomefinderLogger::getInstance()->debugDumpVar($postData);
						
			if( is_wp_error($response)){				
				$contentInfo=null;	
			}
			else{
				$responseBody = wp_remote_retrieve_body( $response );
				$contentInfo=json_decode($responseBody);				
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
	}
}
?>