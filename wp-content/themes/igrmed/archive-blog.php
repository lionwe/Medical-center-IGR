<?php
/**
 * Archive Blog Template
 * 
 * @package IGRMed
 */

get_header();

// Hero section
get_template_part('sections/archive-blog/hero');

// Blog list section
get_template_part('sections/archive-blog/list');

get_footer('simple');
