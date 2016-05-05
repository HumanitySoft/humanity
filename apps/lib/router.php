<?php
return function(){
  $accept = $_SERVER['HTTP_ACCEPT'];

  switch(substr($accept,0,6)){
    case "api://":
      $this->api(substr($accept,6),file_get_contents('php://input'));
    break;
    default:
      $req = $_SERVER['REQUEST_URI'];
      $req = parse_url($req);
      $path = $req['path'];
      if(isset(self::$conf[$path])){
        $conf = self::$conf[$path];
        $css = $conf['css'];
        $js = $conf['js'];
        $html = $conf['html'];

        # HTML
        $html = self::$dir.'/view/'.$html.'.phtml';
        if(is_file($html)) {
echo <<<HTML
    <!DOCTYPE html>
    <html>
     <head>
HTML;
echo '<title></title>';
echo <<<HTML
       <meta charset="utf-8">
       <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <meta name="viewport" content="width=device-width, initial-scale=1">
     </head>
HTML;
        # CSS
        foreach($css as $k=>$name){
          $file = self::$dir.'/css/'.$name.'.css';
          if(is_file($file)) $css[$k] = file_get_contents($file);
          else unset($css[$k]);
        }    
        echo '<style>'.implode('',$css).'</style>';
echo <<<HTML
     <body>
HTML;
          require_once($html);
echo <<<HTML
    </body>
HTML;
        # JS
        foreach($js as $k=>$name){
          $file = self::$dir.'/js/'.$name.'.js';
          if(is_file($file)) $js[$k] = file_get_contents($file);
          else unset($js[$k]);
        }    
        echo '<script>'.implode('',$js).'</script>';
        }
echo <<<HTML
    </html>
HTML;
      }
  }

}
?>
