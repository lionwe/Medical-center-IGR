<?php

/**
 * Single Blog Template
 * 
 * @package IGRMed
 */

get_header();

// Hero section
get_template_part('sections/single-blog/hero');

// Content section
get_template_part('sections/single-blog/content');

get_footer('simple');
