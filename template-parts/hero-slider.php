<?php

/**
 * Template Part for pages with big hero slider
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */

?>


    <div id="hero-slides">
	<h2 class="screen-reader-text"><?php echo __('Slider','fau'); ?></h2>
	<?php	
	global $usejslibs;
	global $defaultoptions;
	$i= 0;

	$numberposts = get_theme_mod('start_header_count');
	$catid =  get_theme_mod('slider-catid');
	$usejslibs['heroslider'] = true;

	if (isset($catid) && $catid > 0) {
	    $hero_posts = get_posts( array( 
		'cat' => $catid, 
		'posts_per_page' => $numberposts) 
	    );
	} else {							    
	    $query = array(
		'numberposts' => $numberposts
	    );                   
	    $hero_posts = get_posts($query); 
	}
	?>
	
	   <div class="slick-slider featured-slider cf">
	       <?php
		foreach($hero_posts as $hero): ?>	
		<article class="item">  
		    <?php 

		    $sliderimage = $copyright = $slidersrc = $slidersrcset = '';

		    $imageid = get_post_meta( $hero->ID, 'fauval_slider_image', true );
		    if (isset($imageid) && ($imageid>0)) {
			// Bestfall: Es gibt ein eigenes Bannerbild für den Artikel
			$sliderimage = wp_get_attachment_image_src($imageid, 'hero'); 
			$imgdata = fau_get_image_attributs($imageid);
			$copyright = trim(strip_tags( $imgdata['credits'] ));
			$slidersrcset =  wp_get_attachment_image_srcset($imageid,'hero');
		    } else {
			$post_thumbnail_id = get_post_thumbnail_id( $hero->ID ); 
			if ($post_thumbnail_id) {
			    // Es wird das Artikelbild verwendet, auch wenn es vielleicht nicht
			    // das Format des Banners hat
			    $sliderimage = wp_get_attachment_image_src( $post_thumbnail_id, 'hero' );
			    $imgdata = fau_get_image_attributs($post_thumbnail_id);
			    $copyright = trim(strip_tags( $imgdata['credits'] ));
			    $slidersrcset =  wp_get_attachment_image_srcset($post_thumbnail_id,'hero');
			} else {
			    $fallbackid = get_theme_mod("fallback-slider-image");			
			    if (isset($fallbackid) && ($fallbackid > 0)) {
				// Es gibt weder Bannerbild noch Artikelbild.
				// Wir nehmen das Fallbackbild aus dem Customizer
				$sliderimage = wp_get_attachment_image_src( $fallbackid, 'hero' );
				$slidersrcset =  wp_get_attachment_image_srcset($fallbackid,'hero');
				$imgdata = fau_get_image_attributs($fallbackid);
				$copyright = trim(strip_tags( $imgdata['credits'] ));
			    } else {
				// Kein Fallbackbild definiert, also hardcodiertes Fallback des Themes
				$sliderimage[0] = fau_esc_url($defaultoptions['src-fallback-slider-image']);
				$sliderimage[1] = $defaultoptions['slider-image-width'];
				$sliderimage[2] = $defaultoptions['slider-image-height'];
			    }	
			}
		    }


		    $slidersrc = '<img src="'.fau_esc_url($sliderimage[0]).'" width="'.$sliderimage[1].'" height="'.$sliderimage[2].'" alt=""';
		    if ($slidersrcset) {
			$slidersrc .= ' srcset="'.$slidersrcset.'"';
		    }
		    $slidersrc .= ' role="presentation">';
		    echo $slidersrc."\n"; 

		    if ((get_theme_mod('advanced_display_hero_credits')==true) && (!empty($copyright))) {
			echo '<p class="credits">'.$copyright."</p>";
		    }
		    ?>
		    <div class="hero-slide-text">
			<div class="container">
			    <div class="row">
				<div class="slider-titel">
				    <?php
					echo '<h3><a href="';

					$link = get_post_meta( $hero->ID, 'external_link', true );
					$external = 0;
					if (isset($link) && (filter_var($link, FILTER_VALIDATE_URL))) {
					    $external = 1;
					} else {
					    $link = fau_esc_url(get_permalink($hero->ID));
					}
					echo $link;
					echo '">'.get_the_title($hero->ID).'</a></h3>'."\n";					
					?>
				    </div>
				</div>
			    <?php
				$maxlen = get_theme_mod("default_slider_excerpt_length");
				if ($maxlen > 0) { ?>
				<div class="row">
				    <div class="slider-text"><?php 
					$abstract = get_post_meta( $hero->ID, 'abstract', true );			   
					if (strlen(trim($abstract))<3) {
					   $abstract =  fau_custom_excerpt($hero->ID,$maxlen,false,'',true);
					} ?>
					<p><?php echo $abstract; ?></p>
				    </div>
				</div>  <?php } ?>		   
			</div>
		    </div>
		</article>
	       
		<?php endforeach; 
		wp_reset_query();
		?>
	    </div>
	    <div class="slider-controls">
		<?php 
		$stopButtontext = __('Stoppe Animation','fau');
		$startButtontext = __('Starte Animation','fau');
		if (('' != get_theme_mod( 'slider-autoplay' )) && (true== get_theme_mod( 'slider-autoplay' )) ) {
		    $startstopclass= '';
		    $buttontext = $stopButtontext;
		} else {
		    $startstopclass= ' stopped';
		    $buttontext = $startButtontext;
		} ?>

		<button type="button" class="slick-startstop<?php echo $startstopclass;?>">
		    <?php echo $buttontext;?>
		</button>
	    </div>
	    
	</div>
   
