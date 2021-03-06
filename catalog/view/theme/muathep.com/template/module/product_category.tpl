<script>
   $('#tabs a').tabs();
</script> 
<div class="box ProductbyCategory">
   <div class="container">
      <div class="row">
         <div class="column_combine">
            <div class="tab-head">
               <div class="hometab-heading box-heading">Danh Mục Bán Chạy</div>
               <div id="tabss" class="htabs">

                  <ul class="etabs">
                    <?php foreach ($products as $k => $product) { ?>
                     <li class="tab">
                        <a href="#<?php echo $product[0]['category_id']; ?>"><?php echo $product[0]['category']; ?></a>
                     </li>
                    <?php } ?>
                  </ul>
               </div>
            </div>
            <div class="product-column">
               <?php foreach ($products as $k => $product) { ?>
               <div id="<?php echo $product[0]['category_id']; ?>" class="tab-content">
                  <div class="box-content">
                     <div class="customNavigation">
                        <a class="fa fa-angle-left prev">&nbsp;</a>
                        <a class="fa fa-angle-right next">&nbsp;</a>
                     </div>
                      
                     <div class="box-product product-carousel" id="productcategory0-carousel">
                       <?php foreach ($product as $produk) { ?>
                        <div class="slider-item">
                           <div class="product-block product-thumb transition">
                              <div class="product-block-inner">
                                 <div class="image_cover">
                                    <div class="image ">
                                       <a href="<?php echo $produk['href']; ?>">
                                          <img src="<?php echo $produk['thumb']; ?>" title="<?php echo $produk['name']; ?>" alt="<?php echo $produk['name']; ?>" class="img-responsive reg-image"/>
                                          <div class="image_content"><img class="img-responsive hover-image" src="<?php echo $produk['thumb']; ?>" title="<?php echo $produk['name']; ?>" alt="<?php echo $produk['name']; ?>"/></div>
                                       </a>
                                       
                                    </div>
                                    <div class="product_hover_block">
                                       <div class="action">
                                          <button class="wishlist" type="button"  title="Add to Wish List " onclick="wishlist.add('<?php echo $produk['product_id']; ?>');"></button>
                                          <button class="compare_button" type="button"  title="Add to compare " onclick="compare.add('<?php echo $produk['product_id']; ?>');"></button>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="product-details">
                                    <div class="caption">
                                       <h4><a href="<?php echo $produk['href']; ?>"><?php echo $produk['name']; ?></a></h4>
                                       <?php if ($produk['price']) { ?>
                                        <p class="price">
                                          <?php if (!$produk['special']) { ?>
                                          <?php echo $produk['price']; ?>
                                          <?php } else { ?>
                                          <span class="price-new"><?php echo $produk['special']; ?></span> <span class="price-old"><?php echo $produk['price']; ?></span>
                                          <?php } ?>
                                          <?php if ($produk['tax']) { ?>
                                          <span class="price-tax"><?php echo $text_tax; ?> <?php echo $produk['tax']; ?></span>
                                          <?php } ?>
                                        </p>
                                        <?php } ?>
                                       <div class="product_hover_block">
                                          <div class="action">
                                             <button type="button" class="cart_button" onclick="cart.add('<?php echo $produk['product_id']; ?>');" title="Add to Cart" ><?php echo $button_cart; ?></button>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                       <?php } ?>
                     </div>

                  </div>
                  <span class="productcategory0_default_width" style="display:none; visibility:hidden"> </span>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $('#tabss a').tabss();
</script>
