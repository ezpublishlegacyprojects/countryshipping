<?php
// Created on: <17-Aug-2006 13:32:49 bjorn>
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

/*! \file ezdefaultshippinghandler.php
*/

/*!
  \class eZDefaultShippingHandler ezdefaultshippinghandler.php
  \brief The class eZDefaultShippingHandler does

*/

// include_once( 'kernel/classes/ezbasket.php' );
// include_once( 'kernel/shop/classes/ezshopfunctions.php' );
// include_once( eZExtension::baseDirectory() . '/countryshipping/datatypes/ezshipping/ezshippingpricegroup.php'  );

class eZDefaultShippingHandler
{
    /*!
     Constructor
    */
    function eZDefaultShippingHandler()
    {
    }

    function getShippingInfo( $productCollectionID )
    {
        $productCollection = eZProductCollection::fetch( $productCollectionID );
        $totalShippingCostArray = array();
        $returnArray = null;

        if ( get_class( $productCollection ) == 'eZProductCollection' )
        {
            require_once( 'kernel/common/template.php' );
            $tpl = templateInit();

            $currencyCode = $productCollection->attribute( 'currency_code' );
            // If we don't have any currency, use the default currency from shop.ini
            // Example from products with the price attribute.
            if ( trim( $currencyCode ) == '' )
            {
                $shopIni = eZINI::instance( 'shop.ini' );
                $currencyCode = $shopIni->variable( 'CurrencySettings', 'PreferredCurrency' );
            }

            $countryName = eZShopFunctions::getPreferredUserCountry();
            $http = eZHTTPTool::instance();
            $orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
            $order = eZOrder::fetch( $orderID );
            if ( get_class( $order ) == 'eZOrder' and
                 $order->attribute( 'is_temporary' ) )
            {
                $accountInfo = $order->attribute( 'account_information');
                $countryName = $accountInfo['country'];
            }

            $items = $productCollection->itemList();
            if ( count( $items ) > 0 )
            {
                $priceGroupHash = eZShippingPriceGroup::fetchIdentifierHash();
                foreach ( $items as $item )
                {
                    $vatValue = $item->attribute( 'vat_value' );
                    $itemCount = $item->attribute( 'item_count' );
                    $contentObjectID = $item->attribute( 'contentobject_id' );
                    $contentObject = $item->attribute( 'contentobject' );
                    $isVATInc = $item->attribute( 'is_vat_inc' );

                    if ( get_class( $contentObject ) == 'eZContentObject' )
                    {
                        $contentObjectAttributes = $contentObject->attribute( 'contentobject_attributes' );
                        if ( trim( $currencyCode ) == '' )
                        {
                            foreach ( $contentObjectAttributes as $contentObjectAttribute )
                            {
                                $dataType = $contentObjectAttribute->dataType();

                                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                                {
                                    $priceObj = $contentObjectAttribute->content();
                                    $currencyCode = $priceObj->attribute( 'currency' );
                                    break;
                                }
                            }
                        }

                        $subTotalStandardValue = 0;
                        $subTotalAdditionalValue = 0;
                        foreach ( $contentObjectAttributes as $contentObjectAttribute )
                        {
                            $dataTypeString = $contentObjectAttribute->attribute( 'data_type_string' );
                            if ( $dataTypeString == 'ezshipping' )
                            {
                                $shippingArray = $contentObjectAttribute->attribute( 'content' );
                                foreach ( $priceGroupHash as $identifier => $shippingPriceGroupID )
                                {

                                    $standardValue = 0;
                                    $additionalValue = 0;
                                    if ( isset( $shippingArray['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['is_auto'] ) )
                                    {
                                        $isAuto = $shippingArray['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['is_auto'];
                                        if ( $isAuto == 0 )
                                        {
                                            $standardValue = $shippingArray['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['value'];
                                        }
                                        else if ( $isAuto == 1 )
                                        {
                                            $standardValue = $shippingArray['shipping_default_data'][$currencyCode][$shippingPriceGroupID]['auto_value'];
                                        }

                                        if ( isset( $shippingArray['shipping_country_data'][$countryName] ) )
                                        {
                                            $isAuto =  $shippingArray['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['is_auto'];
                                            if ( $isAuto == 0 )
                                            {
                                                $additionalValue = $shippingArray['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['value'];
                                            }
                                            else if ( $isAuto == 1 )
                                            {
                                                $additionalValue = $shippingArray['shipping_country_data'][$countryName][$currencyCode][$shippingPriceGroupID]['auto_value'];
                                            }
                                        }
                                        else
                                        {
                                            $isAuto =  $shippingArray['shipping_country_default'][$currencyCode][$shippingPriceGroupID]['is_auto'];
                                            if ( $isAuto == 0 )
                                            {
                                                $additionalValue = $shippingArray['shipping_country_default'][$currencyCode][$shippingPriceGroupID]['value'];
                                            }
                                            else if ( $isAuto == 1 )
                                            {
                                                $additionalValue = $shippingArray['shipping_country_default'][$currencyCode][$shippingPriceGroupID]['auto_value'];
                                            }
                                        }


                                        if ( $isVATInc == 0 )
                                        {
                                            $standardValue *= ( ( $vatValue / 100 ) + 1 );
                                            $additionalValue *= ( ( $vatValue / 100 ) + 1 );
                                        }


                                        switch ( $identifier )
                                        {
                                            case "first_value":
                                            {
                                                $subTotalStandardValue += $standardValue;
                                                $subTotalAdditionalValue += $additionalValue;
                                            }break;

                                            case "additional_value":
                                            {
                                                $subTotalStandardValue += ( $standardValue * abs( $itemCount ) );
                                                $subTotalAdditionalValue += ( $additionalValue * abs( $itemCount ) );
                                            }break;
                                        }
                                    }
                                }
                            }
                        }
                        $isVATInc = 1;

                        if ( isset( $totalShippingCostArray[$vatValue][$isVATInc] ) )
                        {
                            $totalShippingCostArray[$vatValue][$isVATInc] += $subTotalStandardValue + $subTotalAdditionalValue;
                        }
                        else
                        {
                            $totalShippingCostArray[$vatValue][$isVATInc] = $subTotalStandardValue + $subTotalAdditionalValue;
                        }
                    }
                }
                $totalShippingCost = 0;
                foreach ( $totalShippingCostArray as $vatValue => $totalShippingCostItem )
                {
                    foreach ( $totalShippingCostItem as $isVATInc => $shippingCostValue )
                    {
                        $tpl->setVariable( 'vat_value', $vatValue );
                        $tpl->setVariable( 'shipping_cost_value', (float)$shippingCostValue );
                        $tpl->setVariable( 'is_vat_inc', $isVATInc );
                        $templateResult = $tpl->fetch( 'design:shop/shippinghandlers/ezdefaultshippinghandler.tpl' );
                        $description = $tpl->variable( 'vat_description' );

                        $returnArray['shipping_items'][$vatValue] = array( 'description' => $description,
                                                                           'cost'        => (float)$shippingCostValue,
                                                                           'vat_value'   => $vatValue,
                                                                           'is_vat_inc'  => $isVATInc );
                        if ( $isVATInc == 0 )
                        {
                            $totalShippingCost += ( $shippingCostValue * ( ( $vatValue / 100 ) + 1 ) );
                        }
                        else
                        {
                            $totalShippingCost += $shippingCostValue;
                        }
                    }
                }
                ksort( $returnArray['shipping_items'] );

                $tpl->setVariable( 'shipping_cost_value', (float)$totalShippingCost );
                $tpl->setVariable( 'is_vat_inc', 1 );
                $templateResult = $tpl->fetch( 'design:shop/shippinghandlers/ezdefaultshippinghandler.tpl' );
                $description = $tpl->variable( 'description' );

                $returnArray['cost'] = $totalShippingCost;
                $returnArray['description'] = $description;
                $returnArray['vat_value'] = false;
                $returnArray['is_vat_inc'] = 1;
            }
        }
        return $returnArray;
    }

    function updateShippingInfo( $productCollectionID )
    {
    }

    function purgeShippingInfo( $productCollectionID )
    {
    }



}

?>
