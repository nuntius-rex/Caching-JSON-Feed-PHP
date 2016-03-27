<?PHP
//============================================================================================
// Note: This block of code is used to display the file as code:
	if(isset($_GET["code"])){
		highlight_file( __FILE__ );	die();
	}
// This single page PHP is coded in a simple in-line method to quickly and simplistically 
// convey the logic for the demo. It is not intended to imply a preference as opposed to 
// OOP or MVC methodologies by the author.
// 
// Author: Dan Guinn - danguinn.com
//============================================================================================

	error_reporting(0);
	ini_set('display_errors', 0);
	
	//error_reporting(E_ALL);
	//ini_set('display_startup_errors', 1);
	//ini_set('display_errors', 1);

?>
	<!DOCTYPE HTML>
	<html>
	<head>
	<title>DANGUINN.COM - DEMO - Caching a JSON feed with PHP</title>
	<!-- Standard stuff here, just loading bootstrap from CDN-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script src="http://danguinn.com/config/custom-files/js/global.js"></script>
	
	
	
	<style>
		body{
			margin:100px;
			background-color:#DADADA;
		}
		
		#json-feed-display{
			border:solid 1px #000000;
			background-color:#FFFFFF;
			width:100%;
			height:40%;
			overflow:auto;
			padding:10px;	
		}
		
	</style>
	</head>
	<body>
	<div class="container">
	<div class="row">
		<!-- Instructions-->
	
		<h1>DANGUINN.COM - DEMO - Caching a JSON feed with PHP</h1>
		<h2>(Caching a fresh JSON file from a remote server, like NASA, with PHP)</h2>
		<h3>by Dan Guinn</h3>
		<p>
			This demo is some code I wrote to cache a JSON feed from a vendor. They requested that we cached the feed daily rather than referencing it directly on each page load.
			The script caches a new JSON file each day. When you loaded the page, it checked the date of the file, and if it was new it created a new file and deleted the old.
			Note: I am using a public demo feed here from NASA. Many feeds will require additional authentication or API key.
		</p>
		<p>In actual usage, this file could be created with no output except for the JSON feed, and would be included in a &lt;script&gt; header reference or be called from the 
		tool that will parse the feed for usage in the application. In this example I am both calling it and displaying the feed, the PHP JSON Object, and displaying an image from the feed.</p>
		<br />
		<p><small>Looking for an analyst or a big picture programmer/analyst? Check out 
		<a href="http://danguinn.com">danguinn.com</a> for more information about Dan Guinn. Hope you enjoyed the demo!</small></p>
		<br />
	</div><!--End Row -->
	<div class="row">
	    <div class="col-md-12">
			
			<div id="json-feed-display">
			<p> <a href="http://danguinn.com">Home</a> | <a href="">Reload</a> | <a href="?code">View Code</a> | <a href="http://danguinn.com#portfolio">More Examples</a></p>
			<br/>
		<?PHP
			//Set your feed:
			//$feed_url="http://YOUR_FEED_URL/YOUR_FILENAME.json";
			//Note: I am using a demo feed here from NASA. Many feeds will require an authentication piece or API key.			
			$feed_url="https://api.nasa.gov/planetary/earth/imagery?lon=100.75&lat=1.5&date=2014-02-01&cloud_score=True&api_key=DEMO_KEY";
			
			echo "<p>Feed to be cached:<br /><a href=\"".$feed_url."\" target=\"_blank\">".$feed_url."</a></p>";

			//Set the date to today:
			$date=date("Y-m-d");

			//Set the location and name for the JSON file:
			//Note: My file will be cached in a folder called cache in the current directory.
			$cacheFile=__DIR__."/cache/".$date.".json";
			//echo $cacheFile;
			echo "<p>New feed cached today:<br /><a href=\"cache/".$date.".json\" target=\"_blank\">cache/".$date.".json</a></p>";
			

			//Set yesterday's date
			//Note: It is imperative that the server time is correct or you will encounter issues.
			//A good failsafe would be to extend the days of the caching

			$yesterday = date('Y-m-d',strtotime($date . " -1 days"));

			//Set the location and name for yesterday's file:
			//Note: My file will be cached in a folder called cache in the current directory.
			$prevDay_cachedFile=__DIR__."/cache/".$yesterday.".json";

			//Okay, now we are ready to go. Just using a simple date naming convention we now have provided
			//our program a means to tell if the current file is the newest.  
			
			//Check to see if today's file exist.
			if (file_exists($cacheFile)) {

				//If it exist, open, read it and echo it to the browser.*/
				$fh = fopen($cacheFile, 'r');
				//echo "writing from ".__LINE__;
				$contents=fread($fh, filesize($cacheFile));

			}else{

				/*If it doesn't exit, check for the previous day file.*/
				if(file_exists($prevDay_cachedFile)){
					//If yesterday's file exist, delete it. This is just to clean up each day, when a new file is cached.
					unlink($prevDay_cachedFile);
				}
				
				//Now open the cache file:
				$fh = fopen($cacheFile, 'w');

				//Get the contents of the new feed.
				$feed=file_get_contents($feed_url);

					if($feed === false){
						/*If there is nothing in the feed then don't cache it. In other words, don't write an empty file! 
						 * This is helpful if the feed you are pulling from goes down. Note: I coded it this way as a reminder 
						 * to myself that this check is in place.*/
					}else{
						/*If the feed is good, write it to file with the current day as the name.*/
						fwrite($fh, $feed);
						fclose($fh);
					
						$fh = fopen($cacheFile, 'r');
						//echo "writing from ".__LINE__;
						$contents=fread($fh, filesize($cacheFile));
						
					}
			}
			
			if(isset($contents)){
				
				$JSONObj = json_decode($contents);
				
				echo "\n"
				."<p>Raw JSON output from cached file:"
				."<code><pre>".$contents."</pre></code></p>"
				."<br />"
				."<p>PHP JSON Object (What the application will use to build HTML content):"
				."<code><pre>".displayJSONHTML($JSONObj, $contents)."</pre></code></p>"
				."<p>Sample HTML Output (Using the JSON data object to get the image url):"
				."<p>".displayData($JSONObj, $contents)."</p>"
				."";
				
			}
	//These are extra functions to display the JSON Object and sample HTML output that might be returned by the application.
	//This is for display purposes only as this would be more efficient in a class structure.
	function displayJSONHTML($JSONObj, $json_string){
		$JSON_text=print_r($JSONObj, true);
		return $JSON_text;
	}
	
	function displayData($JSONObj, $json_string){
		$JSONObj = json_decode($json_string);
		$line="\n"
		."<img src='".$JSONObj->url."'>"
		."";
		return $line;	
	}

?>
				
				</div><!--End JSON display -->
				
				</div><!--End Column -->
			
	</div><!--End Row -->
</div><!--End Container -->
</body>
</html>
