<?php
class Apps {

    private static $application = [];
    private static $conf;
    private static $singleton = false;
    private static $dir = null;

    public function __construct($dir=null){
      if(!is_null($dir)) self::$dir = $dir;
    }

    public function __get($name){
        array_push(self::$application,$name);
        return (new self);
    }

    public function __call($name,$value){
        array_push(self::$application,$name);
        $file = implode('/',self::$application);
        $nameApp = $file;
        $conf = self::$dir.'/lib/'.$file.'.json';
        if(is_file($conf)) {
          $conf = file_get_contents($conf);
          $conf = json_decode($conf,true);
        } else {
          $conf = [];
        }
        $file = self::$dir.'/lib/'.$file.'.php';
        if(!is_file($file)) return false;
        self::$application = [];
        if(is_file($file)) {
          $func = require($file);
          $app = new self;
          $app::$conf = $conf;
          $func = $func->bindTo($app);
          if(is_callable($func)) {
            if(!$singleton = (new Singleton)->get($nameApp)){
              $func = call_user_func_array($func,$value);
              if(self::$singleton === true) {
                (new Singleton)->set($nameApp,$func);
                self::$singleton = false;
              }
            } else {
              $func = $singleton;
            }
          }
          return $func;
        }
    }

}
?>
