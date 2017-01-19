AWS RESIZER
===========

This project allow to upload an image on S3 and retrieve it in different dimensions.  
  
First of all, for use it you have to set these ENVS:

     - APP_ENV=local                                     // standard for laravel
     - APP_DEBUG=false                                   // standard for laravel
     - APP_KEY=SomeRandomString                          // standard for laravel
     - CACHE_DRIVER=file                                 // standard for laravel
     - SESSION_DRIVER=file                               // standard for laravel
     - QUEUE_DRIVER=sync                                 // standard for laravel

     - DIMENSIONS=1400x1200;800x800                      // allowed dimensions
              
     - AWS_ACCESS_KEY_ID=<access key>                    // standard for use aws services
     - AWS_SECRET_ACCESS_KEY=< secret access key>        // standard for use aws services
     - AWS_BUCKET=<your s3 bucket name>                  // standard for use aws services
     - AWS_REGION=<the region your bucket is in>         // standard for use aws services
    
This project is "Docker Ready" and can be build and started with:

     docker build -t aws-resizer .
     docker run -d --name=aws-resizer -p 80:80 --env-file .env aws-resizer
  
    
Available Endpoints:
--------------------
**/dimensions**  
Return the list of available dimensions that the service allows to use  
    
**/upload**  
Simple page for upload an image file (max size 10M)  

**/get/ID**  
Return a json with the remote image url

**/get/ID?size=WIDTHxHEIGHT**  
Return a json with the remote image url with this size if allowed.
If this resized image doesn't exist on S3 the service resizes the original image and upload it to S3.

**/get/ID?mode=show**  
Show the remote image

**/get/ID?mode=redirect**  
Redirect to the path of remote image