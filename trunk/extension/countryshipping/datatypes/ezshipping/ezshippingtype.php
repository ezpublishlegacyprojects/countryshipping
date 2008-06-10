<?php
//
// Definition of eZShippingType class
//
// Created on: <21-Jul-2006 10:47:35 bjorn>
//
// Copyright (C) 1999-2008 eZ Systems AS. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/products/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

/*! \file ezshippingtype.php
*/

/*!
  \class eZShippingType ezshippingtype.php
  \brief The class eZShippingType does handle the shipping for products.

*/
// include_once( 'kernel/classes/ezdatatype.php' );
// include_once( 'kernel/shop/classes/ezcurrencydata.php' );
// include_once( 'kernel/shop/classes/ezshopfunctions.php' );


// include_once( eZExtension::baseDirectory() . "/countryshipping/datatypes/ezshipping/ezshippingpricegroup.php" );
// include_once( eZExtension::baseDirectory() . "/countryshipping/datatypes/ezshipping/ezshipping.php" );
// include_once( eZExtension::baseDirectory() . "/countryshipping/classes/ezshippinggroup.php" );

// Define the name of datatype string
// define( "EZ_DATATYPESTRING_SHIPPING", "ezshipping" );


class eZShippingType extends eZDataType
{
    const DATA_TYPE_STRING = "ezshipping";

    const CLASS_CONTENT = 'data_text5';
    const CLASS_GROUP = 'data_int1';

    const OBJECT_CONTENT = 'data_text';
    const OBJECT_GROUP = 'data_int';

    /*!
     Constructor
    */
    function eZShippingType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', 'Shipping', 'Datatype name' ) );
    }

    /*!
      Receive the custom actions from the class attibute.
    */
    function customClassAttributeHTTPAction( $http, $action, $contentClassAttribute )
    {
        $classAttributeID = $contentClassAttribute->attribute( 'id' );
        $classAttributeVersion = $contentClassAttribute->attribute( 'version' );
        $shipping = new eZShipping( $contentClassAttribute->attribute( self::CLASS_CONTENT ) );

        switch ( $action )
        {
            case "add_countries":
            {
                $countriesPostVarName = 'ContentClass' . '_ezshipping_additional_country_array_' . $classAttributeID;
                if ( $http->hasPostVariable( $countriesPostVarName ) )
                {
                    $countryArray = $http->postVariable( $countriesPostVarName );
                    $shipping->addCountry( $countryArray );
                }
            }break;

            case "remove_selected_countries":
            {
                $selectedCountriesPostVarName = 'ContentClass' . '_ezshipping_selected_country_array_' . $classAttributeID;
                if ( $http->hasPostVariable( $selectedCountriesPostVarName ) )
                {
                    $countryArray = $http->postVariable( $selectedCountriesPostVarName );
                    $shipping->removeCountries( $countryArray );
                }
            }break;

            case "add_currency":
            {
                $newCurrencyPostVarName = 'ContentClass' . '_ezshipping_new_currency_' . $classAttributeID;
                if ( $http->hasPostVariable( $newCurrencyPostVarName ) )
                {
                    $currencyCode = $http->postVariable( $newCurrencyPostVarName );
                    $shipping->addCurrency( $currencyCode );
                }
            }break;

            case "remove_selected_currency":
            {
                $selectedCurrenciesPostVarName = 'ContentClass' . '_ezshipping_selected_currency_array_' . $classAttributeID;
                if ( $http->hasPostVariable( $selectedCurrenciesPostVarName ) )
                {
                    $currencyArray = $http->postVariable( $selectedCurrenciesPostVarName );
                    $shipping->removeCurrency( $currencyArray );
                }
            }break;

            case "update_default_currency": // Dummy button to reload the page. All information will be stored.
            {
            }break;

            default:
            {
                eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, 'eZShippingType' );
            }break;

        }

        $shipping->sortCountries();
        $contentClassAttribute->setAttribute( self::CLASS_CONTENT, $shipping->serializedString() );
    }


    /*!
     \reimp
     Update the content in the shippingdatatype.
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $classAttributeID = $classAttribute->attribute( 'id' );
        $shipping = new eZShipping( $classAttribute->attribute( self::CLASS_CONTENT ) );
        $shipping->fetchAttributeHTTPInput( $http, $base, $classAttributeID );
        $classAttribute->setAttribute( self::CLASS_CONTENT, $shipping->serializedString() );

        $shippingGroupVarName = $base . '_ezshipping_shipping_group_' . $classAttributeID;
        if ( $http->hasPostVariable( $shippingGroupVarName ) )
        {
            $shippingGroupID = $http->postVariable( $shippingGroupVarName );
            if ( !is_numeric( $shippingGroupID ) or
                 $shippingGroupID <= 0 )
            {
                $shippingGroupID = -1;
            }
            $classAttribute->setAttribute( self::CLASS_GROUP, $shippingGroupID );
        }

        return true;
    }

    /*!
     Returns the content data for the given content class attribute.
    */
    function classAttributeContent( $classAttribute )
    {
        $shippingGroupID = $classAttribute->attribute( self::CLASS_GROUP );
        if ( $shippingGroupID > 0 )
        {
            $group = eZShippingGroup::fetch( $shippingGroupID );
            if ( get_class( $group ) == "eZShippingGroup" )
            {
                $shipping = new eZShipping( $group->attribute( eZShippingGroup::CONTENT ) );
            }
            else
            {
                $shipping = new eZShipping( $classAttribute->attribute( self::CLASS_CONTENT ) );
            }
        }
        else
        {
            $shipping = new eZShipping( $classAttribute->attribute( self::CLASS_CONTENT ) );
        }
        $shippingArray = $shipping->shippingWidthAutoValues();

        return $shippingArray;
    }

    /*!
     Initializes the class attribute with some data.
     \note Default implementation does nothing.
    */
    function initializeClassAttribute( $classAttribute )
    {
        $shipping = new eZShipping( $classAttribute->attribute( self::CLASS_CONTENT ) );

        $preferredCurrencyCode = eZShopFunctions::preferredCurrencyCode();
        $shipping->addCurrency( $preferredCurrencyCode );
        $classAttribute->setAttribute( self::CLASS_CONTENT, $shipping->serializedString() );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    /*!
     Initializes the object attribute with the shipping data.
    */
    function initializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        // if we're initializing a new object.
        if ( $currentVersion == null )
        {
            $contentClassAttributeID = $objectAttribute->attribute( 'contentclassattribute_id' );
            $contentClassAttribute = eZContentClassAttribute::fetch( $contentClassAttributeID );

            $objectAttribute->setAttribute( self::OBJECT_CONTENT, $contentClassAttribute->attribute( self::CLASS_CONTENT ) );
            $objectAttribute->setAttribute( 'data_int', $contentClassAttribute->attribute( self::CLASS_GROUP ) );
        }
    }

    /*!
     Returns the content data for the given content object attribute.
    */
    function objectAttributeContent( $objectAttribute )
    {
        $shippingGroupID = $objectAttribute->attribute( 'data_int' );
        if ( $shippingGroupID > 0 )
        {
            $group = eZShippingGroup::fetch( $shippingGroupID );
            if ( get_class( $group ) == "eZShippingGroup" )
            {
                $shipping = new eZShipping( $group->attribute( eZShippingGroup::CONTENT ) );
            }
            else
            {
                $shipping = new eZShipping( $objectAttribute->attribute( self::OBJECT_CONTENT ) );
            }
        }
        else
        {
            $shipping = new eZShipping( $objectAttribute->attribute( self::OBJECT_CONTENT ) );
        }
        $shippingArray = $shipping->shippingWidthAutoValues();
        return $shippingArray;
    }


    /*!
     Fetches the HTTP input for the content object attribute.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        $shipping = new eZShipping( $objectAttribute->attribute( self::OBJECT_CONTENT ) );
        $attributeID = $objectAttribute->attribute( 'id' );

        $shipping->fetchAttributeHTTPInput( $http, $base, $attributeID );
        $objectAttribute->setAttribute( self::OBJECT_CONTENT, $shipping->serializedString() );

        $shippingGroupVarName = $base . '_ezshipping_shipping_group_' . $attributeID;
        if ( $http->hasPostVariable( $shippingGroupVarName ) )
        {
            $shippingGroupID = $http->postVariable( $shippingGroupVarName );
            if ( !is_numeric( $shippingGroupID ) or
                 $shippingGroupID <= 0 )
            {
                $shippingGroupID = -1;
            }
            $objectAttribute->setAttribute( 'data_int', $shippingGroupID );
        }
        $objectAttribute->store();
    }


    /*!
     Executes a custom action for an object attribute which was defined on the web page.
    */
    function customObjectAttributeHTTPAction( $http, $action, $objectAttribute, $parameters )
    {
        $attributeID = $objectAttribute->attribute( 'id' );
        $shipping = new eZShipping( $objectAttribute->attribute( self::OBJECT_CONTENT ) );

        switch ( $action )
        {
            case "add_countries":
            {
                $countriesPostVarName = 'ContentObjectAttribute' . '_ezshipping_additional_country_array_' . $attributeID;
                if ( $http->hasPostVariable( $countriesPostVarName ) )
                {
                    $countryArray = $http->postVariable( $countriesPostVarName );
                    $shipping->addCountry( $countryArray );
                }
            }break;

            case "remove_selected_countries":
            {
                $selectedCountriesPostVarName = 'ContentObjectAttribute' . '_ezshipping_selected_country_array_' . $attributeID;
                if ( $http->hasPostVariable( $selectedCountriesPostVarName ) )
                {
                    $countryArray = $http->postVariable( $selectedCountriesPostVarName );
                    $shipping->removeCountries( $countryArray );
                }
            }break;

            case "add_currency":
            {
                $newCurrencyPostVarName = 'ContentObjectAttribute' . '_ezshipping_new_currency_' . $attributeID;
                if ( $http->hasPostVariable( $newCurrencyPostVarName ) )
                {
                    $currencyCode = $http->postVariable( $newCurrencyPostVarName );
                    $shipping->addCurrency( $currencyCode );
                }
            }break;

            case "remove_selected_currency":
            {
                $selectedCurrenciesPostVarName = 'ContentObjectAttribute' . '_ezshipping_selected_currency_array_' . $attributeID;
                if ( $http->hasPostVariable( $selectedCurrenciesPostVarName ) )
                {
                    $currencyArray = $http->postVariable( $selectedCurrenciesPostVarName );
                    $shipping->removeCurrency( $currencyArray );
                }
            }break;

            case "update_default_currency":
            case "update_shipping_group":
            {
                // do nothing.
            }break;

            default:
            {
                eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, 'eZEnumType' );
            }break;

        }

        $shipping->sortCountries();
        $objectAttribute->setAttribute( self::OBJECT_CONTENT, $shipping->serializedString() );
        $objectAttribute->store();
    }

    /*!
     Returns the meta data used for storing search indices.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( self::OBJECT_CONTENT );
    }

    /*!
     Returns the text.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( self::OBJECT_CONTENT );
    }
}

eZDataType::register( eZShippingType::DATA_TYPE_STRING, "eZShippingType" );

?>
