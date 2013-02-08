<?php

/**
 *	Plugin Name: 	TNG Toolbar Links
 *	Description: 	Adds links to all the important pages in TNG from the WordPress Toolbar.
 *	Version: 		1.0
 *	Date:			2/7/13
 *	Author:			Nate Jacobs
 *	License:

  Copyright 2013 Nate Jacobs (nate@natejacobs.org)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/** 
*	TNG Toolbar Links
*
*	
*
*	@author		Nate Jacobs
*	@date		2/7/13
*	@since		1.0
*/
class TNGToolBarLinks
{

	protected $tng_url;
	
	/** 
	*	Construct Method
	*
	*	Kick things off
	*
	*	@author		Nate Jacobs
	*	@date		2/7/13
	*	@since		1.0
	*
	*	@param		null
	*/
	public function __construct()
	{
		$this->tng_url = get_option( 'mbtng_url' );

		if( $this->tng_url )
		{
			add_action( 'wp_before_admin_bar_render', array( $this, 'tng_find_menu' ) );
			add_action( 'wp_before_admin_bar_render', array( $this, 'tng_media_menu' ) );
			add_action( 'wp_before_admin_bar_render', array( $this, 'tng_info_menu' ) );
			add_action( 'init', array( $this, 'localization' ) );
			add_action( 'init', array( $this, 'github_update' ) );
		
			register_activation_hook( __FILE__, array( $this, 'activation' ) );
		}
	}
	
	/** 
 	*	Localization
 	*
 	*	Add support for localization
 	*
 	*	@author		Nate Jacobs
 	*	@date		2/7/13
 	*	@since		1.0
 	*
 	*	@param		
 	*/
 	public function localization() 
 	{
  		load_plugin_textdomain( 'tng-user-mgmt', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' ); 
	}
	
	/** 
	*	GitHub Update
	*
	*	Update plugin from GitHub.
	*
	*	@author		Nate Jacobs
	*	@date		2/8/13
	*	@since		1.0
	*
	*	@param		
	*/
	public function github_update()
	{
		if ( is_admin() ) 
		{
			include_once plugin_dir_path( __FILE__ ).'github-update.php';
			//define( 'WP_GITHUB_FORCE_UPDATE', true );
			
			$config = array(
				'slug' => plugin_basename( __FILE__ ),
				'proper_folder_name' => 'tng-toolbar-links',
				'api_url' => 'https://api.github.com/repos/jkudish/WordPress-GitHub-Plugin-Updater',
				'raw_url' => 'https://raw.github.com/jkudish/WordPress-GitHub-Plugin-Updater/master',
				'github_url' => 'https://github.com/jkudish/WordPress-GitHub-Plugin-Updater',
				'zip_url' => 'https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/zipball/master',
				'sslverify' => true,
				'requires' => '3.1',
				'tested' => '3.3',
				'readme' => 'README.md',
				'access_token' => '',
			);
		
			$up = new WP_GitHub_Updater( $config );
		}
	}
	
	/** 
	*	Activation
	*
	*	Runs the method when the plugin is activated.
	*	Verifies the TNG plugin is installed and activated
	*
	*	@author		Nate Jacobs
	*	@date		2/7/13
	*	@since		1.0
	*
	*	@param		null
	*/
	public function activation()
	{
		// checking if plugin is inactive or not installed.
		if( is_plugin_inactive( 'tng-wordpress-plugin/tng.php' ) )
		{
			// okay, tng-wp plugin is missing
			// get the data from the plugin file
			$plugin_data = get_plugin_data( __FILE__, false );
			
			// deactivate this plugin
			deactivate_plugins( plugin_basename( __FILE__ ) );
			
			// let the user know to install or activate the tng-wp integration plugin
			wp_die( "<strong>".$plugin_data['Name']." version ".$plugin_data['Version']."</strong> requires the <a href='http://wordpress.org/extend/plugins/tng-wordpress-plugin/'>TNG WordPress Integration plugin</a> to be activated. ".$plugin_data['Name']." has been deactivated. Please install and activate the Integration plugin and try again.", 'TNG User Management Activation Error', array( 'back_link' => true ) );
		}
	}

	/** 
	*	TNG Find Menu
	*
	*	Adds a menu group to the toolbar that mimics the Find drop down menu from TNG.
	*
	*	@author		Nate Jacobs
	*	@date		2/7/13
	*	@since		1.0
	*
	*	@param		object	$wp_admin_bar		
	*/
	public function tng_find_menu( $wp_admin_bar )
	{
		global $wp_admin_bar;

        $wp_admin_bar->add_node( array(
            'id'    => 'tng_find_parent',
            'title' => __( 'TNG Find', 'tng_toolbar' ),
        ));

        $wp_admin_bar->add_node( array(
            'id'    => 'tng_surnames',
            'title' => __( 'Surnames', 'tng_toolbar' ),
            'href'  => $this->tng_url.'surnames.php',
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_bookmarks',
            'title' => __( 'Bookmarks', 'tng_toolbar' ),
            'href'  => $this->tng_url.'bookmarks.php',
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_places',
            'title' => __( 'Places', 'tng_toolbar' ),
            'href'  => $this->tng_url.'places.php',
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_dates',
            'title' => __( 'Dates', 'tng_toolbar' ),
            'href'  => $this->tng_url.'anniversaries.php',
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_calendar',
            'title' => __( 'Calendar', 'tng_toolbar' ),
            'href'  => $this->tng_url.'calendar.php?m='.date( 'm' ),
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_people_search',
            'title' => __( 'Search People', 'tng_toolbar' ),
            'href'  => $this->tng_url.'searchform.php',
            'parent'=>'tng_find_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_family_search',
            'title' => __( 'Search Families', 'tng_toolbar' ),
            'href'  => $this->tng_url.'famsearchform.php',
            'parent'=>'tng_find_parent'
        ));
	}
	
	/** 
	*	TNG Media Menu
	*
	*	Adds a menu group to the toolbar that mimics the Media drop down menu from TNG.
	*
	*	@author		Nate Jacobs
	*	@date		2/7/13
	*	@since		1.0
	*
	*	@param		object	$wp_admin_bar
	*/
	public function tng_media_menu( $wp_admin_bar )
	{
		global $wp_admin_bar;
		
		$wp_admin_bar->add_node( array(
            'id'    => 'tng_media_parent',
            'title' => __( 'TNG Media', 'tng_toolbar' ),
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_photos',
            'title' => __( 'Photos', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=photos.php',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_documents',
            'title' => __( 'Documents', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=documents',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_headstones',
            'title' => __( 'Headstones', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=headstones',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_histories',
            'title' => __( 'Histories', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=histories',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_recordings',
            'title' => __( 'Recordings', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=recordings',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_videos',
            'title' => __( 'Videos', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php?mediatypeID=videos',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_albums',
            'title' => __( 'Albums', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsealbums.php',
            'parent'=>'tng_media_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_media',
            'title' => __( 'Media', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsemedia.php',
            'parent'=>'tng_media_parent'
        ));
	}
	
	/** 
	*	TNG Info Menu
	*
	*	Adds a menu group to the toolbar that mimics the Info drop down menu from TNG.
	*
	*	@author		Nate Jacobs
	*	@date		2/7/13
	*	@since		1.0
	*
	*	@param		object	$wp_admin_bar
	*/
	public function tng_info_menu( $wp_admin_bar )
	{
		global $wp_admin_bar;
		
		$wp_admin_bar->add_node( array(
            'id'    => 'tng_info_parent',
            'title' => 'TNG Info',
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_whats_new',
            'title' => __( 'What\'s New', 'tng_toolbar' ),
            'href'  => $this->tng_url.'whatsnew.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_most_wanted',
            'title' => __( 'Most Wanted', 'tng_toolbar' ),
            'href'  => $this->tng_url.'mostwanted.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_reports',
            'title' => __( 'Reports', 'tng_toolbar' ),
            'href'  => $this->tng_url.'reports.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_statistics',
            'title' => __( 'Statistics', 'tng_toolbar' ),
            'href'  => $this->tng_url.'statistics.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_trees',
            'title' => __( 'Trees', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsetrees.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_notes',
            'title' => __( 'Notes', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsenotes.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_sources',
            'title' => __( 'Sources', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browsesources.php',
            'parent'=>'tng_info_parent'
        ));
        
        $wp_admin_bar->add_node( array(
            'id'    => 'tng_repositories',
            'title' => __( 'Repositories', 'tng_toolbar' ),
            'href'  => $this->tng_url.'browserepos.php',
            'parent'=>'tng_info_parent'
        ));
	}
}
new TNGToolBarLinks();