<?php
/*

* MITHA API AAPANEL v0.1
* based from API documentation (unfinished) from aaPanel_Jose : https://forum.aapanel.com/d/482-api-interface-tutorial

* first build : 08/14/2022

update :
- deleteSubdomain
- modifySubdomain
- subDomainList
- deleteSite
- insertDbase
- fix bux applySSL

*/

class aapanel_api
{
    public $key = null;
    public $url = null;

    private function encrypt()
    {
        return [
            'request_token' => md5(time() . md5($this->key)),
            'request_time' => time(),
        ];
    }

    private function httpPostCookie($url, $data, $timeout = 60)
    {
        //Define where cookies are saved
        $cookie_file = './' . md5($this->url) . '.cookie';
        if (!file_exists($cookie_file)) {
            $fp = fopen($cookie_file, 'w+');
            fclose($fp);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    public function logs()
    {
        $completeUrl    = $this->url . '/data?action=getData';

        $data           = $this->encrypt();
        $data['table']  = 'logs';
        $data['limit']  = 10;
        $data['tojs']   = 'test';

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function addSite($domain, $path, $desc, $type_id = 0, $type = 'php', $phpversion = '73', $port = '80', $ftp = null, $ftpusername = null, $ftppassword = null, $sql = null, $userdbase = null, $passdbase = null, $setSsl = 0, $forceSsl = 0)
    {
        $completeUrl    = $this->url . '/site?action=AddSite';

        $datajson       = [
            'domain'        => $domain,
            'domainlist'    => [],
            'count'         => 0,
        ];
        $data                   = $this->encrypt();
        $data['webname']        = json_encode($datajson);
        $data['path']           = "/www/wwwroot/" . $path;
        $data['ps']             = $desc;
        $data['type_id']        = $type_id;
        $data['type']           = $type;
        $data['version']        = $phpversion;
        $data['port']           = $port;

        if (isset($ftp)) {
            $data['ftp']            = $ftp;
            $data['ftp_username']   = $ftpusername;
            $data['ftp_password']   = $ftppassword;
        }
        if (isset($sql)) {
            $data['sql']            = $sql;
            $data['datauser']       = $userdbase;
            $data['datapassword']   = $passdbase;
        }

        $data['codeing']        = 'utf8';
        $data['set_ssl']        = $setSsl;
        $data['force_ssl']      = $forceSsl;


        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function addSubDomain($subdomain, $mainDomain, $iptarget)
    {
        $completeUrl    = $this->url . '/plugin?action=a&name=dns_manager&s=act_resolve';

        $data           = $this->encrypt();
        $data['host']   = $subdomain;
        $data['value']  = $iptarget;
        $data['domain'] = $mainDomain;
        $data['ttl']    = '600';
        $data['type']   = 'A';
        $data['act']    = 'add';

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function deleteSubDomain($subdomain, $mainDomain, $iptarget)
    {
        $completeUrl    = $this->url . '/plugin?action=a&name=dns_manager&s=act_resolve';

        $data           = $this->encrypt();
        $data['host']   = $subdomain;
        $data['value']  = $iptarget;
        $data['domain'] = $mainDomain;
        $data['ttl']    = '600';
        $data['type']   = 'A';
        $data['act']    = 'delete';

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }
    public function modifySubDomain($subdomain, $mainDomain, $iptarget, $id)
    {
        $completeUrl    = $this->url . '/plugin?action=a&name=dns_manager&s=act_resolve';

        $data           = $this->encrypt();
        $data['host']   = $subdomain;
        $data['value']  = $iptarget;
        $data['domain'] = $mainDomain;
        $data['ttl']    = '600';
        $data['type']   = 'A';
        $data['act']    = 'modify';
        $data['id']     = $id;

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function subDomainList($domain, $host = null)
    {
        $completeUrl    = $this->url . '/plugin?action=a&name=dns_manager&s=get_resolve';

        $data           = $this->encrypt();
        $data['domain'] = $domain;

        $result         = $this->httpPostCookie($completeUrl, $data);
        $resultarray    = json_decode($result, true);

        if ($host) {
            foreach ($resultarray as $i => $r) {
                if ($r['host'] == $host) {
                    $resultarray = $resultarray[$i];
                    $resultarray['id'] = $i;
                    break;
                }
            }
        }
        return $resultarray;
    }

    public function unzip($sourceFile, $destinationFile, $password = null)
    {
        $completeUrl    = $this->url . '/files?action=UnZip';

        $data               = $this->encrypt();
        $data['sfile']      = $sourceFile;
        $data['dfile']      = $destinationFile;
        $data['type']       = 'zip';
        $data['coding']     = 'UTF-8';
        $data['password']   = $password;

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function forceHTTPS($sitename)
    {
        $completeUrl    = $this->url . '/site?action=HttpToHttps';

        $data               = $this->encrypt();

        $data['siteName']   = $sitename;

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function applySSL($domain, $idDomain)
    {
        $completeUrl    = $this->url . '/acme?action=apply_cert_api';

        $data               = $this->encrypt();

        $data['domains']        = '["' . $domain . '"]';
        $data['id']             = $idDomain;
        $data['auth_to']        = $idDomain;
        $data['auth_type']      = 'http';
        $data['auto_wildcard']  = '0';

        $result         = $this->httpPostCookie($completeUrl, $data);
        $result         = json_decode($result, true);

        $urlSSL     = $this->url . '/site?action=SetSSL';

        $data2      = $this->encrypt();

        $data2['type']      = '1';
        $data2['siteName']  = $domain;
        $data2['key']       = $result['private_key'];
        $data2['csr']       = $result['cert'] . ' ' . $result['root'];

        $result2        = $this->httpPostCookie($urlSSL, $data2);

        return json_decode($result2, true);
    }

    /*
     * List of Your Website Project 
     * boolean php | nodejs | pm2 | all
     * ================================
     * TODO: Show all project by default
     */
    public function siteList($limit, $page, $projectType='php', $search = null)
    {
        // Show Default Project Site
        switch ($projectType) {
            case 'nodejs':
                $completeUrl        = $this->url . '/project/nodejs/get_project_list';
                break;
            case 'php':
                $completeUrl        = $this->url . '/data?action=getData';
                break;
            case 'pm2':
                $completeUrl        = $this->url . '/plugin?action=a&s=List&name=pm2';
                break;
            default:
                $completeUrl        = $this->url . '/project/nodejs/get_project_list';
                break;
        }

        $data               = $this->encrypt();

        $data['table']      = 'sites';
        $data['limit']      = $limit;
        $data['p']          = $page;
        $data['search']     = $search;
        $data['type']       = '-1';
        
        $result             = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function deleteSite($webname, $id)
    {
        $completeUrl    = $this->url . '/site?action=DeleteSite';

        $data               = $this->encrypt();

        $data['ftp']        = "1";
        $data['database']   = "1";
        $data['path']       = "1";
        $data['id']         = $id;
        $data['webname']    = $webname;

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function disableSite($idDomain, $domain, $projectType='PHP')
    {
        // Move For 1st Encrypt Do.
        $data           = $this->encrypt();

        // Show Default Project Site
        switch ($projectType) {
            case 'Node':
                $completeUrl    = $this->url . '/project/nodejs/stop_project';
                $data['data']   = json_encode(['project_name'=>$domain]);
                break;
            case 'PHP':
                $completeUrl    = $this->url . '/site?action=SiteStop';
                $data['id']     = $idDomain;
                $data['name']   = $domain;
                break;
            default:
                $completeUrl    = $this->url . '/site?action=SiteStop';
                $data['id']     = $idDomain;
                $data['name']   = $domain;
                break;
        }

        $result   = $this->httpPostCookie($completeUrl, $data); 

        return json_decode($result, true);
    }

    public function enableSite($idDomain, $domain, $projectType='PHP')
    {
        // Move For 1st Encrypt Do.
        $data           = $this->encrypt();

        // Show Default Project Site
        switch ($projectType) {
            case 'Node':
                $completeUrl    = $this->url . '/project/nodejs/start_project';
                $data['data']   = json_encode(['project_name'=>$domain]);
                break;
            case 'PHP':
                $completeUrl    = $this->url . '/site?action=SiteStart';
                $data['id']     = $idDomain;
                $data['name']   = $domain;
                break;
            default:
                $completeUrl    = $this->url . '/site?action=SiteStart';
                $data['id']     = $idDomain;
                $data['name']   = $domain;
                break;
        }

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function importDbase($file, $dbasename)
    {
        $completeUrl    = $this->url . '/database?action=InputSql';

        $data               = $this->encrypt();

        $data['file']       = $file;
        $data['name']       = $dbasename;

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }

    public function safeFileBody($datafile, $path)
    {
        $completeUrl    = $this->url . '/files?action=SaveFileBody';

        $data               = $this->encrypt();

        $data['data']       = $datafile;
        $data['path']       = $path;
        $data['encoding']   = 'utf-8';

        $result         = $this->httpPostCookie($completeUrl, $data);

        return json_decode($result, true);
    }
}
