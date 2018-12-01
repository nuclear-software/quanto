<?php
	use Symfony\Component\HttpFoundation\File\MimeType\ExtensionGuesser;

	function dot_to_html($dot){
		$dots = explode('.', $dot);
        $response = $dots[0];    
        foreach (array_slice($dots,1) as $item) {
            $response = $response.'['.$item.']';
        }
        return $response	;
	}

	function up_camel_case($string){
		return ucfirst(camel_case($string));
	}

	function json_to_php($string){
		return str_replace(':', '=>', str_replace('}',']',str_replace('{', '[', $string)));
	}

	function images_path($path=''){
		return storage_path($path?"app/images/$path":'app/images/');
	}

	function getExtensionToMimeType($type){
        $guesser = ExtensionGuesser::getInstance();
        return $guesser->guess($type);
	}
?>