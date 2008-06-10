<?php
// Created on: <25-Jul-2006 15:17:38 bjorn>
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

/*! \file ezshippingfunctioncollection.php
*/

/*!
  \class eZShippingFunctionCollection ezshippingfunctioncollection.php
  \brief The class eZShippingFunctionCollection does

*/
// include_once( 'kernel/classes/ezpersistentobject.php' );
// include_once( eZExtension::baseDirectory() . '/countryshipping/datatypes/ezshipping/ezshippingpricegroup.php' );
// include_once( eZExtension::baseDirectory() . '/countryshipping/classes/ezshippinggroup.php' );

class eZShippingFunctionCollection
{
    /*!
     Constructor
    */
    function eZShippingFunctionCollection()
    {
    }

    /*!
      Fetch a list with additional countries for a class attribute.
    */
    function fetchAdditionalCountryList( $classAttributeID, $classAttributeVersion, $asHash = true )
    {
        $conditions = array( 'contentclassattribute_id' => $classAttributeID,
                             'contentclassattribute_version' => $classAttributeVersion );
        $orderBy = array( 'country' => 'asc',
                          'currency_code' => 'asc' );
        $objectArray = eZShippingClassCountryData::fetchList( $conditions, true, false, false, $asHash, $orderBy );
        $returnArray = array( 'result' => $objectArray );
        return $returnArray;
    }

    /*!
      Fetch a list with additional default countries for a class attribute.
    */
    function fetchAdditionalCountryDefault( $classAttributeID, $classAttributeVersion, $asHash = true )
    {
        $conditions = array( 'contentclassattribute_id' => $classAttributeID,
                             'contentclassattribute_version' => $classAttributeVersion );
        $orderBy = array( 'currency_code' => 'asc' );
        $objectArray = eZShippingClassCountryDefault::fetchList( $conditions, true, false, false, $asHash, $orderBy );
        $returnArray = array( 'result' => $objectArray );
        return $returnArray;
    }

    /*!
      Fetch a list with additional countries for a class attribute.
    */
    function fetchDefaultCurrencyList( $classAttributeID, $classAttributeVersion, $asHash = true )
    {
        $conditions = array( 'contentclassattribute_id' => $classAttributeID,
                             'contentclassattribute_version' => $classAttributeVersion );
        $orderBy = array( 'currency_code' => 'asc' );
        $objectArray = eZShippingClassDefaultData::fetchList( $conditions, true, false, false, $asHash, $orderBy );
        $returnArray = array( 'result' => $objectArray );
        return $returnArray;
    }


    function fetchPriceGroupIdentifierHash()
    {
        $objectArray = eZShippingPriceGroup::fetchIdentifierHash();
        $returnArray = array( 'result' => $objectArray );
        return $returnArray;
    }

    function fetchShippingGroupList()
    {
        $objectArray = eZShippingGroup::fetchList();
        $returnArray = array( 'result' => $objectArray );
        return $returnArray;
    }

    function fetchShippingGroup( $id )
    {
        $object = eZShippingGroup::fetch( $id );
        $returnArray = array( 'result' => $object );
        return $returnArray;
    }

}

?>
