<?php

class page{
        private $file_name;
        private $xml;
        private $smarty;
        
        function __construct($filename, $smarty) {
            $this->file_name = $filename;
            $this->smarty = $smarty;
            $this->xml = simplexml_load_file($this->file_name);
        }
}

?>