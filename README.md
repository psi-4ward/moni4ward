# Moni4ward - service checking utility

Cronjob
```
*/3 * * * *     www-default /usr/bin/php ..../system/modules/moni4ward/bin/check_service.php --cron
```

You can use several placeholders in the notification items:

* `data`: The current date
* `time`: The current time
* `result`: The service-check result (PASS|FAIL)
* And nearly all fields from the tables `tl_moni4ward_service` and `tl_moni4ward_server`



License: [LGPL](http://www.gnu.org/licenses/lgpl-3.0.html) <br>
Author: [Christoph Wiechert | 4ward.media](http://www.4wardmedia.de)