<?php
class GChartDataTableComponent extends Component {
	
	/**
	 *
	 * @param type $data
	 * @param type $filters example array('/Model/field' => array('label' => 'label', 'type' => '[string/number]', ''))
	 * @return type 
	 */
	
	public function toJson($data, $filters = false) {
		$columns = json_encode(array_values($filters)); 
		
		$rows = array();
		$colN = 0;
		foreach($filters as $path => $settings) {
			$col = Set::extract($path, $data);
			foreach($col as $row => $value) {
				switch($settings['type']) {
					case 'number':
						$rows[$row][$colN] = (float)$value;
						break;
					case 'date':
						$jsDate = implode(',', array(
							date('Y', strtotime($value)),
							date('m', strtotime($value)),
							date('d', strtotime($value)),
							date('H', strtotime($value)),
							date('i', strtotime($value)),
							date('s', strtotime($value))
						));
						$rows[$row][$colN] = "new Date($jsDate)";
						break;
					case 'string':
					default:
						$rows[$row][$colN] = (string)$value;
				}
			}
			$colN ++;
		}
		$rows = json_encode($rows);
		
		return array(
			'columns' => $columns,
			'rows' => $rows
		);
	}
}