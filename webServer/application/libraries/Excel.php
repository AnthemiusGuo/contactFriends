<?
require "PHPExcel/PHPExcel.php";
class Excel {
    public $excel;
    
    public function __construct(){
        
    }
    
    public function init($inputFileName){
	/** Load $inputFileName to a PHPExcel Object  **/
	$this->excel = PHPExcel_IOFactory::load($inputFileName);
    }
    public function getAllData(){
    	$this->excel_data = array();
		$objWorksheet = $this->excel->getActiveSheet();
		// Get the highest row number and column letter referenced in the worksheet
		$highestRow = $objWorksheet->getHighestRow(); // e.g. 10
		$highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
		// Increment the highest column letter
		$highestColumn++;

		for ($row = 1; $row <= $highestRow; ++$row) {
			$line = array();
		    for ($col = 'A'; $col != $highestColumn; ++$col) {
		  //   	var_dump($objWorksheet->getCell($col . $row)
				// ->getValue());
				// print "<br/>\r\n";
				// continue;
				$cell = $objWorksheet->getCell($col . $row);
				$val = "".$cell->getValue();
				// var_dump($col, $row,$val);
		    	if ($row==1 && $val==""){
		    		$highestColumn = $col++;
		    		break;
		    	}

		    	if (PHPExcel_Shared_Date::isDateTime($cell))
			        $val = PHPExcel_Shared_Date::ExcelToPHP($val);
			    else
			        $val = "".$val;

				$line[] = $val;
		    }
		    $this->excel_data[] = $line;
		}
		return $this->excel_data;
    }
    
}
