
<section id="header">
	<nav class="navbar" style="z-index: 9999">
		<div class="container">
			<div class="col-md-12 col-lg-12">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed pull-right" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<div class="icon-cart-mobile hidden-md hidden-lg">
						<a href="<?php echo base_url('gio-hang'); ?>">
							<i class="fa fa-shopping-cart" aria-hidden="true" style="color: #ff0000; font-size: 17px;"></i>
							<span>(<?php  
                               if($this->session->userdata('cart')){
                                $val = $this->session->userdata('cart');
                                echo count($val);
                            }else{
                                echo 0;
                            }
                            ?>)</span>
                        </a>
                    </div>
                </div>
                <div id="navbar" class="collapse navbar-collapse" style="color: #ff0000">
                	<ul class="nav navbar navbar-nav" id="nav1">
                		<li><a href="<?php echo base_url(); ?>"><i class="bi bi-house"></i>Trang chủ</a></li>
                		<li><a href="<?php echo base_url('san-pham/1'); ?>">Sản phẩm</a></li>
                		<li><a href="<?php echo base_url('tin-tuc/1'); ?>">Tin tức</a></li>
                		<li><a href="<?php echo base_url('gioi-thieu'); ?>">Giới thiệu</a></li>
                		<li><a href="<?php echo base_url('lien-he'); ?>">Liên hệ</a></li>
                		<li><a href="<?php echo base_url('thong-tin-khach-hang'); ?>">Tài khoản</a></li>
                		<?php 
                		if($this->session->userdata('sessionKhachHang')){
                			echo "<li><a href='".base_url('dang-xuat')."'>Thoát</a></li>";
                		}else{
                			echo "<li><a href='".base_url('dang-ky')."'>Đăng ký</a></li>";
                			echo "<li><a href='".base_url('dang-nhap')."'>Đăng nhập</a></li>";
                		}
                		?>
                	</ul>
                	<ul class="nav navbar navbar-nav pull-right" id="nav2">
                		<?php 
                		if($this->session->userdata('sessionKhachHang')){
                			$name=$this->session->userdata('sessionKhachHang_name');
                			echo "<li><a href='#'>Xin Chào: $name</a></li>";
                			echo "<li><a href='".base_url('dang-xuat')."'>Thoát</a></li>";
                		}else{
                			echo "<li><a href='".base_url('dang-ky')."'>Đăng ký</a></li>";
                			echo "<li><a href='".base_url('dang-nhap')."'>Đăng nhập</a></li>";
                		}
						?>
                	</ul>
                </div>
            </div>
        </div>
    </nav>
</section>