<?php
// Created on: <22-May-2007 10:17:18 bjorn>
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

/*! \file ezinfo.php
*/

/*!
  \class CountryShippingInfo ezinfo.php
  \brief The class CountryShippingInfo does

*/

class CountryShippingInfo
{
    /*!
     Constructor
    */
    function CountryShippingInfo()
    {
    }


    /*!
     Return extension information.
    */
    function info()
    {
        return array( 'name' => "Country shipping",
                      'version' => "1.1",
                      'copyright' => "Copyright (C) 1999-2008 eZ Systems AS",
                      'license' => "GNU General Public License v2.0"
                      );
    }
}

?>
