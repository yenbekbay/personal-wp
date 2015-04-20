<?php

// Toggle
function ay_toggle($atts, $content = null) {
	extract(shortcode_atts(array("title" => 'Title'), $atts));  
    return '<div class="toggle"><h3><a href="#">'.$title.'</a></h3><div>'.do_shortcode($content).'</div></div>';
}
add_shortcode('toggle', 'ay_toggle');

// Tabbed
function ay_tabs($atts, $content = null) {
    $GLOBALS['tab_count'] = 0;
	do_shortcode($content);
	if(is_array($GLOBALS['tabs'])) {
		foreach($GLOBALS['tabs'] as $tab ) {
			$tabs[] = '<li><a href="#'.$tab['id'].'">'.$tab['title'].'</a></li>';
			$panes[] = '<div id="'.$tab['id'].'">'.$tab['content'].'</div>';
		}		
		$return = '<div class="tabbed"><ul class="clearfix">'.implode( "\n", $tabs ).'</ul><div class="clear"></div>'.implode( "\n", $panes )."</div>\n";
	}
	return $return;
}
add_shortcode('tabbed_section', 'ay_tabs');

function ay_tab($atts, $content = null){
	extract(shortcode_atts(array('title' => '%d', 'id' => '%d'), $atts));	
	$x = $GLOBALS['tab_count'];
	$GLOBALS['tabs'][$x] = array(
		'title' => sprintf($title, $GLOBALS['tab_count']),
		'content' =>  do_shortcode($content),
		'id' =>  $id);	
	$GLOBALS['tab_count']++;
}
add_shortcode('tab', 'ay_tab');

?>