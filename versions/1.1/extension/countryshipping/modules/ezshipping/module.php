<?php
// Created on: <04-Aug-2006 14:46:09 bjorn>
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

/*! \file module.php
*/

/*!
  \class Module module.php
  \brief The class Module does

*/

$Module = array( "name" => "eZShipping",
                 "variable_params" => true );

$ViewList = array();
$ViewList["grouplist"] = array( "functions" => array( 'list' ),
                                "script" => "grouplist.php",
                                "default_navigation_part" => 'ezshippingnavigationpart',
                                "params" => array() );

$ViewList["groupedit"] = array( "functions" => array( 'edit' ),
                                "script" => "groupedit.php",
                                "default_navigation_part" => 'ezshippingnavigationpart',
                                "params" => array( "ShippingGroupID" ) );

$ViewList["action"] = array( "functions" => array( 'edit' ),
                             "script" => "action.php",
                             "default_navigation_part" => 'ezshippingnavigationpart',
                             "params" => array( "ShippingGroupID" ) );

$FunctionList['list'] = array();
$FunctionList['edit'] = array();


?>
