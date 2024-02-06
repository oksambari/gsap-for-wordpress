<?php
/*
Plugin Name: GSAP for WordPress
Plugin URI: 
Description: 이 플러그인은 wordpress 사이트에 gsap 에니메이션을 쓸 수 있게 합니다.
Version: 1.0.0
Author: oksambari
Author URI: https://oksambari.xyz
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gsap-for-wordpress
*/

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * WordPress에서 스크립트와 스타일을 로드하기 위해 사용되는 액션 훅(Action Hook)
 * 이 훅은 웹 페이지의 헤더(header) 영역에서 스크립트와 스타일을 로드하기 위한 작업을 수행하는 데 사용됩니다.
 */
add_action( 'wp_enqueue_scripts', 'my_enqueue_styles_scripts' );
function my_enqueue_styles_scripts() {
	
    // The core GSAP library
	wp_enqueue_script( 'gsap-core', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/gsap.min.js', array(), false, true );
	// ScrollTrigger - with gsap.js passed as a dependency
	wp_enqueue_script( 'gsap-st', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.4/ScrollTrigger.min.js', array('gsap-core'), false, true );
	
}

/**
 * Add scripts to wp_footer()
 */
add_action( 'wp_footer', 'my_custom_footer_script', 99 );
function my_custom_footer_script() { ?>

	<script type="text/javascript">
		
		// gsap에 플러그인을 추가하여 사용하려면 registerPlugin으로 먼저 등록 필요
		gsap.registerPlugin(ScrollTrigger);			
		
		// 이제 gsap와 ScrollTrigger를 사용할 수 있습니다. 
		// ex) 스크롤 시 gs_reveal 클래스 명을 가진 요소들에 등장 효과 적용하기 
		gsap.utils.toArray(".gs_reveal").forEach(function(elem) {
			hide(elem); // 안 보이는 상태 확인  	
			ScrollTrigger.create({
				trigger: elem,
			//	markers: true,
				onEnter: function() { animateFrom(elem) }, 
				onEnterBack: function() { animateFrom(elem, -1) },
				onLeave: function() { hide(elem) } 
			});
		});
		function animateFrom(elem, direction) {
			direction = direction || 1;
			var x = 0,
				y = direction * 100;
			if(elem.classList.contains("gs_reveal_fromLeft")) {
				x = -100;
				y = 0;
			} else if (elem.classList.contains("gs_reveal_fromRight")) { 
				x = 100;
				y = 0;
			}
			elem.style.transform = "translate(" + x + "px, " + y + "px)";
			elem.style.opacity = "0";
			gsap.fromTo(elem, {x: x, y: y, autoAlpha: 0}, {
				duration: 1.25, 
				x: 0,
				y: 0, 
				autoAlpha: 1, 
				ease: "expo", 
				overwrite: "auto"
			});
		}
		function hide(elem) {
			gsap.set(elem, {autoAlpha: 0});
		}	 
		
		
	</script>

<?php
}
