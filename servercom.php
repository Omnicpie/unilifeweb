<?php
error_reporting(E_ERROR);
define('AES_256_CBC', 'aes-256-cbc');
global $socket;
global $keypair;
//AUTHOR: Ryan Anderson
//Communicates with the Database server


// All possible commands for the server.
// For explaination of what each command, check the Server Manual.
// Returns away of attributes, [0] = attribute name, [1] = attribute information.
function getProcedure($pre, $userrequests){
    $array = array(
        "auth"               => [["command", "USR_AUTH"], ["email", $userrequests[0]], ["password", $userrequests[1]], ["browser_name", $userrequests[2]], ["device_name", $userrequests[3]]],
        "unis"               => [["command", "GET_UNIS"]],
        "courses"            => [["command", "GET_COURSES"]],
        "search"             => [["command", "USR_SEARCH"], ["search_query", $userrequests[0]], ["user_id", $userrequests[1]]],
        "del"                => [["command", "PST_DEL"], ["post_id", $userrequests[0]]],
        "delaccount"         => [["command", "USR_DEL"], ["user_id", $userrequests[0]]],
        "getdata"            => [["command", "USR_DPA"], ["user_id", $userrequests[0]], ["email", $userrequests[1]]],
        "feed"               => [["command", "PST_GET_FEED"], ["user_id", $userrequests[0]]],
        "userposts"          => [["command", "PST_GET_USER_POSTS"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "reguser"            => [["command", "USR_REG"], ["email", $userrequests[0]], ["first_name", $userrequests[1]], ["surname", $userrequests[2]], ["dob", $userrequests[3]], ["password", $userrequests[4]], ["gender", $userrequests[5]], ["uni_id", $userrequests[6]]],
        "updateuser"         => [["command", "USR_UPDATE_PROFILE"], ["user_id", $userrequests[0]], ["first_name", $userrequests[1]], ["last_name", $userrequests[2]], ["study_type", $userrequests[3]], ["gender", $userrequests[4]], ["course_id", $userrequests[5]], ["private_profile", $userrequests[6]], ["course_start", $userrequests[7]], ["course_end", $userrequests[8]]],
        "updategroup"        => [["command", "GRP_UPDATE"], ["group_id", $userrequests[0]], ["group_name", $userrequests[1]], ["group_desc", $userrequests[2]], ["private_group", $userrequests[3]], ["default_permission", $userrequests[4]]],
        "getpost"            => [["command", "PST_GET"], ["post_id", $userrequests[0]], ["user_id", $userrequests[1]], ["group_post", $userrequests[2]]],
        "likepost"           => [["command", "PST_LIKE"], ["post_id", $userrequests[0]], ["user_id", $userrequests[1]], ["group_post", $userrequests[2]]],
        "editpost"           => [["command", "PST_EDIT"], ["post_id", $userrequests[0]], ["post_content", $userrequests[1]], ["public_post", $userrequests[2]], ["group_post", $userrequests[3]]],
        "userid"             => [["command", "USR_GET_ID"], ["email", $userrequests[0]]],
        "getname"            => [["command", "USR_GET_NAME"], ["user_id", $userrequests[0]]],
        "getfriends"         => [["command", "USR_GET_FRIENDS"], ["user_id", $userrequests[0]]],
        "verify"             => [["command", "USR_VERIFY"], ["email", $userrequests[0]], ["confirmation_code", $userrequests[1]]],
        "userinfo"           => [["command", "USR_GET_PROFILE"], ["user_id", $userrequests[0]]],
        "emailcheck"         => [["command", "USR_EMAIL_EXISTS"], ["email", $userrequests[0]]],
        "creategroup"        => [["command", "GRP_REG"], ["owner_id", $userrequests[0]], ["name", $userrequests[1]], ["desc", $userrequests[2]], ["default_permission", $userrequests[3]], ["private", $userrequests[4]], ["verified", $userrequests[5]]],
        "getcomments"        => [["command", "PST_GET_COMMENTS"], ["post_id", $userrequests[0]], ["group_comment", $userrequests[1]]],
        "comment"            => [["command", "PST_COMMENT"], ["post_id", $userrequests[0]], ["group_id", $userrequests[1]], ["user_id", $userrequests[2]], ["content", $userrequests[3]], ["group_comment", $userrequests[4]], ["reply_id", $userrequests[5]]],
        "delcomment"         => [["command", "PST_DEL_COMMENT"], ["comment_id", $userrequests[0]], ["group_comment", $userrequests[1]]],
        "delgroup"           => [["command", "GRP_DEL"], ["group_id", $userrequests[0]], ["user_id", $userrequests[1]]],
        "joingroup"          => [["command", "GRP_JOIN"], ["user_id", $userrequests[0]], ["group_id", $userrequests[1]]],
        "leavegroup"         => [["command", "GRP_LEAVE"], ["user_id", $userrequests[0]], ["group_id", $userrequests[1]]],
        "grouppost"          => [["command", "PST_MAKE_GROUP_POST"], ["group_id", $userrequests[0]], ["user_id", $userrequests[1]], ["post_content", $userrequests[2]], ["image", $userrequests[3]]],
        "getgroupowner"      => [["command", "GRP_GET_OWNER"], ["group_id", $userrequests[0]]],
        "getgroup"           => [["command", "GRP_GET"], ["group_id", $userrequests[0]], ["user_id", $userrequests[1]]],
        "getmembers"         => [["command", "GRP_GET_MEMBERS"], ["group_id", $userrequests[0]]],
        "memberrequests"     => [["command", "GRP_MEMBER_REQUESTS"], ["group_id", $userrequests[0]]],
        "reqaccept"          => [["command", "GRP_REQUEST_ACCEPT"], ["group_id", $userrequests[0]], ["user_id", $userrequests[1]]],
        "reqdeny"            => [["command", "GRP_REQUEST_DENY"], ["group_id", $userrequests[0]], ["user_id", $userrequests[1]]],
        "getpermissionlevel" => [["command", "GRP_GET_PERMISS"], ["user_id", $userrequests[0]], ["group_id", $userrequests[1]]],
        "getgroupposts"      => [["command", "PST_GET_GROUP_POSTS"], ["group_id", $userrequests[0]], ["user_id", $userrequests[0]]],
        "getgroups"          => [["command", "GRP_GET_ALL"], ["user_id", $userrequests[0]]],
        "getusersgroups"     => [["command", "GRP_USER_GROUPS"], ["user_id", $userrequests[0]]],
        "post"               => [["command", "PST_MAKE"], ["user_id", $userrequests[0]], ["post_content", $userrequests[1]], ["public_post", $userrequests[2]], ["image", $userrequests[3]]],
        "profpic"            => [["command", "IMG_UPDATE_PROFILE_PIC"], ["user_id", $userrequests[0]], ["image", $userrequests[1]]],
        "getpic"             => [["command", "IMG_GET_PROFILE_PIC"], ["user_id", $userrequests[0]]],
        "profban"            => [["command", "IMG_UPDATE_USER_BANNER"], ["user_id", $userrequests[0]], ["image", $userrequests[1]]],
        "getban"             => [["command", "IMG_GET_USER_BANNER"], ["user_id", $userrequests[0]]],
        "updategrouppic"     => [["command", "IMG_UPDATE_GRP_PIC"], ["group_id", $userrequests[0]], ["image", $userrequests[1]]],
        "getgrouppic"        => [["command", "IMG_GET_GRP_PIC"], ["group_id", $userrequests[0]]],
        "updategroupban"     => [["command", "IMG_UPDATE_GRP_BANNER"], ["group_id", $userrequests[0]], ["image", $userrequests[1]]],
        "getgroupban"        => [["command", "IMG_GET_GRP_BANNER"], ["group_id", $userrequests[0]]],
        "friendstatus"       => [["command", "FRD_GET_FRIEND_REL"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "getfndrequests"     => [["command", "FRD_GET_ALL_REQS"], ["current_user_id", $userrequests[0]]],
        "numreq"             => [["command", "FRD_NUM_REQS"], ["current_user_id", $userrequests[0]]],
		    "friendrec"			     => [["command", "FRD_GET_RECOMMENDATIONS"], ["current_user_id", $userrequests[0]]],
        "friend"             => [["command", "FRD_SEND_REQ"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "denyFR"             => [["command", "FRD_DENY_REQ"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "cancelFR"           => [["command", "FRD_CANCEL_REQ"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "acceptFR"           => [["command", "FRD_ACCEPT_REQ"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "remFriend"          => [["command", "FRD_UNFRIEND"], ["current_user_id", $userrequests[0]], ["other_user_id", $userrequests[1]]],
        "messend"            => [["command", "CHT_MSG_SEND"], ["user_id", $userrequests[0]], ["chat_id", $userrequests[1]], ["message_content", $userrequests[2]]],
        "getmessages"        => [["command", "CHT_MSG_GET"], ["chat_id", $userrequests[0]]],
        "nummessages"        => [["command", "CHT_MGS_NUM"], ["chat_id", $userrequests[0]]],
        "getchat"            => [["command", "CHT_GET"], ["user_id", $userrequests[0]]],
        "chatleave"          => [["command", "CHT_REMOVE_USER"], ["user_id", $userrequests[0]], ["chat_id", $userrequests[1]]],
        "chatcreate"         => [["command", "CHT_CREATE"], ["user_id", $userrequests[0]], ["other_user_id", $userrequests[1]], ["chat_name", $userrequests[2]]],
        "chatadduser"        => [["command", "CHT_ADD_USER"], ["user_id", $userrequests[0]], ["chat_id", $userrequests[1]]],
        "quit"               => [["command", "QUIT"]]
    );
    return $array[$pre];
}
// Creates an XML requests, give an array of attributes.
// Returns the xml in a string format.
function createXMLRequest($attributes){
    // Creates the document.
    $dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->xmlVersion = '1.0';
    $dom->formatOutput = true;
    // Creates the base element and the request.
    $root = $dom->createElement('requests');
    $request = $dom->createElement('rqst');
    // Adds all the attributes to rqst. 
    foreach($attributes as $atrrib){
        echo $attrib[0]; 
        echo $attrib[1];
        $attribute = new DOMAttr($atrrib[0], $atrrib[1]);
        $request->setAttributeNode($attribute);
    }
    $root->appendChild($request);
    $dom->appendChild($root);
    // Returns a string of XML.
    $xml = $dom->saveXML();
    return $xml;
}
// Function for reading from the socket.
// Takes a socket connects and a number of bytes to read and returns the result or a false on failure.
function socketRead($socket, $bytes){
    // Gets results from the socket.
    $result = socket_read($socket, $bytes, PHP_NORMAL_READ);
    if($result == false){
        return false;
    }else{
        return $result;
    }
}

// First reads number of bytes needed to read from the socket, then the information that is sent.
// Outputs the information.
function serverRead($socket, $keypair){
    $result = socketRead($socket, 2048);
    $result2 = decrypt(mb_substr($result, 0, -1), $keypair[0], $keypair[1]);
    $result3 = socketRead($socket, (int)$result2);
    $finalout = decrypt($result3, $keypair[0], $keypair[1]);
    return $finalout;
}

// Creates a socket at host:port and returns it for use by other servercom functions.
function setupServer(){
    // Create socket.
    $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
    socket_set_option($socket, SOL_SOCKET, TCP_NOD);
    // Host options. change as needed 
    $host    = "unilifeserver.ddns.net";
    // Port options 4000 = production server, 4001 = test server(this one goes down regularly)
    $port    = 4000;
    
    
    socket_connect($socket, $host, $port) or die("Could not connect to server\n");
    return $socket;
}

// Sends the AES key and iv to the server and returns them for use with writing requests.
function sendAES($socket){
    // Generates a new AES key and IV.
    $keypair = genkey();
    // Seperates key and IV for sending.
    $encryptedKey = keyenc($keypair[0]);
    $encryptedIV = keyenc($keypair[1]);
    // Creates the send request. 
    $keyex = $encryptedKey . "\r\n" . $encryptedIV . "\r\n";
    socket_write($socket, $keyex, strlen($keyex)) or die("Could not send Key to server\n");
    return $keypair;
}
//Function for sending a command to the server, and  getting the SQL result returned
function sendtoServer($socket, $keypair, $precedure, ...$messages){
    // Gets the XML request and encrypts with AES.
    $req = getProcedure($precedure, $messages);
    $xmlRequest = createXMLRequest($req);
    $request = encrypt($xmlRequest, $keypair[0], $keypair[1]);
   
     // GENERATE AND use private key to encypt the SECRET KEY for HMAC.
    $inputKey = random_bytes(32);
    $salt = random_bytes(16);
    $encryptionKey = hash_hkdf('sha256', $inputKey, 32, 'aes-256-encryption', $salt);
    $privateKeyEncryptedSecret = hasEnc($encryptionKey);
    
    // Generate the HMAC_SHA256 of the request XML.
    $hmax = hash_hmac('SHA256', $xmlRequest, $encryptionKey, true);
    $hashXML = base64_encode($hmax);
    $privateEncHas = $hashXML;
    
    // AES the other parts for sending.
    $hashedReq = encrypt($privateEncHas, $keypair[0], $keypair[1]);
    $publicKey = encrypt(fread(fopen('pubHMAC.key', 'r'), 8192), $keypair[0], $keypair[1]);
    $secretKey = encrypt($privateKeyEncryptedSecret, $keypair[0], $keypair[1]);
    
    // Concats the full server request and sends. returns whether successful.
    $requesttosend = $request . "\r\n" . $hashedReq . "\r\n" . $publicKey . "\r\n" . $secretKey . "\r\n";
    $p = socket_write($socket, $requesttosend, strlen($requesttosend));
    // Returns if socket write passed or failed. 
    if ($p != false){
      return true;
    }
    else{
      return false;
    }
}

//encrypts a message using an AES key and IV
function encrypt($message, $key, $iv){
    $encrypted = openssl_encrypt($message, AES_256_CBC, $key, 1, $iv);
    $b64 = base64_encode($encrypted);
    return $b64;
}

//decrypts a message using an AES key and IV
function decrypt($message, $key, $iv){
    $b64 = base64_decode($message);
    if ($b64 == false){
        return false;
    }
    $decrypted = openssl_decrypt($b64, AES_256_CBC, $key, 1, $iv);
    if ($decrypted == false){
        return false;
    }
    return $decrypted;
}

//Generates a AES key and IV
function genkey(){
$key = openssl_random_pseudo_bytes(32);
$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
$out = array($key, $iv);
    return $out;
}

//used a public key to encode a string
function keyenc($thing){
    $key = fread(fopen('public.key', 'r'), 8192);
    $encrypted= "";
    openssl_public_encrypt($thing, $encrypted, $key, OPENSSL_PKCS1_PADDING);
    $b64encypt = base64_encode($encrypted);
    return $b64encypt;
}

//uses the HMACprivate key to encode a string
function hasEnc($thing){
    $key = fread(fopen('privHMAC.key', 'r'), 8192);
    $encrypted= "";
    openssl_private_encrypt($thing, $encrypted, $key, OPENSSL_PKCS1_PADDING);
    $b64encypt = base64_encode($encrypted);
    return $b64encypt;
}
?>
