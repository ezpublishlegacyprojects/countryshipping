<?php
// Created on: <25-Jul-2006 11:28:59 bjorn>
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

/*!
  \class eZShippingPriceGroup ezshippingpricegroup.php
  \brief The class eZShippingPriceGroup does

*/

class eZShippingPriceGroup extends eZPersistentObject
{
    /*!
     Constructor
    */
    function eZShippingPriceGroup( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \return the persistent object definition for the eZShippingPriceGroup class.
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
                                                          'required' => true ),
                                         "identifier" => array( 'name' => "Identifier",
                                                                          'datatype' => 'string',
                                                                          'default' => '',
                                                                          'required' => true ) ),
                      'function_attributes' => array(),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "class_name" => "eZShippingPriceGroup",
                      "name" => "ezshipping_pricegroup" );
    }

    static function fetch( $id )
    {
        $shippingPriceGroup = eZPersistentObject::fetchObject( eZShippingPriceGroup::definition(),
                                                    null, array( "id" => $id ),
                                                    true );
        return $shippingPriceGroup;
    }

    static function fetchList( $conditions = null, $asObject = true, $offset = false, $limit = false )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
        {
            $limitation = array( 'offset' => $offset,
                                 'limit' => $limit );
        }

        $shippingPriceGroupList = eZPersistentObject::fetchObjectList( eZShippingPriceGroup::definition(),
                                                                       null,
                                                                       $conditions,
                                                                       null,
                                                                       $limitation,
                                                                       $asObject );
        return $shippingPriceGroupList;
    }

    static function fetchIdentifierHash()
    {
        $asObject = false;
        $shippingPriceGroupList = eZPersistentObject::fetchObjectList( eZShippingPriceGroup::definition(),
                                                                       null,
                                                                       null,
                                                                       null,
                                                                       null,
                                                                       $asObject );
        $returnArray = array();
        if ( count( $shippingPriceGroupList ) )
        {
            foreach ( $shippingPriceGroupList as $shippingPriceGroup )
            {
                $returnArray[$shippingPriceGroup['identifier']] = $shippingPriceGroup['id'];
            }
        }
        return $returnArray;
    }

}

?>
