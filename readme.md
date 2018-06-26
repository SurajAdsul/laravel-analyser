# Laravel Web Analyser
  

## How to run?  
  
I have created a docker setup to run this application.  
To run the application you just need to run the following from root of application:  
```  
sh  install.sh  
```  
  It will run the Docker containers. I have already added all the dependencies in the zip file as it will be easier running instantly. Then in a browser goto.  
```  
http://localhost  
```  
You will find a UI to type the URL.  
  
  To run the unit test cases use the following command:  
```  
docker run -it --rm \
    -v $(pwd)/application:/opt \
    -w /opt \
    --network=php-app_appnet \
    shippingdocker/php \
    vendor/bin/phpunit
```  
  
## Explanation  
  
I have used Laravel framework to create this application.  
  
  You will find all checkers in `app/Checkers` directory and client setup in `app/Analyser` directory.
  
  The starting point of this application is `routes/api` which contains the API route for `http://localhost/api/check?url=siteurl`  
  
The reason behind for creating the API is we can use this same API for anywhere which provides the JSON info about the extracted information from the webpage.  
We can use this API to create the chrome extension or to serve the data to a mobile application.  
  

## How to add a new checker  

Adding new checker is a breeze here.  
  
  You can create a new checker which should be extended from `AbstractChecker` which can be added in `app/Checkers` directory. You have to override the required methods from `AbstractChecker` which implements the `CheckerInterface` interface.  
  
You need to register that checker in a `Config/analysers.php` file.  
  

## Directory Structure  

The `App` Directory  
- The `Analyser` Directory - contains the crawler setup  
- The `Checkers` Directory - contains the different checkers  
  
The `Config` Directory - contains the `analysers.php` file to add new  analyser.  
The `tests/unit` Directory - contains the unit test cases for each checker  
The `resources/views` Directory - Front-end code for the UI 
    
## Future Enhancement

1. We can create a artisan command to add a new checker skeleton easily.
2. The loader is currently displayed in the front end while data is being fetched via ajax request.
3. Currently `ExtractLinks` checker contains too much code which can be extracted using a value object. 
4. As we add more checkers we will get a clear idea what pattern it should follow but current pattern should suffice for most of newly added checkers.
5. Can use queue to crawl through all the webpages as it will use the server resources optimally in case of lot of traffic.

## Third party packages used
"guzzlehttp/guzzle": "^6.3",  
"symfony/dom-crawler": "^3.4"
