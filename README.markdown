# Useful library for AJAX

AJAX spark consists in one library for to simplify JSON responses and one helper for cross-domain iframe technique.

This is a simple method in your controller returning an AJAX response:
```php
function ajax(){
	$this->load->spark('ajax/<version>');
	$this->load->library('ajax');

	if ($this->ajax->is_ajax_request()){
		$this->ajax->response(array('response'=>array('stat'=>'OK', 'msg'=>'All works!')));
	}else{
		$this->ajax->response(array('response'=>array('stat'=>'ERROR', 'msg'=>'Only works with ajax!')));
	}
}
```

You can force an XML output setting the format in the constructor:
```php
$this->load->spark('ajax/1.0');
$this->load->library('ajax', array('format'=>'xml'));
```
Also you can specify the format with an url GET param (by default with &output=json|xml)
```php
$this->load->spark('ajax/1.0');
$this->load->library('ajax', array('getFormat'=>'<GET param name>'));
```

Using JSON response, you can call to a JS callback function
```php
$this->ajax->response($response_array, 'calback');
```

Also you can write the script tag, setting a third param as FALSE (by default no script tag is written):
```php
$this->ajax->response($response_array, 'calback', FALSE);
```
produces:
<script language="javascript" type="text/javascript">try{ window.parent.window.callback({JSON}) }catch(e){}</script>

You can assign the JSON response to a JS variable:
```php
$this->ajax->response($response_array, null, TRUE,'myvariable');
```
produces:
```javascript
var myvariable = {JSON}
```

Finally you can extend the variable with jQuery extend setting a fith param to TRUE:
```php
$this->ajax->response($response_array, null, TRUE,'myvariable', TRUE);
```
produces:
```javascript
var myvariable = $.extend({JSON},myvariable)
```

## Cross-domain AJAX with iframe
This library can use a simple technique for cross-domain ajax. This works with a simple steps:

 1. In your view call to the helper iframe_response, this produces an invisible iframe:
```php
<?=iframe_response('my_iframe_name') ?>
```

 2. Call to the server in JavaScript setting the src url of the iframe or targetting a form submitted
to the iframe, and write a JS callback:
```html
<form action="url" method="POST" target="my_iframe_name">
	<input name="param" value="test" /><button type="submit">Submit</button>
</form>
```

In JavaScript code:
```javascript
function myCallback(data){
	
}
```

Finally
3. In the server method write the iframe response calling to the callback:

```php
$this->ajax->iframe($response_array, 'myCallback', TRUE);
```