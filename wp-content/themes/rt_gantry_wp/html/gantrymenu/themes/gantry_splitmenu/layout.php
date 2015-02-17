<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

class GantrySplitMenuLayout extends AbstractRokMenuLayout {

	static $jsLoaded = false;

	public function stageHeader() {
		global $gantry;

		//don't include class_sfx on 3rd level menu
		$this->args['class_sfx'] = ( array_key_exists( 'startLevel', $this->args ) && $this->args['startLevel'] == 1 ) ? '' : $this->args['class_sfx'];
		$this->activeid = ( array_key_exists( 'dropdown_enable_current_id', $this->args ) && $this->args['dropdown_enable_current_id'] == 0 ) ? false : true;

		if ( !self::$jsLoaded ) {
			if ( !( $gantry->browser->name == 'ie' && $gantry->browser->shortver < 9 ) ) {
				$gantry->addScript( $gantry->gantryUrl . '/widgets/gantrymenu/themes/default/js/rokmediaqueries.js' );
				$gantry->addScript( $gantry->gantryUrl . '/widgets/gantrymenu/themes/default/js/responsive.js' );
				if ( $gantry->get( 'responsive-menu' ) == 'selectbox' ) $gantry->addScript( $gantry->gantryUrl . '/widgets/gantrymenu/themes/default/js/responsive-selectbox.js' );
			}
			self::$jsLoaded = true;
		}

		$gantry->addLess( 'menu.less', 'menu.css', 1, array( 'headerstyle' => $gantry->get( 'headerstyle', 'dark' ), 'menuHoverColor' => $gantry->get( 'linkcolor' ) ) );

		// no media queries for IE8 so we compile and load the hovers
		if ( $gantry->browser->name == 'ie' && $gantry->browser->shortver < 9 ) {
			$gantry->addLess( 'menu-hovers.less', 'menu-hovers.css', 1, array( 'headerstyle' => $gantry->get( 'headerstyle', 'dark' ), 'menuHoverColor' => $gantry->get( 'linkcolor' ) ) );
		}
	}

	protected function renderItem(RokMenuNode &$item, &$menu) {
		global $gantry;

        //check if splitmenu is set as main menu
        if( isset( $this->args['style'] ) && $this->args['style'] == 'mainmenu' ) {
            $splitmenu_mainmenu = true;
        } else {
            $splitmenu_mainmenu = false;
        }

		//not so elegant solution to add subtext
		$item_subtext = $item->getAttribute( 'item_subtext' );
		if ( $item_subtext == '' ) $item_subtext = false;
		else $item->addLinkClass( 'subtext' );

		//get custom image
		$custom_image = $item->getAttribute( 'customimage' );
		//get the custom icon
		$custom_icon = $item->getAttribute( 'customicon' );

		//add default link class
		$item->addLinkClass( 'item' );

		if ( $custom_image && $custom_image != -1 ) $item->addLinkClass( 'image' );
		if ( $custom_icon && $custom_icon != -1 ) $item->addLinkClass( 'icon' );

		if ( $item->getLink() == '#' ) {
			$item->setLink( 'javascript:void(0);' );
		}

		?>
		<li <?php if( $item->hasListItemClasses() ) : ?>class="<?php echo $item->getListItemClasses();?>"<?php endif;?> <?php if( $item->getCssId() ) : ?>id="<?php echo $item->getCssId();?>"<?php endif;?>>
			
			<a <?php if( $item->hasLinkClasses() ) : ?>class="<?php echo $item->getLinkClasses(); ?>"<?php endif;?> <?php if( $item->hasLink() ) : ?>href="<?php echo $item->getLink();?>"<?php endif;?> <?php if( $item->getTarget() ) : ?>target="<?php echo $item->getTarget();?>"<?php endif;?> <?php if( $item->hasLinkAttribs() ) : ?> <?php echo $item->getLinkAttribs(); ?><?php endif;?>>

				<?php if( $custom_image && $custom_image != -1 && $splitmenu_mainmenu ) : ?>
					<img class="menu-image" src="<?php echo $gantry->templateUrl . '/images/icons/' . $custom_image; ?>" alt="<?php echo $custom_image; ?>" />
				<?php endif; ?>

				<?php
				if( $custom_icon && $custom_icon != -1 && $splitmenu_mainmenu ) {
					echo '<i class="' . $custom_icon . '"></i> ' . $item->getTitle();
				} else {
					echo $item->getTitle();
				}
				if( !empty( $item_subtext ) && $splitmenu_mainmenu ) {
					echo '<em>'. $item_subtext . '</em>';
				}
				?>
			</a>

			<?php if ( $item->hasChildren() ): ?>
			<ul class="level<?php echo intval( $item->getLevel() ) + 2; ?>">
				<?php foreach ( $item->getChildren() as $child ) : ?>
					<?php echo $this->renderItem( $child, $menu ); ?>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</li>
		<?php
	}
	
	public function curPageURL( $link ) {
		$pageURL = 'http';
		if ( isset( $_SERVER["HTTPS"] ) && $_SERVER["HTTPS"] == "on" ) {$pageURL .= "s";}
		$pageURL .= "://";
		if ( $_SERVER["SERVER_PORT"] != "80" ) {
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
	
		$replace = str_replace( '&', '&amp;', ( preg_match( "/^http/", $link ) ? $pageURL : $_SERVER["REQUEST_URI"] ) );

		return $replace == $link;
	}
	
	public function renderMenu( &$menu ) {
		global $gantry;
		ob_start();
		
		$menuname = ( isset( $this->args['style'] ) && $this->args['style'] == 'mainmenu' ) ? 'gf-menu gf-splitmenu' : 'menu';

		if( $menu->getChildren() ) : ?>
		<?php if ( isset( $this->args['style'] ) && $this->args['style'] == 'mainmenu' ): ?>
		<div class="gf-menu-device-container"></div>
		<?php endif; ?>
		<ul class="<?php echo $menuname; ?> l1 <?php echo $this->args['class_sfx']; ?>">
			<?php foreach ( $menu->getChildren() as $item ) : ?>
				<?php echo $this->renderItem( $item, $menu ); ?>
			<?php endforeach; ?>
		</ul>
		<?php endif;
		return ob_get_clean();
	}
}