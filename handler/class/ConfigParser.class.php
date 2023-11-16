<?php
	class config {
		
		private $name = "";
		private $object = "";

		function __construct($name) {
			$this->name = $name;
            
            if (!file_exists($name)) {
    			$config = fopen($name, "w+");
    			fclose($config);
            }
            
			$string = file_get_contents($name);

			if ($string && $string != "") {
				$this->object = json_decode($string, true);
			} else {
				$this->object = "";
			}
		}

        function check() {
            if ($this->object == "") {
				return false;
			} else {
				return true;
			}
        }

		function getString($string) {
			if ($this->object == "") {
				return "Không có dữ liệu!";
			} else {
				try {
					if ($this->object[$string] == null) {
					    $this->change($string, "");
					}
					$value = $this->object[$string];
				} catch (Exception $e) {	
					$value = "Giá trị không tồn tại!";
				}
				return $value;
			}
		}

		function getList($string) {
			if ($this->object == "") {
				return "Không có dữ liệu!";
			} else {
				try {
				    if ($this->object[$string] == null) {
					    $this->change($string, "[]");
					}
					$value = $this->object[$string];
					$value = json_decode($value, true);
				} catch (Exception $e) {	
					$value = "Giá trị không tồn tại!";
				}
				return $value;
			}
		}

		function change($key, $value) {
		    $arr = array();
			if ($this->object == "") {
				$arr = array();
			} else {
			    $arr = $this->object;
			}
			try {
				$arr[$key] = $value;
				
				$config = fopen($this->name, "w+");
				fwrite($config, json_encode($arr));
				fclose($config);

				$string = file_get_contents($this->name);

				if ($string && $string != "") {
					$this->object = json_decode($string, true);
				} else {
					$this->object = "";
				}
				return true;				
			} catch (Exception $e) {	
				return false;
			}
		}
		
		function remove($key) {
		    if ($this->object == "") {
				return true;
			} else {
			    $arr = $this->object;
			    try {
    				unset($arr[$key]);
    				
    				$config = fopen($this->name, "w+");
    				fwrite($config, json_encode($arr));
    				fclose($config);
    
    				$string = file_get_contents($this->name);
    
    				if ($string && $string != "") {
    					$this->object = json_decode($string, true);
    				} else {
    					$this->object = "";
    				}
    				return true;				
    			} catch (Exception $e) {	
    				return false;
    			}   
			}
		}
	}

?>