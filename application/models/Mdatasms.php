<?php 
class Mdatasms extends CI_Model{

	function allsms(){
         $result = $this->db->query("SELECT *,
         							(SELECT COUNT(nik) FROM sms where nik=A.nik) AS jumlah,
         							(SELECT COUNT(notujuan) FROM sms where notujuan=A.notujuan &&  nik=A.nik ) AS jumlah_notujuan  
         							FROM sms A ORDER BY nik,notujuan")->result_array();
        return $result;
    }
}
 ?>