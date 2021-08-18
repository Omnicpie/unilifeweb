var Android = /(android)/i.test(navigator.userAgent); 
if(Android) { 
    window.location.assign("https://unilife.ddns.net/androidDetected.php")
} 
