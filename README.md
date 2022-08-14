MITHA API AAPANEL v0.1
based from API documentation (unfinished) from aaPanel_Jose : https://forum.aapanel.com/d/482-api-interface-tutorial

first build : 08/14/2022

if anyone wants to help develop, i'm very grateful

===================================

for first time you must enable API (Settings > Global > API > enable)
set > API secret key RESET > confirm
IP whitelist > input your public IP
save

===================================

set new file must contain :

```code
include_once 'api/api_aapanel_mitha.php'; $aapanel = new aapanel_api;
$aapanel->key = 'YOUR API KEY'; $aapanel->url = 'YOUR AAPANEL IP AND PORT';
```

==================================
