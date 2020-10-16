<?php
/*echo "<pre>";
print_r($product_data);die;*/
?>
<div class="shopping-cart">
  <?php if($product_data["flag"]){ ?>
    <!-- Title -->
    <div class="title">
      Shopping Bag
    </div>
    <?php if(!empty($product_data["data"])) foreach($product_data["data"] as $key => $value){ ?>
      <!-- Product #1 -->
      <div class="item">
        <div class="buttons">
          <span class="delete-btn"></span>
          <span class="like-btn"></span>
        </div>
     
        <div class="image">
          <?php $prod_img = (isset($value["product_images"][0])) ? $value["product_images"][0]: ""; ?>
          <img src="<?php echo 'http://localhost/inovationsolution'.$prod_img;?>" alt="" height="100" wieght="100"/>
        </div>
     
        <div class="description">
          <span><?php echo $value["product_name"]; ?></span>
        </div>
     
        
        <div class="total-price">INR <?php echo $value["product_price"]; ?></div>
      </div>
    <?php } ?> 
      
  <?php } else{ ?>
    <div class="item">
        <p><?php echo $product_data["status"];?></p>
      </div>
  <?php } ?>    
</div>
</body>
</html>