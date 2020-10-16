<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

	function __construct() { 
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form','url','security','file'));
        $this->load->model('product_model');
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
    }

	public function add_product(){
		try{
			$auth_data = $this->product_model->get_data('server_auth_token_master');
	        $auth_code = $this->input->request_headers();
	      
	        if(isset($auth_data['token']) && isset($auth_code['Authorization']) && ($auth_code['Authorization'] == $auth_data['token'])) {
				$request = file_get_contents('php://input');
		        $data1 = json_decode($request, TRUE);

		        $prod_name = (isset($data1["name"]) && $data1["name"] != "") ? $data1["name"] : "";
		        $prod_price = (isset($data1["price"]) && $data1["price"] != "")? $data1["price"] : "";
		        $prod_images = (isset($data1["images"]) && $data1["images"] != "")? $data1["images"] : "";
		        
	        	if($prod_name == ""){
		        	$result["status"] = "Product name can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }

		        if($prod_price == ""){
		        	$result["status"] = "Product price can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }

		        if($prod_images == ""){
		        	$result["status"] = "Product image can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }

		        $insrt_data["prod_name"] = $prod_name;
		        $insrt_data["prod_price"] = $prod_price;
		        $disp_insert_data_result = $this->product_model->insert_data($insrt_data, 'products');
		        if($disp_insert_data_result){
		        	$insert_data_result = 0;
					if( !empty($data1["images"]) && is_array($data1["images"])){
				        $decode_img = $data1["images"];
				        foreach ($decode_img as $key => $value) {
					        $decode_img = base64_decode($value);

					        $no_images_data = str_replace(' ', '+', $value);

							$no_images_data = base64_decode($no_images_data);
							$img_name = base64_encode(strtotime(date('m/d/Y h:i:s a', time())))."".mt_rand().'.png';
							$file_path = './product_images/'.$img_name;
							
							$success1 = file_put_contents($file_path, $no_images_data);

							$no_images_data = base64_decode($value);
							
							$source_img1 = imagecreatefromstring($no_images_data);

							$rotated_img = imagerotate($source_img1, 90, 0); 

							imagedestroy($source_img1);
					        
					        $prod_img_insrt_data["prod_images"] = "/product_images/".$img_name;
					        $prod_img_insrt_data["prod_id"] = $disp_insert_data_result;
					        $insert_data_result = $this->product_model->insert_data($prod_img_insrt_data, 'product_images');
					    }    
				        if($insert_data_result){
				        	$result["status"] = "Product Added Successfully";
			                $result["product_id"] = $disp_insert_data_result;
			                $result["flag"] = "1";
				        }
				        else{
				        	$result["status"] = "Something Went Wrong, Please Try Again";
			                $result["flag"] = "0";
				        }
				    }
				    else{
				    	$result["status"] = "Please Select Image/s";
		            	$result["flag"] = "0";
			        }
			    }
			    else{
			    	$result["status"] = "Something Went Wrong";
	            	$result["flag"] = "0";
			    }
			}
	        else {
	            $result["status"] = "Server Authentication Failed";
	            $result["flag"] = "0";
	        }    
		    echo json_encode($result);exit();
		}catch (Exception $e) {
          var_dump($e->getMessage());
        }        
	}

	public function get_product_data(){
		try{
		      
            $return_data = $this->product_model->get_all_data('products');

            if(!empty($return_data)){
            	foreach ($return_data as $prod_key => $prod_value) {
	            	$user_data["name"] = $prod_value["prod_name"];
	            	$user_data["price"] = $prod_value["prod_price"];
					
	                $prod_imgs_cond = array("prod_id" => $prod_value["id"]);
	                $prod_images = $this->product_model->get_data_by_cols('product_images', "*",$prod_imgs_cond);
	                
	                if(!empty($prod_images)){
	            		$data_arr = array();
	            		$arr_push = array();
	            		foreach ($prod_images as $key => $value) {
	            			$img = file_get_contents(".".$value["prod_images"]);
	            			//$data_arr["image"] = ;
	            			array_push($arr_push, base64_encode($img));
	            		}
	            		$user_data["images"] = $arr_push;
		            }
		            else{
	                	$user_data["images"] = "";
		            }
		        } 
            	$result["status"] = "Success";
            	$result["data"] = $user_data;
            	$result["flag"] = "1";
            }
            else{
            	$result["status"] = "Fail";
            	$result["data"] = $return_data;
            	$result["flag"] = "0";
            }
			  
		    echo json_encode($result);exit();
		}catch (Exception $e) {
          var_dump($e->getMessage());
        }       
	}
}
