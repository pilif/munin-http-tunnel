<?
$req = file_get_contents('php://input');
$s = @fsockopen('localhost', 4949, $errno, $errstr, 10);
if (!$s) { die("# connection error"); }
$banner = fgets($s); // skip banner. Client outputs that
$res = "";
fwrite($s, $req."\n");

if (!preg_match('#^(nodes|config|fetch)#', $req)){
    $res .= fgets($s);
}else{
    while(!feof($s)){
        $line = chop(fgets($s));
        $res .= $line."\n";
        if ($line == '.') break;
    }
}
fclose($s);
header('Content-Length: '.strlen($res));
header('Content-Type: text/plain');
echo $res;

