MITHA API AAPANEL v0.1 (PHP)
based from API documentation (unfinished) from aaPanel_Jose : https://forum.aapanel.com/d/482-api-interface-tutorial

first build : 08/14/2022\
minor update : 09/05/2022\
\
if anyone wants to help develop, i'm very grateful

===================================

for first time you must enable API in aapanel (Settings > Global > API > enable)\
set > API secret key RESET > confirm\
IP whitelist > input your public IP\
save

===================================

create new file must contain :

```

include_once 'api/api_aapanel_mitha.php';

$aapanel = new aapanel_api;

$aapanel->key = 'YOUR API KEY';
$aapanel->url = 'YOUR AAPANEL IP AND PORT';

```

==================================

method list :

# show Logs

```

$aapanel->logs();

```

# add Site

```

$aapanel->addSite($domain, $path, $desc, $type_id = 0, $type = 'php', $phpversion = '73', $port = '80', $ftp = null, $ftpusername = null, $ftppassword = null, $sql = null, $userdbase = null, $passdbase = null, $setSsl = 0, $forceSsl = 0);

```

# add Sub Domain (must install DNS Manager with domain ready)

```

$aapanel->addSubDomain($subdomain,$maindomain,$iptarget);

```

# Delete Sub Domain (must install DNS Manager with domain ready)

```

$aapanel->deleteSubDomain($subdomain, $mainDomain, $iptarget);

```

# Update Sub Domain (must install DNS Manager with domain ready)

```

$aapanel->modifySubDomain($subdomain, $mainDomain, $iptarget, $id)

```

# unzip file (file must exist in server)

```
$aapanel->unzip($sourcefilepath,$destinationpath,$password = null);

```

# force HTTPS for site (site must exist in website tab)

```
$aapanel->forceHTTPS($sitename);

```

# apply SSL for new domain/subdomain (site must exist in website tab)

```

$aapanel->applySSL($domain, $id_site);

```

# site list

```

$aapanel->siteList($limit,$page,$projectType,$search = null);

```

# disable Site

```

$aapanel->disableSite($id_site,$domain);

```

# enable Site

```

$aapanel->enableSite($id_site,$domain);

```

# import database file

$file = complete path

```

$aapanel->importDbase($file, $dbasename);

```

# edit file body

```

$aapanel->safeFileBody($databody,$filepath);

```
