<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin;

use Closure;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

use Paddlin\Resources\Addresses;
use Paddlin\Resources\Adjustments;
use Paddlin\Resources\Businesses;
use Paddlin\Resources\Customers;
use Paddlin\Resources\Discounts;
use Paddlin\Resources\Prices;
use Paddlin\Resources\PricingPreviews;
use Paddlin\Resources\Products;
use Paddlin\Resources\Subscriptions;
use Paddlin\Resources\Transactions;

class Client {

    /**
     * API key
     */
    protected string $api_key;

    /**
     * Sandbox flag
     */
    protected bool $sandbox;

    /**
     * ClientInterface implementation
     */
    protected ClientInterface $http_client;

    /**
     * ServerRequestFactoryInterface implementation
     */
    protected ServerRequestFactoryInterface $request_factory;

    /**
     * Addresses resource
     */
    public readonly Addresses $addresses;

    /**
     * Adjustments resource
     */
    public readonly Adjustments $adjustments;

    /**
     * Businesses resource
     */
    public readonly Businesses $businesses;

    /**
     * Customers resource
     */
    public readonly Customers $customers;

    /**
     * Discounts resource
     */
    public readonly Discounts $discounts;

    /**
     * Prices resource
     */
    public readonly Prices $prices;

    /**
     * PricingPreviews resource
     */
    public readonly PricingPreviews $pricingPreviews;

    /**
     * Products resource
     */
    public readonly Products $products;

    /**
     * Subscriptions resource
     */
    public readonly Subscriptions $subscriptions;

    /**
     * Transactions resource
     */
    public readonly Transactions $transactions;

    /**
     * Constructor
     * @param ClientInterface               $http_client     ClientInterface implementation
     * @param ServerRequestFactoryInterface $request_factory ServerRequestFactoryInterface implementation
     * @param string                        $api_key         API key
     * @param bool                          $sandbox         Sandbox flag
     */
    public function __construct(ClientInterface $http_client, ServerRequestFactoryInterface $request_factory, string $api_key, bool $sandbox = false) {
        $this->http_client = $http_client;
        $this->request_factory = $request_factory;
        $this->api_key = $api_key;
        $this->sandbox = $sandbox;
        #
        $this->addresses = new Addresses($this);
        $this->adjustments = new Adjustments($this);
        $this->businesses = new Businesses($this);
        $this->customers = new Customers($this);
        $this->discounts = new Discounts($this);
        $this->prices = new Prices($this);
        $this->pricingPreviews = new PricingPreviews($this);
        $this->products = new Products($this);
        $this->subscriptions = new Subscriptions($this);
        $this->transactions = new Transactions($this);
    }

    /**
     * Execute GET request
     * @param  string $endpoint   Request endpoint
     * @param  array  $parameters Endpoint parameters
     */
    public function getRequest(string $endpoint, array $parameters = []): Response {
        return $this->executeRequest('GET', $endpoint, function(ServerRequestInterface $request) use ($parameters) {
            if ($parameters) {
                $request = $request->withQueryParams($parameters);
            }
            return $request;
        });
    }

    /**
     * Execute POST request
     * @param  string $endpoint   Request endpoint
     * @param  array  $payload    Request payload
     * @param  array  $parameters Endpoint parameters
     */
    public function postRequest(string $endpoint, array $payload = [], array $parameters = []): Response {
        return $this->executeRequest('POST', $endpoint, function(ServerRequestInterface $request) use ($payload, $parameters) {
            $contents = json_encode($payload);
            $contents = $contents == '[]' ? '{}' : $contents;
            if ($contents) {
                $request = $request->withHeader('Content-Type', 'application/json');
                $request->getBody()->write($contents);
            }
            if ($parameters) {
                $request = $request->withQueryParams($parameters); // @codeCoverageIgnore
            }
            return $request;
        });
    }

    /**
     * Execute PATCH request
     * @param  string $endpoint   Request endpoint
     * @param  array  $payload    Request payload
     */
    public function patchRequest(string $endpoint, array $payload): Response {
        return $this->executeRequest('PATCH', $endpoint, function(ServerRequestInterface $request) use ($payload) {
            $contents = json_encode($payload);
            $contents = $contents == '[]' ? '{}' : $contents;
            if ($contents) {
                $request = $request->withHeader('Content-Type', 'application/json');
                $request->getBody()->write($contents);
            }
            return $request;
        });
    }

    // @codeCoverageIgnoreStart
    /**
     * Execute DELETE request
     * @param  string $endpoint   Request endpoint
     */
    public function deleteRequest(string $endpoint): Response {
        return $this->executeRequest('DELETE', $endpoint, function(ServerRequestInterface $request) {
            return $request;
        });
    }
    // @codeCoverageIgnoreEnd

    /**
     * Execute request
     * @param  string       $method   Request method
     * @param  string       $endpoint Request endpoint
     * @param  Closure|null $builder  Closure for building request
     */
    protected function executeRequest(string $method, string $endpoint, ?Closure $builder = null): Response {
        $uri = $this->sandbox ? 'https://sandbox-api.paddle.com' : 'https://api.paddle.com';
        $uri .= '/' . trim($endpoint, " \n\r\t\v\0/");
        $request = $this->request_factory->createServerRequest($method, $uri);
        # Run the builder callback
        if ($builder) {
            $request = $builder($request);
        }
        # Add query parameters, if any
        $query = $request->getQueryParams();
        if ($query) {
            $uri = $request->getUri();
            $uri = $uri->withQuery( http_build_query($query) );
            $request = $request->withUri($uri);
        }
        # Add authorization header
        $request = $request->withHeader('Authorization', "Bearer {$this->api_key}");
        # Execute the request
        $server_response = $this->http_client->sendRequest($request);
        return new Response($server_response);
    }
}
