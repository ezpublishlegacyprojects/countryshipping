<?php
// Created on: <04-Aug-2006 14:49:29 bjorn>
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

/*! \file ezshippinggroup.php
*/

/*!
  \class eZShippingGroup ezshippinggroup.php
  \brief The class eZShippingGroup does

*/

// include_once( 'kernel/shop/classes/ezshopfunctions.php' );

// include_once( eZExtension::baseDirectory() . '/countryshipping/datatypes/ezshipping/ezshipping.php' );

class eZShippingGroup extends eZPersistentObject
{
    const CONTENT = 'data_text';

    /*!
     Constructor
    */
    function eZShippingGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZShippingGroup class.
    */
    static function definition()
    {
        return array( "fields" => array( "id" => array( 'name' => 'ID',
                                                        'datatype' => 'integer',
                                                        'default' => 0,
                                                        'required' => true ),
                                         "name" => array( 'name' => "Name",
                                                          'datatype' => 'string',
                                                          'default' => '',
                                                          'required' => false ),
                                         "data_text" => array( 'name' => "DataText",
                                                               'datatype' => 'string',
                                                               'default' => '',
                                                               'required' => false ) ),
                      'function_attributes' => array( 'group_list' => 'groupList' ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZShippingGroup",
                      "name" => "ezshipping_group" );
    }

    static function fetch( $id )
    {
        $shippingGroup = eZPersistentObject::fetchObject( eZShippingGroup::definition(),
                                                          null, array( "id" => $id ),
                                                          true );
        return $shippingGroup;
    }

    static function fetchList( $conditions = null, $asObject = true, $offset = false, $limit = false )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
        {
            $limitation = array( 'offset' => $offset,
                                 'limit' => $limit );
        }

        $shippingGroupList = eZPersistentObject::fetchObjectList( eZShippingGroup::definition(),
                                                                  null,
                                                                  $conditions,
                                                                  null,
                                                                  $limitation,
                                                                  $asObject );
        return $shippingGroupList;
    }

    function groupList()
    {
        $returnValue = $this->attribute( 'data_text' );
        $returnArray = array();
        if ( trim( $returnValue ) != "" )
        {
            $shipping = new eZShipping( $returnValue );
            $returnArray = $shipping->shippingWidthAutoValues();
        }
        return $returnArray;
    }

    function create()
    {
        $param = array();
        $shipping = new eZShipping( $param );

        $preferredCurrencyCode = eZShopFunctions::preferredCurrencyCode();
        $shipping->addCurrency( $preferredCurrencyCode );

        $dataText = $shipping->serializedString();
        $row = array( 'data_text' => $dataText );
        $object = new eZShippingGroup( $row );
        return $object;
    }
}

?>
