<?php
function fromRGB($R, $G, $B)
{

    $R = dechex($R);
    if (strlen($R)<2)
    $R = '0'.$R;

    $G = dechex($G);
    if (strlen($G)<2)
    $G = '0'.$G;

    $B = dechex($B);
    if (strlen($B)<2)
    $B = '0'.$B;

    return '#' . $R . $G . $B;
}
if(!empty($_GET['dtc'])){
  if(@fopen("https://danstonchat.com/".$_GET['dtc'].".html", 'r')){
    preg_match('`<a href="https://danstonchat.com/'.$_GET['dtc'].'.html"[^>]*>(.*)</a[^>]*>`isU', file_get_contents("https://danstonchat.com/".$_GET['dtc'].".html"), $matches);
    $body = $matches[1];
    $body = str_replace(' class="decoration"', "", $body);
    $body = str_replace('RGB(', "RGB{", $body);
    $body = str_replace(');">', '};">', $body);
    preg_match_all('`background-color: RGB{(.*)};">`isU', $body, $colored);
    $colored = $colored[1];
    $i = 0;
    while($i <= count($colored) -1){
      $colored[$i] = fromRGB(explode(",", $colored[$i])[0], explode(",", $colored[$i])[1], explode(",", $colored[$i])[2]);
      $i = $i +1;
    }
    preg_match_all('`};">(.*)</span>`isU', $body."<br />", $pseudo);
    $pseudo = $pseudo[1];
    preg_match_all('`</span>(.*)<br />`isU', $body."<br />", $message);
    $message = $message[1];

    $y = 0;
    $array = array();
    while($y <= count($colored) -1){
      $array = array_merge($array, array($y => array("color" => $colored[$y], "pseudonyme" => $pseudo[$y], "texte" => $message[$y])));
      $y = $y +1;
    }
    echo json_encode($array);
  }else{
    echo "Le chat n'existe pas.";
  }
}else{
  echo "L'argument dtc n'est pas défini.";
}
exit;
?>
