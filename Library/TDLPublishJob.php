<?php
//this class interacts with DSpace's REST API on TDL
//DSpace REST API 5.x (by May 2017)  //See https://wiki.duraspace.org/display/DSDOC5x/REST+API
//to change login information for TDL, locate tdlconfig.ini file and change the username and password
class TDLPublishJob
{
    static protected $ini_dir = "BandoCat_config\\tdlconfig.ini";
    protected $token;
    protected $tdl_email;
    protected $tdl_pwd;
    protected $baseUrl;

    function __construct($iToken = null)
    {
        $root = substr(getcwd(),0,strpos(getcwd(),"htdocs\\")); //point to xampp// directory
        $config = parse_ini_file($root . TDLPublishJob::$ini_dir);
        $this->tdl_email = $config['TDLemail'];
        $this->tdl_pwd = $config['TDLpwd'];
        $this->baseUrl = $config['baseURL'];
        if($iToken == null)
            $this->TDL_LOGIN();
        else $this->token = $iToken;
    }

    /**
     * @return null
     */
    public function getToken()
    {
        return $this->token;
    }
    /**
     * @param null $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    //login
    //method: POST
    //desc: given email and password, login and retrieve dspace-rest-token ($token)
    public function TDL_LOGIN()
    {
        $ch = curl_init();
        $data = array("email" => $this->tdl_email, "password" => $this->tdl_pwd);
        $data_str = json_encode($data);
        $options = array(CURLOPT_URL => $this->baseUrl . "login",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data_str,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_COOKIESESSION => true
        );
        curl_setopt_array($ch, $options);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_str))
        );
        // $output contains the output string
        curl_setopt($ch,CURLOPT_HEADER,false); //do not return header
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        $this->token = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
    }

    //custom GET request from Dspace REST API 5.x
    //See https://wiki.duraspace.org/display/DSDOC5x/REST+API
    //method: GET
    //@Param: $str : URI
    //return: GET output
    public function TDL_CUSTOM_GET($str)
    {
        $ch = curl_init($this->baseUrl . $str);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //custom DELETE request from Dspace REST API 5.x
    //See https://wiki.duraspace.org/display/DSDOC5x/REST+API
    //method: DELETE
    //@Param: $str : URI
    //return: http code (200 = OK)
    public function TDL_CUSTOM_DELETE($str,$certURL = null)
    {
        $header = array('rest-dspace-token:' . $this->token,'Accept:application/json');
        $ch = curl_init($this->baseUrl . $str);

        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certURL != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certURL);
            curl_setopt($ch,CURLOPT_CAPATH, $certURL);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $info['http_code'];
    }

    function TDL_CUSTOM_PUT($str,$filename,$mime,$certURL = null)
    {
        $data = file_get_contents($filename); //read file into variables (json/xml)
        //prepare header
        $header = array('rest-dspace-token:' . $this->token,'Content-Type:' . $mime,'Accept:' . $mime );

        $ch = curl_init($this->baseUrl . $str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HEADER,true);
        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certURL != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certURL);
            curl_setopt($ch,CURLOPT_CAPATH, $certURL);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        if($info['http_code'] == "200") //if http_code is 200, extract the item id from the header
            return $result;
        else
        {
            //echo $info['http_code'] . "\n";
            //print_r($info);
            return false;
        }
    }

    function TDL_CUSTOM_POST($str,$filename,$mime,$certURL = null)
    {
        $data = file_get_contents($filename); //read file into variables (json/xml)
        //prepare header
        $header = array('rest-dspace-token:' . $this->token,'Content-Type:' . $mime,'Accept:' . $mime );

        $ch = curl_init($this->baseUrl . $str);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HEADER,true);
        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certURL != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certURL);
            curl_setopt($ch,CURLOPT_CAPATH, $certURL);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);


        if($info['http_code'] == "200") //if http_code is 200, extract the item id from the header
            return $result;
        else
        {
            //echo $info['http_code'] . "\n";
            return false;
        }
    }


    //check_status
    //method: GET
    //return: status (json)
    public function TDL_CHECK_STATUS()
    {
        $hdr_arr = array("rest-dspace-token:" . $this->token,'Content-Type: application/json','Accept: application/json');
        $ch = curl_init($this->baseUrl . "status");
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,'GET');
        curl_setopt($ch, CURLOPT_HTTPHEADER,$hdr_arr);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        //curl_setopt($ch,CURLOPT_HEADER,true); //return header
        $output =  curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //get_communities, if $communityID = null, return all communities
    //method: GET
    //return: list of all communities (json)
    public function TDL_GET_COMMUNITIES($communityID = null)
    {
        if($communityID == null)
            $ch = curl_init($this->baseUrl . "communities");
        else $ch = curl_init($this->baseUrl . "communities/" . $communityID);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }


    //get_collections, if $communityID = null, get all collections, else get an array of collections of the specified community.
    //method: GET
    //return: list of all collections (json)
    public function TDL_GET_COLLECTIONS($communityID = null,$collectionID = null)
    {
        if($communityID == null)
            $ch = curl_init($this->baseUrl . "collections");
        else $ch = curl_init($this->baseUrl . "communities/" . $communityID ."/collections");
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //get_items
    //method: GET
    //@param: $collection_id
    //return: all items if $collectionid is blank OR all items in a specified collection
    public function TDL_GET_ITEMS($tdlCollectionID = null)
    {
        if($tdlCollectionID != null)
            $ch = curl_init($this->baseUrl . "collections/" . $tdlCollectionID . "/items");
        else $ch = curl_init($this->baseUrl . "items");

        curl_setopt($ch,CURLOPT_HTTPHEADER,array("rest-dspace-token:" . $this->token,'Content-Type:application/json'));
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //disable SSL
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    //post_new_items
    //method: POST
    //@param: $collectionid, $filename (json/xml), $mime (mime type),
    // 		  $cert_url (certification .crt file path in config.php OR leave it blank)
    //return value: if the POST is good (http_code == 200) => return the handle uri (include item_id)
    //				else return -1
    public function TDL_POST_NEW_ITEM($tdlCollectionID,$fileName,$mime,$certURL = null)
    {
        $data = file_get_contents($fileName); //read file into variables (json/xml)
        //prepare header
        $header = array('rest-dspace-token:' . $this->token,'Content-Type:' . $mime,'Accept:' . $mime );

        $ch = curl_init($this->baseUrl . "collections/" . $tdlCollectionID . "/items");
        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HEADER,true);

        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certURL != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certURL);
            curl_setopt($ch,CURLOPT_CAPATH, $certURL);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if any redirection after upload

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);


        if($info['http_code'] == "200") //if http_code is 200, extract the item id from the header
            return $result;
        else
        {
            //echo $info['http_code'] . "\n";
            return false;
        }
    }

    //post_bitstream
    //method: POST
    //@param: $itemid, $name (filepath+name, $postname (filename)
    // 		  $cert_url (certification .crt file path in config.php OR leave it blank)
    //return value: return the http_code
    public function TDL_POST_BITSTREAM($itemID,$name,$postName,$certUrl = null)
    {
        $header = array('rest-dspace-token:' . $this->token,'Accept:application/json');

        $data = file_get_contents($name);

        $ch = curl_init($this->baseUrl . "items/" . $itemID . "/bitstreams?name=" . $postName);

        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_HEADER,true);
        //increase buffer size
        //curl_setopt($ch,CURLOPT_BUFFERSIZE,8192);

        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certUrl != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certUrl);
            curl_setopt($ch,CURLOPT_CAPATH, $certUrl);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $info['http_code'];

    }


    public function TDL_DELETE_BITSTREAM($bitstreamID,$certUrl = null)
    {
        $header = array('rest-dspace-token:' . $this->token,'Accept:application/json');
        $ch = curl_init($this->baseUrl . "bitstreams/" . $bitstreamID);

        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certUrl != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certUrl);
            curl_setopt($ch,CURLOPT_CAPATH, $certUrl);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $info['http_code'];
    }

    //delete_item
    //method: DELETE
    //@param: $itemid
    // 		  $cert_url (certification .crt file path in config.php OR leave it blank)
    //return value: return the http_code
    public function TDL_DELETE_ITEM($itemID,$certUrl = null)
    {
        $header = array('rest-dspace-token:' . $this->token,'Accept:application/json');
        $ch = curl_init($this->baseUrl . "items/" . $itemID);

        curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST,"DELETE");
        //if $cert_url is blank => disable ssl; otherwise, setup SSL certficate
        if($certUrl != null)
        {
            //certificate
            curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,1);
            curl_setopt($ch,CURLOPT_CAINFO, $certUrl);
            curl_setopt($ch,CURLOPT_CAPATH, $certUrl);
        }
        else curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);

        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $info['http_code'];
    }

}