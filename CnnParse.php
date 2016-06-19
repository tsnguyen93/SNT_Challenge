<?php
class Article{
	Protected $url;
	
	Public function __construct($url){
		$this->url = $url;
	}
	
	// This is grabs every content within an h1 tag.
	// This captures the headline of the article.
	Public function getHeadline(){
		$string = file_get_contents($this->url);
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($string);
		libxml_use_internal_errors(false);
		
		$headers = $doc->getElementsByTagName('h1');
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
	
	//There were multiple images of the same image but different size.
	//This captures one of the images to display but iterates through all images scraped.
	Public function getImage(){
		$string = file_get_contents($this->url);
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($string);
		libxml_use_internal_errors(false);
		
		$images = $doc->getElementsByTagName('img');
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

	//This function captured the whole story of the article without html tags attached.
	//Part of the story was located in p tags and the rest was located in the div class = "zn-body__paragraph".
	//This grabs everything and stores each value into an array and returns it to the user.
	Public function getStory(){
		$string = file_get_contents($this->url);
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($string);
		libxml_use_internal_errors(false);
		
		$xpath = new DOMXPath($doc);	
		
		$stories = $doc->getElementsByTagName('p');		
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

	$article = new Article($url);
	
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