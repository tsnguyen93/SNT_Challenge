<?php
class Article{
	Protected $doc;
	
	Public function __construct($doc){
		$this->doc = $doc;
	}
	
	
	Public function getHeadline(){
		$headers = $this->doc->getElementsByTagName('h1');
		$headers_arr = array();
		
			foreach ($headers as $header){
			$headers_arr[] = $header->nodeValue;
		}
		$main_header = array_values($headers_arr)[0];
		
		if ($main_header){
			return $main_header;
		}
		else{
			return "No headline found.";
		}
			
	}
	
	
	Public function getImage(){
		$images = $this->doc->getElementsByTagName('img');
		$images_arr = array();
		
		foreach ($images as $image){
			$images_arr[] = $image;
		}
		
		$main_image = array_values($images_arr)[1]->getAttribute('src');
		
		if($main_image){
			return $main_image;
		}
		else{
			return "No main image found.";
		}

	}


	Public function getStory(){
		$stories = $this->doc->getElementsByTagName('p');
		$xpath = new DOMXPath($this->doc);		
		$stories_arr = array ();
		
		foreach ($stories as $story){
			$stories_arr[] = $story->nodeValue;
		}
		
		foreach ($xpath->query('//div[@class="zn-body__paragraph"]') as $div){
			$stories_arr[] = $div->textContent;
		}
		if ($stories_arr){		
			return $stories_arr;
		}
		else{
			return "Not story content found.";
		}
	}

}
?>


<?php
$url = $_GET["article"];
if ($url != 'http://www.cnn.com/2016/06/16/design/nasa-mars-posters/index.html'){
	echo "Please go back and use the link " . "<b>" . "http://www.cnn.com/2016/06/16/design/nasa-mars-posters/index.html" . "</b>" ;
}
else{	
	$string = file_get_contents($url);
	$doc = new DOMDocument();
	libxml_use_internal_errors(true);
	$doc->loadHTML($string);
	libxml_use_internal_errors(false);
	$xpath = new DOMXPath($doc);


	$article = new Article($doc);
	$headline = "<h1>" . $article->getHeadline() . "</h1>";
	echo $headline;
	echo "<br>";

	$mainImage = '<a><img src = "' . $article->getImage() . '"></a>';
	echo $mainImage;
	echo "<br>";

	$storyArray = $article->getStory();
	$storyLen = count($storyArray);
	//echo $storyLen;

	if ($storyLen > 1){
		foreach ($storyArray as $p){
			echo $p;
			echo "<br>";
		}
	}
	else{
		echo $storyArray;
	}
}
?>