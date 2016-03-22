<?php namespace Leean;

use Leean\Endpoints\Filters;

abstract class Endpoint {

	protected $ENDPOINT = '/leean';
	protected $HTTP_VERB = 'GET';

	public function create() {
		$this->set_variables();
		add_action( 'rest_api_init', [$this, 'register']);
	}

	private function set_variables(){
		$this->namespace = apply_filters( Filters::API_NAMESPACE, 'leean', $this->ENDPOINT );
		$this->version = apply_filters( Filters::API_VERSION, 'v1', $this->ENDPOINT );
		$this->options = [
			'methods' => $this->HTTP_VERB,
			'callback' => [ $this, 'endpointCallback' ],
			'args' => $this->endpointArgs(),
		];
	}

	public function register(){
		register_rest_route(
			$this->namespace . '/' . $this->version,
			$this->ENDPOINT,
			$this->options
		);
	}

	abstract public function endpointCallback(\WP_REST_Request $request);
	abstract public function endpointArgs();
}
