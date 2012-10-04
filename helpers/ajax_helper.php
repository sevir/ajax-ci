<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * prints iframe tag for iframe (ajax simulated) responses
 *
 * @param object  $iframe_name [optional]
 * @return string
 */
function iframe_response($iframe_name = 'iframe_response') {
	return '<iframe src="about:blank" id="' . $iframe_name . '" name="' . $iframe_name . '" style="width:0; height:0; border: 0px #fff solid"></iframe>';
}