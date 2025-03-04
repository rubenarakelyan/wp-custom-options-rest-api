# WP Custom Options REST API

A WordPress plugin that exposes custom options defined through plugins such as [ACF](https://www.advancedcustomfields.com) or [ACPT](https://acpt.io) via the WordPress REST API.

## Installing

Download the [latest release](https://github.com/rubenarakelyan/wp-custom-options-rest-api/releases) .zip file, upload it to your WordPress install, and activate the plugin.

## Querying

Make a GET request to https://www.example.com/wp-json/wp-custom-options-rest-api/v1/prefix/my-options-page, where `www.example.com` is your WordPress domain and `my-options-page` is the slug of your options page.

The response will look something like:

```
{
  "contact-details": {
    "address": "My address",
    "phone-number": "01234567890",
    "email-address": "hi@example.com"
  }
}
```

`contact-details` is the name of the field group. Any serialized values are automatically unserialized.
