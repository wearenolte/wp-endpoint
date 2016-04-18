<?php namespace Leean;

use Leean\AbstractEndpoint;
use Leean\Endpoints\Filters;

/**
 * Class that implements the behaviour of a collection of posts.
 *
 * @package Lean;
 */
abstract class AbstractCollectionEndpoint extends AbstractEndpoint {

	/**
	 * Array that holds all the shared arguments used for query the site.
	 *
	 * @since 0.1.0
	 * @var array
	 */
	protected $args = [];

	/**
	 * Function inherint from the parant Abstract class that is called once the
	 * endpoint has been initiated and the method that returns the data delivered
	 * to the endpoint.
	 *
	 * @Override
	 *
	 * @since 0.1.0
	 *
	 * @param \WP_REST_Request $request The request object that mimics the request
	 *									made by the user.
	 * @return array The data to be delivered to the endpoint
	 */
	public function endpoint_callback( \WP_REST_Request $request ) {
		$this->args = $request->get_params();
		return $this->filter_data( $this->loop() );
	}

	/**
	 * WP_Query Loop that has been triggered from the endpoint.
	 */
	abstract protected function loop();

	/**
	 * This function allow to format every item that is returned to the endpoint
	 * the filter sends 3 params to the user so can be more easy to manipulate the
	 * data based on certain params.
	 *
	 * @param object $item The unformatted post object.
	 */
	protected function format_item( $item ) {
		return apply_filters( Filter::ITEM_FORMAT, $item, $item, $this->args );
	}

	/**
	 * Clean up and make sure we don't deliver post with password, privates and
	 * some other datat that might be sensible on the API. This medhod overrides
	 * the default mechanism inherint from the parent class.
	 *
	 * @Override
	 *
	 * @since 0.1.0
	 * @return array an array with the accepted arguments and options per each argument.
	 */
	public function endpoint_args() {
		return [];
	}
}
