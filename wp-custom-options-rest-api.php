<?php
/**
 * Plugin Name: WP Custom Options REST API
 * Plugin URI: https://github.com/rubenarakelyan/wp-custom-options-rest-api
 * Description: Expose custom options via the WordPress REST API.
 * Version: 1.0
 * Author: Ruben Arakelyan
 * Author URI: https://www.wackomenace.co.uk
 */

// Prevent direct access
if (!defined('ABSPATH')) {
  exit;
}

/**
 * Get all options based on a prefix.
 *
 * @param array $data Options for the function.
 * @return object A set of options, potentially nested.
 */
function get_prefixed_custom_option($data) {
  global $wpdb;

  // Prepare an SQL query to select rows that match the given prefix
  $prefix     = $data['prefix'];
  $table_name = $wpdb->prefix . 'options';
  $query      = $wpdb->prepare("SELECT option_name, option_value FROM $table_name WHERE LOWER(option_name) LIKE '$prefix\_%' ORDER BY option_id ASC", $prefix);
  $results    = $wpdb->get_results($query);

  if (empty($results)) {
    return (object)[];
  }

  $options = [];

  foreach ($results as $result) {
    list($prefix, $field_group, $field, $suffix) = explode('_', $result->option_name, 4);

    // Only return field contents, not IDs, types etc
    if (!empty($suffix)) {
      continue;
    }

    // Try to unserialize the value in case it's serialized
    $value = @unserialize($result->option_value);

    if ($value === false) {
      $value = $result->option_value;
    }

    $options[$field_group][$field] = $value;
  }

  return $options;
}

// Register our REST API endpoint
add_action('rest_api_init', function () {
  register_rest_route('wp-custom-options-rest-api/v1', '/prefix/(?P<prefix>[a-zA-Z0-9_-]+)', array(
    'methods' => 'GET',
    'callback' => 'get_prefixed_custom_option'
  ));
});
