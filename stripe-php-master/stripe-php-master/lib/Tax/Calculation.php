<?php

// File generated from our OpenAPI spec

namespace Stripe\Tax;

/**
 * A Tax Calculation allows you to calculate the tax to collect from your customer.
 *
 * Related guide: <a href="https://stripe.com/docs/tax/custom">Calculate tax in your custom payment flow</a>
 *
 * @property null|string $id Unique identifier for the calculation.
 * @property string $object String representing the object's type. Objects of the same type share the same value.
 * @property int $amount_total Total amount after taxes in the <a href="https://stripe.com/docs/currencies#zero-decimal">smallest currency unit</a>.
 * @property string $currency Three-letter <a href="https://www.iso.org/iso-4217-currency-codes.html">ISO currency code</a>, in lowercase. Must be a <a href="https://stripe.com/docs/currencies">supported currency</a>.
 * @property null|string $customer The ID of an existing <a href="https://stripe.com/docs/api/customers/object">Customer</a> used for the resource.
 * @property \Stripe\StripeObject $customer_details
 * @property null|int $expires_at Timestamp of date at which the tax calculation will expire.
 * @property null|\Stripe\Collection<CalculationLineItem> $line_items The list of items the customer is purchasing.
 * @property bool $livemode Has the value <code>true</code> if the object exists in live mode or the value <code>false</code> if the object exists in test mode.
 * @property null|\Stripe\StripeObject $ship_from_details The details of the ship from location, such as the address.
 * @property null|\Stripe\StripeObject $shipping_cost The shipping cost details for the calculation.
 * @property int $tax_amount_exclusive The amount of tax to be collected on top of the line item prices.
 * @property int $tax_amount_inclusive The amount of tax already included in the line item prices.
 * @property \Stripe\StripeObject[] $tax_breakdown Breakdown of individual tax amounts that add up to the total.
 * @property int $tax_date Timestamp of date at which the tax rules and rates in effect applies for the calculation.
 */
class Calculation extends \Stripe\ApiResource
{
    const OBJECT_NAME = 'tax.calculation';

    /**
     * Calculates tax based on the input and returns a Tax <code>Calculation</code>
     * object.
     *
     * @param null|array $params
     * @param null|array|string $options
     *
     * @return Calculation the created resource
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public static function create($params = null, $options = null)
    {
        self::_validateParams($params);
        $url = static::classUrl();

        list($response, $opts) = static::_staticRequest('post', $url, $params, $options);
        $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        $obj->setLastResponse($response);

        return $obj;
    }

    /**
     * Retrieves a Tax <code>Calculation</code> object, if the calculation hasn’t
     * expired.
     *
     * @param array|string $id the ID of the API resource to retrieve, or an options array containing an `id` key
     * @param null|array|string $opts
     *
     * @return Calculation
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public static function retrieve($id, $opts = null)
    {
        $opts = \Stripe\Util\RequestOptions::parse($opts);
        $instance = new static($id, $opts);
        $instance->refresh();

        return $instance;
    }

    /**
     * @param string $id
     * @param null|array $params
     * @param null|array|string $opts
     *
     * @return \Stripe\Collection<CalculationLineItem> list of calculation line items
     *
     * @throws \Stripe\Exception\ApiErrorException if the request fails
     */
    public static function allLineItems($id, $params = null, $opts = null)
    {
        $url = static::resourceUrl($id) . '/line_items';
        list($response, $opts) = static::_staticRequest('get', $url, $params, $opts);
        $obj = \Stripe\Util\Util::convertToStripeObject($response->json, $opts);
        $obj->setLastResponse($response);

        return $obj;
    }
}
