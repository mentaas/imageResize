## Image Resize
Logic of application
-Photos are resized, saved and displayed from given URL

-If the request is within 24h and is with the same URL and dimension as any cached resized image in database than image
will be return from database and there is no need for resizing again

-If there is a same request as any resized image in database but after 24h system will try to refresh(update) the risized
image, if there is any problem with refreshing than system will retrun the resized image that is stored in database

-If  there is a request with same URL as any URL of resized image stored in database but with different dimension
system will try to get image from URL but if there is any error system will get image from resized image in database and resize again
with new dimension and display to the user.

## API
Url: /api/resizeImage; Method: GET; Retrun: All resized images

Url: /api/resizeImage/{id}; Method: GET; Retrun: Particular resized image

Url: /api/resizeImage; Method: POST; Retrun: Save resized image

Url: /api/resizeImage/{id}; Method: DELETE; Retrun: Delete particular image by id

Url: /api/resizeImage/{url}/{width}/{height}; Method: DELETE; Retrun: Delete specific image by url, width and height

!!!Please provide encoded url as parameter!!!

Url: /api/resizeImage/{id}; Method: DELETE; Retrun: Delete image(s) by url  [Please check Appache for AllowEncodedSlashes]

Url: /api/resizeImage/image/{url}; Method: GET; Retrun: Particular resized image from random generated path





