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

// FAQ section
get_template_part('sections/services/faq');

get_footer('simple');
