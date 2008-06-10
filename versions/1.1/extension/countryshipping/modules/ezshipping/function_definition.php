<?php
// Created on: <25-Jul-2006 15:05:26 bjorn>
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

/*! \file function_definition.php
*/

/*!
  \class Function_definition function_definition.php
  \brief The class Function_definition does

*/

$FunctionList = array();
$FunctionList['additional_country_list'] = array( 'name' => 'additional_country_list',
                                                  'operation_types' => array( 'read' ),
                                                  'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                       'class' => 'eZShippingFunctionCollection',
                                                                       'method' => 'fetchAdditionalCountryList' ),
                                                  'parameter_type' => 'standard',
                                                  'parameters' => array( array( 'name' => 'contentclassattribute_id',
                                                                                'type' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                                                         array( 'name' => 'contentclassattribute_version',
                                                                                'type' => 'integer',
                                                                                'default' => 0,
                                                                                'required' => true ),
                                                                         array( 'name' => 'as_hash',
                                                                                'type' => 'boolean',
                                                                                'default' => true,
                                                                                'required' => false ) ) );

$FunctionList['additional_country_default'] = array( 'name' => 'additional_country_default',
                                                     'operation_types' => array( 'read' ),
                                                     'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                             'class' => 'eZShippingFunctionCollection',
                                                                             'method' => 'fetchAdditionalCountryDefault' ),
                                                     'parameter_type' => 'standard',
                                                     'parameters' => array( array( 'name' => 'contentclassattribute_id',
                                                                                   'type' => 'integer',
                                                                                   'default' => 0,
                                                                                   'required' => true ),
                                                                            array( 'name' => 'contentclassattribute_version',
                                                                                   'type' => 'integer',
                                                                                   'default' => 0,
                                                                                   'required' => true ),
                                                                            array( 'name' => 'as_hash',
                                                                                   'type' => 'boolean',
                                                                                   'default' => true,
                                                                                   'required' => false ) ) );

$FunctionList['default_currency_list'] = array( 'name' => 'default_currency_list',
                                                'operation_types' => array( 'read' ),
                                                'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                        'class' => 'eZShippingFunctionCollection',
                                                                       'method' => 'fetchDefaultCurrencyList' ),
                                                'parameter_type' => 'standard',
                                                'parameters' => array( array( 'name' => 'contentclassattribute_id',
                                                                              'type' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                                                       array( 'name' => 'contentclassattribute_version',
                                                                              'type' => 'integer',
                                                                              'default' => 0,
                                                                              'required' => true ),
                                                                       array( 'name' => 'as_hash',
                                                                              'type' => 'boolean',
                                                                              'default' => true,
                                                                              'required' => false ) ) );

$FunctionList['pricegroup_identifier_hash'] = array( 'name' => 'pricegroup_identifier_hash',
                                                     'operation_types' => array( 'read' ),
                                                     'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                             'class' => 'eZShippingFunctionCollection',
                                                                             'method' => 'fetchPriceGroupIdentifierHash' ),
                                                     'parameter_type' => 'standard',
                                                     'parameters' => array() );


$FunctionList['shipping_group_list'] = array( 'name' => 'shipping_group_list',
                                              'operation_types' => array( 'read' ),
                                              'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                      'class' => 'eZShippingFunctionCollection',
                                                                      'method' => 'fetchShippingGroupList' ),
                                              'parameter_type' => 'standard',
                                              'parameters' => array() );

$FunctionList['shipping_group'] = array( 'name' => 'shipping_group',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'include_file' => eZExtension::baseDirectory() . '/countryshipping/classes/ezshippingfunctioncollection.php',
                                                                 'class' => 'eZShippingFunctionCollection',
                                                                 'method' => 'fetchShippingGroup' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array(  array( 'name' => 'shippinggroup_id',
                                                                        'type' => 'integer',
                                                                        'default' => 0,
                                                                        'required' => true ) ) );

?>
