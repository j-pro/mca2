<?php
class Form_model extends CI_model{

	function msearch($age, $term, $inv, $ptype){
		if($age || $term || $inv || $ptype){
		
		//$this->db->where($age "BETWEEN min_age AND max_age");
		//$query = $this->db->get_where('policies', array('min_age'=>$age));
			
			$sql="SELECT * FROM `policies` WHERE (min_age <= $age AND max_age >=$age ) OR (term like $term) OR (inv_per_year like $inv) OR (policy_type_id = $ptype)";
		}else{

			$sql="SELECT * FROM `policies`";
		}
			$query = $this->db->query($sql);
			return $query;



	}
	function add_order($order){
		 $this->db->insert('orders', $order);
	}
}
?>