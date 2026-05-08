<?php

/**
 * Theme Helper Functions
 *
 * @package IGRMed
 */

if (!defined('ABSPATH')) {
    exit;
}


/**
 * Get Google Reviews Widget data by feed ID
 *
 * @param int $feed_id
 * @return array|null
 */
function get_grw_data($feed_id)
{
    if (!class_exists('WP_Rplg_Google_Reviews\Includes\Core\Core')) {
        return null;
    }

    if (!class_exists('WP_Rplg_Google_Reviews\Includes\Feed_Deserializer')) {
        return null;
    }

    $feed_deserializer = new \WP_Rplg_Google_Reviews\Includes\Feed_Deserializer(new \WP_Query());
    $feed = $feed_deserializer->get_feed($feed_id);

    if (!$feed) {
        return null;
    }

    $core = new \WP_Rplg_Google_Reviews\Includes\Core\Core();
    $data = $core->get_reviews($feed);

    return $data;
}
