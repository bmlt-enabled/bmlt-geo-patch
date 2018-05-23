# bmlt-geo-patch

*Backup your database, the maintainers of this script cannot be held responsible for things that could go wrong.*

This will patch up any potential bad geocoding.  You will need to set 3 configuration values at the top of index.php.

```php
$table_prefix = "";  // database prefix for your MySQL sever
$google_maps_api_key = "";
$root_server = "";  
```

Once you are ready run it

`php index.php`

You will get a list of `UPDATE` queries to run on your root server MySQL.  Run them.