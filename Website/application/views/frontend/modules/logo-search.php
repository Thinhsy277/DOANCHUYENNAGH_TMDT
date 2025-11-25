<section class="logo-search">
  <div class="container">
  <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 logo">
  <a href="<?php echo base_url() ?>"><img src="<?php echo base_url() ?>public/images/loho3.png"></a>
</div>

    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 search">
      <div class="contact-row">
        <div class="phone inline">
          <i class="icon fa fa-phone"></i> (84) 366 765 888
        </div>
        <div class="contact inline">
          <i class="icon fa fa-envelope"></i> sos@gmail.com
        </div>
      </div>
      <form action="search" method="get" role="form">
        <div class="input-search">
          <input type="text" class="form-control" id="search_text" name="search" placeholder="Nhập từ khóa để tìm kiếm...">
          <button>
         
              <i class="fa fa-search"></i>
            </button>
          </div>
        </form>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 hidden-xs" style="padding: 24px;">
       <!-- Cart -->
       <div class="cart_header" style="

    display: flex;

    align-items: center;

    gap: 10px;

">



    <a href="gio-hang" title="Giỏ hàng" style="

        display: flex;

        align-items: center;

        gap: 10px;

        text-decoration: none;

        color: inherit;

    ">



        <!-- ICON GIỎ HÀNG -->

        <span class="cart_header_icon" style="

            width: 40px;

            height: 40px;

            display: flex;

            justify-content: center;

            align-items: center;

        ">

            <img src="<?php echo base_url() ?>public/images/cart2.png"

                 alt="Cart"

                 style="width: 32px; height: 32px; object-fit: contain;">

        </span>



        <!-- TEXT BÊN PHẢI -->

        <span class="box_text" style="

            display: flex;

            flex-direction: column;

            justify-content: center;

            line-height: 1.2;

        ">



            <!-- DÒNG GIỎ HÀNG + SỐ LƯỢNG -->

            <strong class="cart_header_count" style="font-size: 14px;">

                Giỏ hàng

                <span>(

                    <?php  

                        if($this->session->userdata('cart')){

                            $val = $this->session->userdata('cart');

                            echo count($val);

                        }else{

                            echo 0;

                        }

                    ?>

                )</span>

            </strong>



            <!-- DÒNG GIÁ TIỀN -->

            <span class="cart_price" style="font-size: 12px; color: #444;">

                <?php if($this->session->userdata('cart')): ?>

                    <?php 

                        $cart = $this->session->userdata('cart');

                        $money = 0;



                        foreach ($cart as $key => $value):

                            $row = $this->Mproduct->product_detail_id($key);



                            if($row['price_sale'] > 0){

                                $total = $row['price_sale'] * $value;

                            }else{

                                $total = $row['price'] * $value;

                            }



                            $money += $total;

                        endforeach;



                        echo number_format($money).' VNĐ';

                    ?>

                <?php else: ?>

                    <p style="margin: 0;">0 VNĐ</p>

                <?php endif; ?>

            </span>



        </span>



    </a>



    <!-- BOX CLONE CART (GIỮ NGUYÊN) -->

    <div class="cart_clone_box">

        <div class="cart_box_wrap hidden">



            <div class="cart_item original clearfix">

                <div class="cart_item_image"></div>



                <div class="cart_item_info">

                    <p class="cart_item_title"><a href="" title=""></a></p>

                    <span class="cart_item_quantity"></span>

                    <span class="cart_item_price"></span>

                    <span class="remove"></span>

                </div>

            </div>



        </div>

    </div>



</div>
   <!-- End Cart -->
   <!-- Account -->
   <div class="user_login">
     <a href="thong-tin-khach-hang" title="Tài khoản">
      <div class="user_login_icon">
       <img src="<?php echo base_url() ?>public/images/user.png" alt="Cart">
     </div>
     <div class="box_text">
       <strong>Tài khoản</strong>
       <!--<span class="cart_price">Đăng nhập, đăng ký</span>-->
       
     </div>
   </a>
 </div>
</div>
</div>
</section>
