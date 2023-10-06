<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/PHPExcel/PHPExcel.php";

class K_Excel extends PHPExcel {
    protected $_ci;

    public function __construct() {
        $this->_ci = &get_instance();
        parent::__construct();
    }

    /**
     * GET Data from excel file
     * @param $excel_path
     * @param int $max_col
     * @return array
     */
    public function get_data_from_excel($excel_path, $max_col = 0) {
        $data_return = array(
            "data"  => array(),
            "state" => 1,
        );
        $inputFileType = PHPExcel_IOFactory::identify($excel_path);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(TRUE);
        $objPHPExcel = $objReader->load($excel_path);
        if ($objPHPExcel) {
            $total_sheets = $objPHPExcel->getSheetCount();
            for ($sheet = 0; $sheet < $total_sheets; $sheet++) {
                $objWorksheet = $objPHPExcel->setActiveSheetIndex($sheet);
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $highestColumnIndex = $max_col && $highestColumnIndex > $max_col ? $max_col : $highestColumnIndex;
                $array_data = array();
                for ($row = 2; $row <= $highestRow; ++$row) {
                    if (!$objWorksheet->getCellByColumnAndRow(0, $row)->getValue()) continue;
                    for ($col = 0; $col < $highestColumnIndex; ++$col) {
                        $value = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                        $array_data[$row - 2][$col] = $value;
                    }
                }
                array_push($data_return['data'], $array_data);
            }
        } else {
            $data_return['state'] = 0;
        }

        return $data_return;
    }
}