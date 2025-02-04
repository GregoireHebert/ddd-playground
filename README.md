#


## Usage

Install the app

```shell
composer install
bin/console d:d:c
bin/console d:s:c
```

Start the app

```shell
symfony serve
```

Create a smartphone

```shell
curl --request POST \
--url https://localhost:8000/catalogue/classic-service-cqs/smartphones \
--header 'content-type: multipart/form-data' \
--form label=itelephone
```

Get a smartphone

```shell
curl --request GET \
  --url https://localhost:8000/catalogue/classic-service-cqs/smartphones/0194d14b-aa2b-70c6-b120-387e16a2025c
```

Toggle a smartphone

```shell
curl --request POST \
  --url https://localhost:8000/catalogue/classic-service-cqs/smartphones/0194d14b-aa2b-70c6-b120-387e16a2025c/rpc-toggle
```

Toggle a smartphone but without an application service because here it is not needed.

```shell
curl --request POST \
  --url https://localhost:8000/catalogue/aggregate-method-cqs/smartphones/0194d14b-aa2b-70c6-b120-387e16a2025c/rpc-toggle
```
