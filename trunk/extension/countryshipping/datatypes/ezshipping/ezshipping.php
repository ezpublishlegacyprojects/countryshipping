<?php
// Created on: <04-Aug-2006 10:05:32 bjorn>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: n.n.n
// BUILD VERSION: nnnnn
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

/*! \file ezshipping.php
*/

/*!
  \class eZShipping ezshipping.php
  \brief The class eZShipping does

*/

// include_once( 'kernel/shop/classes/ezcurrencydata.php' );
// include_once( 'kernel/shop/classes/ezshopfunctions.php' );

// include_once( eZExtension::baseDirectory() . "/countryshipping/datatypes/ezshipping/ezshippingpricegroup.php" );

class eZShipping
{
    /*!
     Constructor
    */
    function eZShipping( $serializedString )
    {
        if ( is_string( $serializedString ) )
        {
            $this->ShippingValues = unserialize( $serializedString );
        }
        else if ( is_array( $serializedString ) )
        {
            $this->ShippingValues = $serializedString;
        }
    }

    function addCountry( $countryArray )
    {
        if ( count( $countryArray ) > 0 )
        {
            $shippingPriceGroups = eZShippingPriceGroup::fetchList();
            $value = 0;

            // make a checkarray to check if the item allready exists in shippingclasscountrydata.
            foreach ( $countryArray as $countryName )
            {

                if ( isset( $this->ShippingValues['shipping_default_data'] ) and
                     count( $this->ShippingValues['shipping_default_data'] ) > 0 and
                     count( $shippingPriceGroups ) > 0 )
                {
                    foreach ( array_keys( $this->ShippingValues['shipping_default_data'] ) as $currencyCode )
                    {
                        foreach ( $shippingPriceGroups as $shippingPriceGroup )
                        {
                            $shippingPriceGroupID = $shippingPriceGroup->attribute( 'id' );
                            if ( !isset( $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID] ) and
                                 $countryName != "" )
                            {
                                $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['value'] = $value;
                            }
                        }
                    }
                }
            }
        }
    }

    function removeCountries( $countryArray )
    {
        if ( count( $countryArray ) > 0 )
        {
            foreach ( $countryArray as $countryName )
            {
                unset( $this->ShippingValues['shipping_country_data'][$countryName] );
            }
        }
    }

    function addCurrency( $currencyCode )
    {
        $value = 0;
        $preferredCurrencyCode = $this->defaultCurrencyCode();

        $shippingPriceGroups = eZShippingPriceGroup::fetchList();
        $conditions = null;
        $asObject = true;
        $offset = false;
        $limit = false;
        $asHash = true;
        $currencies = eZCurrencyData::FetchList( $conditions, $asObject, $offset, $limit, $asHash );
        $defaultAutoRateValue = 1;
        if ( isset( $currencies[$preferredCurrencyCode] ) and is_object( $currencies[$preferredCurrencyCode] ) )
        {
            $defaultAutoRateValue = $currencies[$preferredCurrencyCode]->attribute( 'rate_value' );
        }

        foreach ( $shippingPriceGroups as $shippingPriceGroup )
        {
            $shippingPriceGroupID = $shippingPriceGroup->attribute( 'id' );
            // add column for shipping data.
            if ( !isset( $this->ShippingValues['shipping_default_data'][$currencyCode][$shippingPriceGroupID] ) )
            {
                $value = 0;
                if ( isset( $currencies[$currencyCode] ) and
                     is_object( $currencies[$currencyCode] ) and
                     isset( $this->ShippingValues['shipping_default_data'][$preferredCurrencyCode][$shippingPriceGroupID]['value'] ) )
                {
                    $autoRateValue = $currencies[$currencyCode]->attribute( 'rate_value' );
                    $defaultValue = $this->ShippingValues['shipping_default_data'][$preferredCurrencyCode][$shippingPriceGroupID]['value'];
                    $value = round( $defaultValue / $defaultAutoRateValue * $autoRateValue, 2 );
                }
                $this->ShippingValues['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['value'] = $value;
                $this->ShippingValues['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['is_auto'] = 0;
            }

            // add column for country default data.
            if ( !isset( $this->ShippingValues['shipping_country_default'][$currencyCode][$shippingPriceGroupID] ) )
            {
                $value = 0;
                if ( isset( $currencies[$currencyCode] ) and
                     is_object( $currencies[$currencyCode] ) and
                     isset( $this->ShippingValues['shipping_country_default'][$preferredCurrencyCode][$shippingPriceGroupID]['value'] ) )
                {
                    $autoRateValue = $currencies[$currencyCode]->attribute( 'rate_value' );
                    $defaultValue = $this->ShippingValues['shipping_country_default'][$preferredCurrencyCode][$shippingPriceGroupID]['value'];
                    $value = round( $defaultValue / $defaultAutoRateValue * $autoRateValue, 2 );
                }
                $this->ShippingValues['shipping_country_default'][$currencyCode][$shippingPriceGroupID]['value'] = $value;
                $this->ShippingValues['shipping_country_default'][$currencyCode][$shippingPriceGroupID]['is_auto'] = 0;
            }

            if ( isset( $this->ShippingValues['shipping_country_data'] ) and
                 count( $this->ShippingValues['shipping_country_data'] ) > 0 )
            {
                foreach ( array_keys( $this->ShippingValues['shipping_country_data'] ) as $countryName )
                {
                    foreach ( $shippingPriceGroups as $shippingPriceGroup )
                    {
                        $shippingPriceGroupID = $shippingPriceGroup->attribute( 'id' );

                        // add column for each country data item.
                        if ( !isset( $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID] ) )
                        {
                            $value = 0;
                            if ( isset( $currencies[$currencyCode] ) and
                                 is_object( $currencies[$currencyCode] ) and
                                 isset( $this->ShippingValues['shipping_country_data'][$countryName][$preferredCurrencyCode][$shippingPriceGroupID]['value'] ) )
                            {
                                $autoRateValue = $currencies[$currencyCode]->attribute( 'rate_value' );
                                $defaultValue = $this->ShippingValues['shipping_country_data'][$countryName][$preferredCurrencyCode][$shippingPriceGroupID]['value'];
                                $value = round( $defaultValue / $defaultAutoRateValue * $autoRateValue, 2 );
                            }
                            $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['value'] = $value;
                            $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['is_auto'] = 0;
                        }
                    }
                }
            }
        }
    }

    function removeCurrency( $currencyArray )
    {
        if ( count( $currencyArray ) > 0 )
        {
            foreach ( $currencyArray as $currencyCode )
            {
                if ( isset( $this->ShippingValues['shipping_default_data'][$currencyCode] ) )
                {
                    unset( $this->ShippingValues['shipping_default_data'][$currencyCode] );
                }

                if ( isset( $this->ShippingValues['shipping_country_default'][$currencyCode] ) )
                {
                    unset( $this->ShippingValues['shipping_country_default'][$currencyCode] );
                }

                if ( isset( $this->ShippingValues['shipping_country_data'] ) and
                     is_array( $this->ShippingValues['shipping_country_data'] ) )
                {
                    foreach ( array_keys( $this->ShippingValues['shipping_country_data'] ) as $countryName )
                    {
                        if ( isset( $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode] ) )
                        {
                            unset( $this->ShippingValues['shipping_country_data'][$countryName][$currencyCode] );
                        }
                    }
                }
            }
        }
    }

    function sortCountries()
    {
        if ( isset( $this->ShippingValues['shipping_country_data'] ) )
        {
            $shippingCountryDataArray = $this->ShippingValues['shipping_country_data'];
            ksort( $shippingCountryDataArray );
            unset( $this->ShippingValues['shipping_country_data'] );
            $this->ShippingValues['shipping_country_data'] = $shippingCountryDataArray;
        }
    }

    function serializedString()
    {
        return serialize( $this->ShippingValues );
    }

    function shippingArray()
    {
        return $this->ShippingValues;
    }

    function shippingWidthAutoValues()
    {
        $value = 0;
        $preferredCurrencyCode = $this->defaultCurrencyCode();
        $shippingPriceGroups = eZShippingPriceGroup::fetchList();

        $shippingPriceGroupArray = array();
        foreach ( $shippingPriceGroups as $shippingPriceGroup )
        {
            $shippingPriceGroupArray[] = $shippingPriceGroup->attribute( 'id' );
        }

        $conditions = null;
        $asObject = true;
        $offset = false;
        $limit = false;
        $asHash = true;
        $currencies = eZCurrencyData::FetchList( $conditions, $asObject, $offset, $limit, $asHash );
        $currencyArray = array_keys( $currencies );

        $defaultAutoRateValue = 1;
        if ( isset( $currencies[$preferredCurrencyCode] ) and is_object( $currencies[$preferredCurrencyCode] ) )
        {
            $defaultAutoRateValue = $currencies[$preferredCurrencyCode]->attribute( 'rate_value' );
        }

        $shippingValues = $this->ShippingValues;
        foreach ( $shippingValues as $groupName => $contentArray )
        {
            switch ( $groupName )
            {
                case "shipping_default_data":
                case "shipping_country_default":
                {
                    foreach ( $currencyArray as $currencyCode  )
                    {
                        $autoRateValue = $currencies[$currencyCode]->attribute( 'rate_value' );
                        foreach ( $shippingPriceGroupArray as $shippingPriceGroupID )
                        {
                            if ( !isset( $this->ShippingValues[$groupName][$currencyCode][$shippingPriceGroupID]['value'] ) )
                            {
                                $autoValue = 0;
                                if ( isset( $currencies[$currencyCode] ) and
                                     is_object( $currencies[$currencyCode] ) and
                                     isset( $this->ShippingValues[$groupName][$preferredCurrencyCode][$shippingPriceGroupID]['value'] ) )
                                {
                                    $defaultValue = $this->ShippingValues[$groupName][$preferredCurrencyCode][$shippingPriceGroupID]['value'];
                                    $autoValue = round( $defaultValue / $defaultAutoRateValue * $autoRateValue, 2 );
                                }

                                $this->ShippingValues[$groupName][$currencyCode][$shippingPriceGroupID]['auto_value'] = $autoValue;
                                $this->ShippingValues[$groupName][$currencyCode][$shippingPriceGroupID]['is_auto'] = 1;
                            }
                        }
                    }
                }break;

                case "shipping_country_data":
                {
                    foreach ( array_keys( $contentArray ) as $countryName )
                    {
                        foreach ( $currencyArray as $currencyCode  )
                        {
                            $autoRateValue = $currencies[$currencyCode]->attribute( 'rate_value' );
                            foreach ( $shippingPriceGroupArray as $shippingPriceGroupID )
                            {
                                if ( !isset( $this->ShippingValues[$groupName][$countryName][$currencyCode][$shippingPriceGroupID]['value'] ) )
                                {
                                    $autoValue = 0;
                                    if ( isset( $currencies[$currencyCode] ) and
                                         is_object( $currencies[$currencyCode] ) and
                                         isset( $this->ShippingValues[$groupName][$countryName][$preferredCurrencyCode][$shippingPriceGroupID]['value'] ) )
                                    {
                                        $defaultValue = $this->ShippingValues[$groupName][$countryName][$preferredCurrencyCode][$shippingPriceGroupID]['value'];
                                        $autoValue = round( $defaultValue / $defaultAutoRateValue * $autoRateValue, 2 );
                                    }

                                    $this->ShippingValues[$groupName][$countryName][$currencyCode][$shippingPriceGroupID]['auto_value'] = $autoValue;
                                    $this->ShippingValues[$groupName][$countryName][$currencyCode][$shippingPriceGroupID]['is_auto'] = 1;
                                }
                            }
                        }
                    }

                }break;
            }
        }
        return $this->ShippingValues;
    }

    function defaultCurrencyCode()
    {
        $currencyCode = eZShopFunctions::preferredCurrencyCode();
        if ( isset( $this->ShippingValues['default_currency']['code'] ) )
        {
            $currencyCode = $this->ShippingValues['default_currency']['code'];
        }
        else
        {
            $this->ShippingValues['default_currency']['code'] = $currencyCode;
        }
        return $currencyCode;
    }

    function shippingDefaultDataArray()
    {
        $returnArray = array();
        if ( isset( $this->ShippingValues['shipping_default_data'] ) )
        {
            $returnArray = $this->ShippingValues['shipping_default_data'];
        }
        return $returnArray;
    }

    function shippingCountryDefaultArray()
    {
        $returnArray = array();
        if ( isset( $this->ShippingValues['shipping_country_default'] ) )
        {
            $returnArray = $this->ShippingValues['shipping_country_default'];
        }
        return $returnArray;
    }

    function shippingCountryDataArray()
    {
        $returnArray = array();
        if ( isset( $this->ShippingValues['shipping_country_data'] ) )
        {
            $returnArray = $this->ShippingValues['shipping_country_data'];
        }
        return $returnArray;
    }

    function setDefaultDataValue( $code, $shippingPriceGroupID, $value )
    {
        $this->ShippingValues['shipping_default_data'][$code][$shippingPriceGroupID]['value'] = floatval( $value );
        $this->ShippingValues['shipping_default_data'][$code][$shippingPriceGroupID]['is_auto'] = 0;
    }

    function setCountryDefaultValue( $code, $shippingPriceGroupID, $value )
    {
        $this->ShippingValues['shipping_country_default'][$code][$shippingPriceGroupID]['value'] = floatval( $value );
        $this->ShippingValues['shipping_country_default'][$code][$shippingPriceGroupID]['is_auto'] = 0;
    }

    function setCountryDataValue( $country, $code, $shippingPriceGroupID, $value )
    {
        $this->ShippingValues['shipping_country_data'][$country][$code][$shippingPriceGroupID]['value'] = floatval( $value );
        $this->ShippingValues['shipping_country_data'][$country][$code][$shippingPriceGroupID]['is_auto'] = 0;
    }

    function setDefaultCurrencyCode( $value )
    {
        $this->ShippingValues['default_currency']['code'] = $value;
    }


    function fetchAttributeHTTPInput( &$http, $base, $attributeID )
    {
        $conditions = null;
        $asObject = false;
        $shippingPriceGroups = eZShippingPriceGroup::fetchList( $conditions, $asObject );
        $shippingPriceGroupArray = array();
        foreach ( $shippingPriceGroups as $shippingPriceGroup )
        {
            $shippingPriceGroupArray[] = $shippingPriceGroup['id'];
        }

        $conditions = null;
        $asObject = true;
        $offset = false;
        $limit = false;
        $asHash = true;
        $currencies = eZCurrencyData::FetchList( $conditions, $asObject, $offset, $limit, $asHash );
        $variableName = $base . '_ezshipping_default_currency_' . $attributeID;
        if ( $http->hasPostVariable( $variableName ) )
        {
            $defaultCurrencyCode = $http->postVariable( $variableName );
            if ( in_array( $defaultCurrencyCode, array_keys( $currencies ) ) )
            {
                $this->setDefaultCurrencyCode( $defaultCurrencyCode );
            }
        }

        $currencyValueArray = array();
        if ( count( $currencies ) > 0 )
        {
            $countryDefaultValue = array();
            $shippingDefaultDataArray = $this->shippingDefaultDataArray();
            $shippingCountryDefaultArray = $this->shippingCountryDefaultArray();

            foreach ( $shippingDefaultDataArray as $code => $priceGroupArray )
            {
                $currencyValueArray[] = $code;
                // store the different default values for the shipping.
                foreach ( $shippingPriceGroupArray as $shippingPriceGroupID )
                {
                    if ( isset( $shippingDefaultDataArray[$code][$shippingPriceGroupID] ) )
                    {
                        $variableName = $base . '_ezshipping_currency_value_' . $code . '_' . $shippingPriceGroupID . '_' . $attributeID;
                        if ( $http->hasPostVariable( $variableName ) )
                        {
                            $defaultCurrencyValue = $http->postVariable( $variableName );
                            if ( is_numeric( $defaultCurrencyValue ) )
                            {
                                $this->setDefaultDataValue( $code, $shippingPriceGroupID, $defaultCurrencyValue );
                            }
                        }
                    }
                    else
                    {
                        $this->setDefaultDataValue( $code, $shippingPriceGroupID, 0 );
                    }

                    if ( isset( $shippingCountryDefaultArray[$code][$shippingPriceGroupID] ) )
                    {
                        $variableName = $base . '_ezshipping_country_default_value_' . $code . '_' . $shippingPriceGroupID . '_' . $attributeID;
                        if ( $http->hasPostVariable( $variableName ) )
                        {
                            $countryDefaultValue = $http->postVariable( $variableName );
                            if ( is_numeric( $countryDefaultValue ) )
                            {
                                $this->setCountryDefaultValue( $code, $shippingPriceGroupID, $countryDefaultValue );
                            }
                        }
                    }
                    else
                    {
                        $this->setCountryDefaultValue( $code, $shippingPriceGroupID, 0 );
                    }
                }
            }

            $shippingCountryDataArray = $this->shippingCountryDataArray();
            foreach ( $shippingCountryDataArray as $country => $currencyArray )
            {
                foreach ( $currencyArray as $code => $shippingPriceGroup )
                {
                    foreach ( $shippingPriceGroupArray as $shippingPriceGroupID )
                    {
                        if ( isset( $currencyArray[$code][$shippingPriceGroupID] ) )
                        {
                            $variableName = $base . '_ezshipping_country_value_' . $code . '_' . $shippingPriceGroupID . '_' . $attributeID;
                            if ( $http->hasPostVariable( $variableName ) )
                            {
                                $countryValueArray = $http->postVariable( $variableName );
                                if ( isset( $countryValueArray[$country] ) )
                                {
                                    $countryValue = $countryValueArray[$country];
                                    if ( is_numeric( $countryValue ) )
                                    {
                                        $this->setCountryDataValue( $country, $code, $shippingPriceGroupID, $countryValue );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }


    var $ShippingValues;
}

?>
