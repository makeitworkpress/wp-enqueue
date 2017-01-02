<?php
/**
 * Class wrapper for enqueing scripts and styles
 *
 * @author Michiel Tramper - https://michieltramper.com & https://www.makeitworkpress.com
 * @todo Built include/exclude for front-end enqueueing based on is_x parameters
 */
defined( 'ABSPATH' ) or die( 'Go eat veggies!' );

class MT_WP_Enqueue {
    
    /**
     * Set the initial state of the class
     *
     * @param array $assets The array with the assets, namely scripts or styles, to be enqueued
     */
    public function __construct(Array $assets = array()) {
        $this->assets = $assets;
        $this->examine();
        $this->enqueue();
    }
    
    /**
     * Enqueues our scripts and styles, but only if we have them
     */
    private function enqueue() {
        if( isset($this->frontAssets) ) {
            add_action('wp_enqueue_scripts', array($this, 'enqueueFront'));    
        }
        
        if( isset($this->adminAssets) ) {
            add_action('admin_enqueue_scripts', array($this, 'enqueueAdmin'), 10, 1);    
        } 
        
        if( isset($this->loginAssets) ) {
            add_action('login_enqueue_scripts', array($this, 'enqueueLogin'));    
        }
        
    }
    
    /**
     * Examines the array of assets and add them in the right array
     */
    private function examine() {
        
        // Loop through the various assets
        foreach( $this->assets as $asset ) {
            
            // Default values for each of the assets
            $defaults = array(
                'action'    => 'enqueue',
                'context'   => '',
                'deps'      => array(),
                'exclude'   => false,
                'include'   => false,
                'in_footer' => true,
                'media'     => 'all',
                'mix'       => '',
                'ver'       => false,
            );
            
            $asset  = wp_parse_args($asset, $defaults);
            $type   = substr($asset['src'], -2, 2);
            
            // Determine the action based upon their type.
            $asset['action'] = 'wp_' . $asset['action'] . '_';
            $asset['action'] .= $type == 'js' ? 'script' : 'style'; 
            $asset['mix']     = $type == 'js' ? $asset['in_footer'] : $asset['media'];
                
            // Add the assets to their context
            if( $asset['context'] == 'admin' || $asset['context'] == 'both' ) {
                $this->adminAssets[] = $asset;      
            } elseif( $asset['context'] == 'login' ) {
                $this->loginAssets[] = $asset;    
            } else {
                $this->frontAssets[] = $asset;    
            }   
        
        }
        
    }
    
    /**
     * Enqueues the front-end scripts and styles
     */
    public function enqueueFront() {        
        foreach( $this->frontAssets as $asset ) {         
            $asset['action']($asset['handle'], $asset['src'], $asset['deps'], $asset['ver'], $asset['mix']);    
        }
    }
    
    /**
      * Enqueues the admin scripts and styles
     */
    public function enqueueAdmin($hook) {
        foreach( $this->adminAssets as $asset ) {
            
            // If we are not on a page where it should be included
            if( $asset['include'] && ! in_array($hook, $asset['include']) ) {
                continue;
            }
            
            // If we are on a page where an asset should be excluded
            if( $asset['exclude'] && in_array($hook, $asset['exclude']) ) {
                continue;
            }            
            
            $asset['action']($asset['handle'], $asset['src'], $asset['deps'], $asset['ver'], $asset['mix']);     
        }  
    } 
    
    /**
      * Enqueues the login scripts and styles
     */
    public function enqueueLogin() {
        foreach( $this->loginAssets as $asset ) {
            $asset['action']($asset['handle'], $asset['src'], $asset['deps'], $asset['ver'], $asset['mix']);    
        }
    }    

}