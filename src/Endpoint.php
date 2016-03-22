<?php namespace Leean;

use Leean\Endpoints\Filters;

/**
 * Class that creates the default behavior used to register a new endpoint and
 * also the same logic that is used across all lean PHP modules.
 *
 * @package Leean;
 */
abstract class Endpoint {

	/**
	 * The path of the endpoint to be created.
	 *
	 * @var string
	 */
	protected $endpoint = '/leean';

	/**
	 * HTTP Verb used on the API.
	 *
	 * @var string
	 */
	protected $http_verb = 'GET';

	/**
	 * Register the endpoint on the WP Rest API and initializes the variables.
	 *
	 * @since 0.1.0
	 */
	public function create() {
		$this->set_variables();
		add_action( 'rest_api_init', [ $this, 'register' ] );
	}

	/**
	 * Variables.
	 *
	 * @since 0.1.0
	 */
	private function set_variables() {
		$this->namespace = apply_filters( Filters::API_NAMESPACE, 'leean', $this->endpoint );
		$this->version = apply_filters( Filters::API_VERSION, 'v1', $this->endpoint );
		$this->options = [
			'methods' => $this->http_verb,
			'callback' => [ $this, 'endpoint_callback' ],
			'args' => $this->endpoint_args(),
		];
	}

	/**
	 * Callback called from rest_api_init action that creates the new endpoint.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		register_rest_route(
			$this->namespace . '/' . $this->version,
			$this->endpoint,
			$this->options
		);
	}

	/**
	 * This is the callback where all the logic of the endpoint is handled here you
	 * recieve a $request param that can be used to handle queries or any other
	 * operation to generate the endpoint.
	 *
	 * @param \WP_REST_Request $request Contains data from the request.
	 */
	abstract public function endpoint_callback( \WP_REST_Request $request );

	/**
	 * Arguments that the endpoint can recieve in this way we can customize this
	 * on the class that extends from this one.
	 *
	 * The function must return an array with the required params.
	 *
	 * @since 0.1.0
	 * @return Array
	 */
	abstract public function endpoint_args();
}
