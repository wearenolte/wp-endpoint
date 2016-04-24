<?php namespace Lean;

use Lean\Endpoints\Filters;
use Lean\Endpoints\Contract;

/**
 * Class that creates the default behavior used to register a new endpoint and
 * also the same logic that is used across all lean PHP modules.
 *
 * @package Lean;
 */
abstract class AbstractEndpoint {

	/**
	 * The path of the endpoint to be created.
	 *
	 * @var string
	 */
	protected $endpoint = '/lean';

	/**
	 * Static method as user interface for the class that creates a new object
	 * of this class to make sure we can access to instance properties and methods.
	 *
	 * @since 0.1.0
	 */
	public static function init() {
		$child = get_called_class();
		$endpoint = new $child;
		$endpoint->create();
	}

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
			$this->endpoint_options()
		);
	}

	/**
	 * Helper function that creates a filter based on the endpoint
	 * created. That will help to have dynamic filters and to remove an extra
	 * param from the default filters.
	 *
	 * @since 0.1.0
	 *
	 * @return string The filter created
	 */
	protected function get_api_data_filter_name() {
		return Filters::API_DATA . $this->filter_format( $this->endpoint );
	}

	/**
	 * Creates a function that can be used on the child class to format a string
	 * into a filter name.
	 *
	 * @since 0.2.0
	 *
	 * @param String $str The string to be formated.
	 * @return String
	 */
	protected function filter_format( $str = '' ) {
		$endpoint_name = trim( $str );
		return str_replace( [ '-', '/' ], '_', $endpoint_name );
	}

	/**
	 * Function that return the options used on the endpoint can be override
	 * by the class that inherints from this class.
	 *
	 * @since 0.1.0
	 * @return array An associative array or an array of arrays with the params
	 *               to setup the endpoint, by default creates a GET endpoint.
	 */
	protected function endpoint_options() {
		return [
			'methods' => \WP_REST_Server::READABLE,
			'callback' => [ $this, 'endpoint_callback' ],
			'args' => $this->endpoint_args(),
		];
	}

	/**
	 * This is the callback where all the logic of the endpoint is handled here you
	 * recieve a $request param that can be used to handle queries or any other
	 * operation to generate the endpoint.
	 *
	 * @since 0.1.0
	 * @param \WP_REST_Request $request Contains data from the request.
	 */
	public function endpoint_callback( \WP_REST_Request $request ) {
		return [];
	}

	/**
	 * Arguments that the endpoint can recieve in this way we can customize this
	 * on the class that extends from this one.
	 *
	 * The function must return an array with the required params.
	 *
	 * @since 0.1.0
	 * @return Array
	 */
	public function endpoint_args() {
		return [];
	}

	/**
	 * Function that allow to apply the filter on the response.
	 *
	 * @param Array $response The response or data to be applied.
	 * @param Int   $id The id to be pass into the filter.
	 * @since 0.1.0
	 */
	protected function filter_data( $response, $id = 0 ) {
		return apply_filters( $this->get_api_data_filter_name(), $response, $id );
	}
}
