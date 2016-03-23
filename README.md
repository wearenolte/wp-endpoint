# WP Endpoint

> Class that allow easy creation of endpoints under the leean namespace.

The class provides a set of defined methods and mechanism to register a new endpoint more easily and by reusing same component across other modules.

## Getting Started

The easiest way to install this package is by using composer from your terminal:

```bash
composer require moxie-leean/wp-endpoint
```

Or by adding the following lines on your `composer.json` file

```json
"require": {
  "moxie-leean/wp-endpoint": "dev-master"
}
```

This will download the files from the [packagist site](https://packagist.org/packages/moxie-leean/wp-endpoint)
and set you up with the latest version located on master branch of the repository.

After that you can include the `autoload.php` file in order to
be able to autoload the class during the object creation.

```php
include '/vendor/autoload.php';
```

## Usage

After you added the dependency on your module you need to create a new class that defines your endpoint for example:

```php
<?php
use Leean\AbstractEndpoint;

class customEndpoint extends AbstractEndpoint {

	protected $endpoint = '/customEndpoint';

	public function endpoint_callback( \WP_REST_Request $request ) {
		$data = [
		    'data' => 'Hi',
		    'count' => 10
		];
		return $this->filter_data( $data );
	}

	public function endpoint_args() {
		return [
			'id' => [
				'required' => true,
				'sanitize_callback' => function ( $author_id, $request, $key ) {
					return absint( $author_id );
				},
			],
		];
	}
}
```

On this class you have:

- `init` or any other name you want to expose is a public static method that always have the same signature in order to execute the code that runs the new endpoint.
- `endpoint_callback` , By default you have one method that allow you to define the response of the GET request to the endpoint, GET is the default HTTP Verb.
-  `endpoint_args`, Same as `endpoint_callback`, is the callback by default that allow you to define if the endpoint allow parameters and how are going to be used.
-  `endpoint_options`, if you want to specify a new set of HTTP verbs andor rewrite the default endpoint options, you only need  to return an array with the parameters that are required for the endpoint.

For instance to define a new slug parameter with a different HTTP verb and reuse the default get params. You only need to define as follows

```php
	protected function endpoint_options() {
		return [
		    [
			'methods' => \WP_REST_Server::DELETABLE,
			'callback' => [ $this, 'custom_callback_now' ],
			],
			parent::endpoint_options,
		];
	}
```

`parent::endpoint_options` is going to return the default options defined on `Endpoint` class if you don't want to redeclare the options for a GET request.

### Filters

- `ln_endpoints_api_namespace`, This allow you to overwrite the default namespace used on the API definition.
- `ln_endpoints_api_version`, This filter allow you to change the version number of the API.
- `ln_endpoints_data_${api}`, where `${api}` is the name of your endpoint for instance in the case above: `ln_endpoints_data_customEndpoint`.

