# Caching a JSON Feed with PHP
Caching a fresh JSON file from a remote server with PHP (so that the system only calls the service once a day).  

![Preview](/preview.png?raw=true "Preview")

SCENARIO: This demo is some code I wrote to cache a JSON feed from a vendor. The vendor requested that we cached the feed daily rather than referencing it directly on each page load so that their feed would only get hit once a day from us to save server resources. The script caches a new JSON file each day. Upon page load, it checks the date of the file, and if the file is old, it creates a new file, caches the feed and writes it to the new file and then finally deletes the old file. Note: I am using a public demo feed here from NASA. Many feeds will require additional authentication or an API key.

In actual usage, this file could be created with no output except for the JSON feed, and could be included in a <script> header reference or be called from the tool that will parse the feed for usage in the application. In this example I am both calling it and displaying the feed, the PHP JSON Object, and displaying an image from the feed.

You will need a folder on your server called "cache" in the same directory as the script, or you need to change the url.

Project Page: http://danguinn.com/dan-guinn/config/custom-files/code-examples/cache-json-feed/

Code Page: http://danguinn.com/dan-guinn/config/custom-files/code-examples/cache-json-feed/?code
