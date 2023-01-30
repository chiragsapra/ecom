## About Ecom

To use this application please install this laravel app to the system and runthis commands.

# To install tables
php artisan migrate

# To install cutomer data from csv file.
php artisan create:customer

# To install product data from csv file.
php artisan create:product

# To use CRUD operation for application
To create order use an email ID on this URL with POST request
<base_url>/api/orders/create

To see all orders hit this URL with GET request
<base_url>/api/orders

To delete an order hit URL with order id to delete with DELETE request
<base_url>/api/orders/<id>/delete

To add the product to existing created order hit URL with product id with POST request
<base_url>/api/orders/<id>/add

To pay for the order hit the URL with POST request.
<base_url>/api/orders/<id>/pay

** I have used the file stored in storage folder to import cuatomer and product data as you URL is giving some problem about fetching the data.
