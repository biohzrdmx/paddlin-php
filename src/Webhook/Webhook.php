<?php

declare(strict_types = 1);

/**
 * Paddlin
 * Interact with the Paddle Billing API
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @license MIT
 */

namespace Paddlin\Webhook;

use Exception;
use RuntimeException;

use Psr\Http\Message\ServerRequestInterface;

class Webhook {

    /**
     * ServerRequestInterface implementation
     */
    protected ServerRequestInterface $request;

    /**
     * Webhook secret
     */
    protected string $secret;

    /**
     * Webhook payload
     */
    protected string $payload;

    /**
     * Constructor
     * @param ServerRequestInterface $request ServerRequestInterface implementation
     * @param string                 $secret  Webhook secret
     */
    public function __construct(ServerRequestInterface $request, string $secret) {
        $this->request = $request;
        $this->secret = $secret;
        $this->payload = $this->request->getBody()->getContents();
    }

    /**
     * Verify webhook
     */
    public function verify(): bool {
        $signature = $this->request->getHeaderLine('Paddle-Signature');
        list($ts, $h1) = explode(';', $signature);
        $ts = explode('=', $ts);
        $h1 = explode('=', $h1);
        $ts = array_pop($ts);
        $h1 = array_pop($h1);
        if ($ts && $h1) {
            $check = hash_hmac('sha256', $ts . ':' . $this->payload, $this->secret);
            return hash_equals($h1, $check);
        }
        return false; //@codeCoverageIgnore
    }

    /**
     * Get webhook Notification
     */
    public function getNotification(): ?Notification {
        try {
            $data = json_decode($this->payload, true, flags: JSON_THROW_ON_ERROR);
            return $data ? new Notification($data) : null;
        } catch (Exception $e) {
            throw new RuntimeException('Invalid webhook payload', 0, $e);
        }
    }
}
