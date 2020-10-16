<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller {

	function __construct() { 
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form','url','security','file'));
        $this->load->model('product_model');
        date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
    }

	public function add_to_cart(){
		try{
			$auth_data = $this->product_model->get_data('server_auth_token_master');
	        $auth_code = $this->input->request_headers();
	      
	        if(isset($auth_data['token']) && isset($auth_code['Authorization']) && ($auth_code['Authorization'] == $auth_data['token'])) {
				$request = file_get_contents('php://input');
		        $data1 = json_decode($request, TRUE);

		        $user_id = (isset($data1["user_id"]) && $data1["user_id"] != "") ? $data1["user_id"] : "";
		        $prod_id = (isset($data1["product_id"]) && $data1["product_id"] != "")? $data1["product_id"] : "";
		        
	        	if($user_id == ""){
		        	$result["status"] = "User ID can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }

		        if($prod_id == ""){
		        	$result["status"] = "Product ID can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }
		        $cond = array("id" => $user_id);
	            $user_data = $this->product_model->get_data_by_cols('users', "*",$cond);
		        if(!empty($user_data)){
		        	$cart_cond = array("id" => $prod_id, "user_id" => $user_id);
	            	$cart_data = $this->product_model->get_data_by_cols('cart', "*",$cart_cond);
	            	if(empty($cart_data)){
				        $insrt_data["prod_id"] = $prod_id;
				        $insrt_data["user_id"] = $user_id;
				        $add_to_cart = $this->product_model->insert_data($insrt_data, 'cart');
				        if($add_to_cart){
				        	$result["status"] = "Data added into cart successfully";
				        	$result["cart_id"] = $add_to_cart;
		            		$result["flag"] = "1";
				        }
				        else{
				        	$result["status"] = "Something went wrong";
		            		$result["flag"] = "0";
				        }
				    }
				    else{
				    	$result["status"] = "Product already present in a cart";
	            		$result["flag"] = "0";
				    }    
		        }
		        else{
		        	$result["status"] = "User doesn't exist";
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

	public function get_user_cart_data(){
		try{
			$auth_data = $this->product_model->get_data('server_auth_token_master');
	        $auth_code = $this->input->request_headers();
	      
	        if(isset($auth_data['token']) && isset($auth_code['Authorization']) && ($auth_code['Authorization'] == $auth_data['token'])) {
				$request = file_get_contents('php://input');
		        $data1 = json_decode($request, TRUE);

		        $user_id = (isset($data1["user_id"]) && $data1["user_id"] != "") ? $data1["user_id"] : "";
		        
	        	if($user_id == ""){
		        	$result["status"] = "User ID can't be empty";
	            	$result["flag"] = "0";
	            	echo json_encode($result);exit();
		        }

		        $cond = array("id" => $user_id);
	            $user_details = $this->product_model->get_data_by_cols('users', "*",$cond);
		        if(!empty($user_details)){
		        	$cart_cond = array("cart_staus" => "Y", "user_id" => $user_id);
	            	$cart_data = $this->product_model->get_data_by_cols('cart', "*",$cart_cond);
	            	if(!empty($cart_data)){
	            		$data_arr = array();
	            		foreach ($cart_data as $key => $value) {
	            			$prod_id = $value["prod_id"];
	            			$prod_data = $this->product_model->get_data_by_cols('products', "*",array("id" => $prod_id));

	            			if(!empty($prod_data)){
	            				$return_data["product_name"] = $prod_data[0]["prod_name"];
	            				$return_data["product_price"] = $prod_data[0]["prod_price"];
	            				$prod_imgs_cond = array("prod_id" => $prod_id);
	                			$prod_images = $this->product_model->get_data_by_cols('product_images', "*",$prod_imgs_cond);
	            				if(!empty($prod_images)){
				            		
				            		$arr_push = array();
				            		foreach ($prod_images as $key => $value) {
				            			array_push($arr_push, $value["prod_images"]);
				            		}
				            		$return_data["product_images"] = $arr_push;
					            }
					            else{
				                	$return_data["product_images"] = "";
					            }
							}
							array_push($data_arr, $return_data);
						}
						$result["status"] = "Success";
						$result["data"] = $data_arr;
						$result["flag"] = "1";	
				    }
				    else{
				    	$result["status"] = "No product added into the cart";
	            		$result["flag"] = "0";
				    }    
		        }
		        else{
		        	$result["status"] = "User doesn't exist";
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

	public function view_cart(){
		try{
			$auth_data = $this->product_model->get_data('server_auth_token_master');
	        $auth_code = $this->input->request_headers();

		        $user_id = 1;
		        $cond = array("id" => $user_id);
	            $user_details = $this->product_model->get_data_by_cols('users', "*",$cond);
		        if(!empty($user_details)){
		        	$cart_cond = array("cart_staus" => "Y", "user_id" => $user_id);
	            	$cart_data = $this->product_model->get_data_by_cols('cart', "*",$cart_cond);
	            	if(!empty($cart_data)){
	            		$data_arr = array();
	            		foreach ($cart_data as $key => $value) {
	            			$prod_id = $value["prod_id"];
	            			$prod_data = $this->product_model->get_data_by_cols('products', "*",array("id" => $prod_id));

	            			if(!empty($prod_data)){
	            				$return_data["product_name"] = $prod_data[0]["prod_name"];
	            				$return_data["product_price"] = $prod_data[0]["prod_price"];
	            				$prod_imgs_cond = array("prod_id" => $prod_id);
	                			$prod_images = $this->product_model->get_data_by_cols('product_images', "*",$prod_imgs_cond);
	            				if(!empty($prod_images)){
				            		
				            		$arr_push = array();
				            		foreach ($prod_images as $key => $value) {
				            			array_push($arr_push, $value["prod_images"]);
				            		}
				            		$return_data["product_images"] = $arr_push;
					            }
					            else{
				                	$return_data["product_images"] = "";
					            }
							}
							array_push($data_arr, $return_data);
						}
						$result["status"] = "Success";
						$result["data"] = $data_arr;
						$result["flag"] = "1";	
				    }
				    else{
				    	$result["status"] = "No product added into the cart";
	            		$result["flag"] = "0";
				    }    
		        }
		        else{
		        	$result["status"] = "User doesn't exist";
	            	$result["flag"] = "0";
		        }
		    $data["product_data"] = $result;    
		    $this->load->view('header');    
			$this->load->view('cart-list',$data);
		}catch (Exception $e) {
          var_dump($e->getMessage());
        }        
	}
}
