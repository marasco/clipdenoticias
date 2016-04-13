<?php
class Logger {
    private $path;
    private $file;
    private $date;

    public function __construct($path) {
        $this->path = $path;
    }

    public function LogLine($tipo,$msg){
        $fileObj=NULL;
	$this->date = date('d/m/Y H:i:s',time());
	$this->file = ($this->path).date('Ymd',time()).".log";
        if (is_dir($this->path)) { 
		if (!file_exists($this->file)){
			$fileObj = @fopen($this->file,'a+');
	        }else{
			$fileObj = @fopen($this->file,'a');
		}
		@fwrite($fileObj,"[".$this->date."]".chr(9).chr(9)."[".$tipo."]".chr(9).chr(9).$msg.chr(10));
		@fclose($fileObj);
        }
    }
  
}

?>
