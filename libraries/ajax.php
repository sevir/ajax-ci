<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* simple ajax response with cross-domain iframe techniques
* made with love in DIGIO S.L.N.E (http://www.digio.es) by SeViR 2009 
*/

class Ajax{
	private $CI;
	private $responseFormat = 'json';
	private $outputFormatGetParam = 'output';

	/**
	* You can call to the constructor with the params
	* array('format'=>'json|xml') or array('getFormat'=>'<GET param name>')
	*/
	function __construct($param = null){
		$this->CI = &get_instance();
		if ($param && isset($param['getFormat'])){
			$this->outputFormatGetParam = $param['getFormat'];
		}else if($param && isset($param['format'])){
			$this->responseFormat = $param['format'];
		}

		if ($this->CI->input->get($this->outputFormatGetParam)){
			if ($this->CI->input->get($this->outputFormatGetParam) == 'xml'){
				$this->responseFormat = 'xml';
			}else{
				$this->responseFormat = 'json';
			}
		}
	}

	/**
	 * Send one response with a JSON format, you can call to one js callback, assign to one js var or including in a iframe
	 * @param object $var [optional]
	 * @param object $responsefunction [optional]
	 * @param object $as_script [optional]
	 * @param object $assign_variable [optional]
	 * @return void
	 */
	function response($var = '', $responsefunction = '', $as_script = TRUE, $assign_variable = '', $extend_var = FALSE) {
		if ($this->responseFormat == 'xml'){
			$response = $this->xml_encode($var);
		}else{
			$response = json_encode($var);
		}
		
		
		if ($responsefunction != '') {
			$response = $responsefunction . '(' . $response . ')';
		}
		
		if ($as_script) {
			if (!empty($assign_variable)) {
				if ($extend_var) {
					$script = 'var ' . $assign_variable . ' = $.extend(' . $response . ',' . $assign_variable . ')';
				} else {
					$script = 'var ' . $assign_variable . ' = ' . $response;
				}
			} else {
				$script = $response;
			}
		} else {
			$script = '<script language="javascript" type="text/javascript">'.
						'try{ window.parent.window.' . $response . ' }catch(e){}'.
					'</script>';
		}
		
		$this->CI->output->set_content_type('application/'.$this->responseFormat);
		$this->CI->output->set_output($script);
	}

	/**
	 * Alias of response, it returns the response in a iframe
	 * @param object $var [optional]
	 * @param object $responsefunction [optional]
	 * @param object $cross_domain [optional]
	 * @return void
	 */
	function iframe($var = '', $responsefunction = 'iframe_response', $cross_domain = FALSE) {
		$this->response($var, $responsefunction, $cross_domain);
	}

	/**
	* Check if it is an ajax request
	**/
	function is_ajax_request(){
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
			return true;
		}else{
			return false;
		}
	}

	/**
	*	generate output in XML
	**/
	private function xml_encode($mixed,$domElement=null,$DOMDocument=null){
	    if(is_null($DOMDocument)){
	        $DOMDocument=new DOMDocument('1.0','UTF-8');
	        $DOMDocument->formatOutput=true;
	        $this->xml_encode($mixed,$DOMDocument,$DOMDocument);
	        echo $DOMDocument->saveXML();
	    }
	    else{
	        if(is_array($mixed)){
	            foreach($mixed as $index=>$mixedElement){
	                if(is_int($index)){
	                    if($index==0){
	                        $node=$domElement;
	                    }
	                    else{
	                        $node=$DOMDocument->createElement($domElement->tagName);
	                        $domElement->parentNode->appendChild($node);
	                    }
	                }
	                else{
	                    $plural=$DOMDocument->createElement($index);
	                    $domElement->appendChild($plural);
	                    $node=$plural;
	                    if(rtrim($index,'s')!==$index){
	                        $singular=$DOMDocument->createElement(rtrim($index,'s'));
	                        $plural->appendChild($singular);
	                        $node=$singular;
	                    }
	                }
	                $this->xml_encode($mixedElement,$node,$DOMDocument);
	            }
	        }
	        else{
	            $domElement->appendChild($DOMDocument->createTextNode($mixed));
	        }
	    }
	}
}