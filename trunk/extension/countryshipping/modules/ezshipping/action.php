<?php
// Created on: <04-Aug-2006 16:48:19 bjorn>
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

/*! \file action.php
*/

// include_once( "lib/ezutils/classes/ezhttptool.php" );

// include_once( eZExtension::baseDirectory() . "/countryshipping/datatypes/ezshipping/ezshipping.php" );
// include_once( eZExtension::baseDirectory() . "/countryshipping/classes/ezshippinggroup.php" );

$module = $Params["Module"];
$http = eZHTTPTool::instance();

$shippingGroupID = (int)$Params['ShippingGroupID'];
if ( !is_numeric( $shippingGroupID ) )
{
    $shippingGroupID = '';
}

$viewMode = 'grouplist';
if ( $http->hasPostVariable( 'ViewMode' ) )
{
    $viewMode = $http->postVariable( 'ViewMode' );
}

$actionValue = 'grouplist';
if ( $http->hasPostVariable( 'ActionValue' ) )
{
    $actionValue = $http->postVariable( 'ActionValue' );
}

switch ( $actionValue )
{
    case "grouplist":
    {
        if ( $http->hasPostVariable( 'NewShippingGroup' ) )
        {
            $group = eZShippingGroup::create();
            $group->store();
            $groupID = $group->attribute( 'id' );
            $redirectURI = '/ezshipping/groupedit/' . $groupID;
            $module->redirectTo( $redirectURI );
            return;
        }

        if ( $http->hasPostVariable( 'RemoveShippingGroup' ) and
             $http->hasPostVariable( 'ShippingGroupArray' ) )
        {
            $shippingGroupArray = $http->postVariable( 'ShippingGroupArray' );
            if ( count( $shippingGroupArray ) > 0 )
            {
                foreach ( $shippingGroupArray as $shippingGroupID )
                {
                    $shippingGroup = eZShippingGroup::fetch( $shippingGroupID );
                    $shippingGroup->remove();
                }
            }
        }
    }break;

    case "groupedit":
    {
        if ( $http->hasPostVariable( 'DiscardButton' ) )
        {
            $module->redirectTo( '/ezshipping/grouplist' );
            return;
        }

        $group = eZShippingGroup::fetch( $shippingGroupID );
        if ( $http->hasPostVariable( 'ShippingGroupName' ) )
        {
            $shippingGroupName = $http->postVariable( 'ShippingGroupName' );
            if ( trim( $shippingGroupName ) != "" )
            {
                $group->setAttribute( 'name', $shippingGroupName );
            }
            else
            {
                $group->setAttribute( 'name', 'Shipping #' . $shippingGroupID  );
            }
        }

        $base = 'ShippingGroup';
        $shipping = new eZShipping( $group->attribute( 'data_text' ) );
        $shipping->fetchAttributeHTTPInput( $http, $base, $shippingGroupID );

        $customActionButtomName = 'CustomActionButton';
        if ( $http->hasPostVariable( $customActionButtomName ) )
        {
            $customActionButtomArray = $http->postVariable( $customActionButtomName );

            foreach ( array_keys( $customActionButtomArray  ) as $customActionButtomValue )
            {
                $action = '';
                if ( preg_match( "/^(\d+)_(.+)$/", $customActionButtomValue, $matchArray ) )
                {
                    $action = $matchArray[2];
                }

                switch ( $action )
                {
                    case "add_countries":
                    {
                        $countriesPostVarName = 'ShippingGroup' . '_ezshipping_additional_country_array_' . $shippingGroupID;
                        if ( $http->hasPostVariable( $countriesPostVarName ) )
                        {
                            $countryArray = $http->postVariable( $countriesPostVarName );
                            $shipping->addCountry( $countryArray );
                        }
                    }break;

                    case "remove_selected_countries":
                    {
                        $selectedCountriesPostVarName = 'ShippingGroup' . '_ezshipping_selected_country_array_' . $shippingGroupID;
                        if ( $http->hasPostVariable( $selectedCountriesPostVarName ) )
                        {
                            $countryArray = $http->postVariable( $selectedCountriesPostVarName );
                            $shipping->removeCountries( $countryArray );
                        }
                    }break;

                    case "add_currency":
                    {
                        $newCurrencyPostVarName = 'ShippingGroup' . '_ezshipping_new_currency_' . $shippingGroupID;
                        if ( $http->hasPostVariable( $newCurrencyPostVarName ) )
                        {
                            $currencyCode = $http->postVariable( $newCurrencyPostVarName );
                            $shipping->addCurrency( $currencyCode );
                        }
                    }break;

                    case "remove_selected_currency":
                    {
                        $selectedCurrenciesPostVarName = 'ShippingGroup' . '_ezshipping_selected_currency_array_' . $shippingGroupID;
                        if ( $http->hasPostVariable( $selectedCurrenciesPostVarName ) )
                        {
                            $currencyArray = $http->postVariable( $selectedCurrenciesPostVarName );
                            $shipping->removeCurrency( $currencyArray );
                        }
                    }break;

                    case "update_default_currency": // do nothing, is updated in  $shipping->fetchAttributeHTTPInput()
                    {
                    }break;

                    default:
                    {
                        eZDebug::writeError( 'Unknown custom HTTP action: ' . $action, 'eZEnumType' );
                    }break;
                }
            }
        }
        $group->setAttribute( 'data_text', $shipping->serializedString() );
        $group->store();

        if ( $http->hasPostVariable( 'RedirectURL' ) )
        {
            $module->redirectTo( $http->postVariable( 'RedirectURL' ) );
        }
        return;

    }break;
}

$module->redirectTo( 'ezshipping/grouplist' );

?>
