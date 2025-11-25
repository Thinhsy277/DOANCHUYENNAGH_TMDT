<?php echo form_open('info-order'); ?>
<?php  
if(!$this->session->userdata('cart')){
    redirect('gio-hang');
}else{
    $user = $this->session->userdata('sessionKhachHang');
}
?>
<section id="checkout-cart" style="position: relative; z-index: 1; padding: 20px 0; min-height: 100vh; background:#f9f9f9;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 15px;">

        <?php if(!$this->session->userdata('sessionKhachHang')): ?>
        <div style="font-size: 16px; padding: 15px 0; color: #ccc; text-align: center; background:#fff; margin-bottom:20px; border-radius:5px;">
                     Bạn có tài khoản? 
            <a href="<?php echo base_url('dang-nhap'); ?>" style="color: #ff0000; font-weight:bold;">Ấn vào đây để đăng nhập</a>
        </div>
        <?php endif; ?>

        <div class="checkout-main-wrapper" style="display:flex; flex-wrap:wrap; margin:0 -15px; background:transparent;">

            <!-- CỘT TRÁI: 60% -->
            <div class="checkout-left" style="flex:0 0 60%; max-width:60%; padding:0 15px; box-sizing:border-box;">
                <div style="background: white; border: 1px solid #ddd; border-radius:8px; overflow:hidden;">
                    <p style="background: white; color: #333; text-align: center; padding: 15px 0; font-size: 18px; font-weight: 600; margin:0; border-bottom:1px solid #eee;">
                        Địa chỉ giao hàng của quý khách
                    </p>

                    <div class="wrap-info" style="padding:20px;">
                        <table class="table tinfo" style="width:100%; border-collapse:separate; border-spacing:0 10px;">
                            <tbody>
                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Khách hàng: <span style="color:red;">*</span></td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Họ và tên" name="name" value="<?php echo isset($user['fullname']) ? $user['fullname'] : ''; ?>" <?php if($this->session->userdata('sessionKhachHang')) echo 'readonly'; ?> style="width:100%;">
                                        <div class="error"><?php echo form_error('name')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Email: <span style="color:red;">*</span></td>
                                    <td>
                                        <input type="text" class="form-control" name="<?php echo $this->session->userdata('sessionKhachHang') ? 'tv' : 'email'; ?>" value="<?php echo isset($user['email']) ? $user['email'] : ''; ?>" placeholder="Email" <?php if($this->session->userdata('sessionKhachHang')) echo 'readonly'; ?> style="width:100%;">
                                        <div class="error"><?php echo form_error('email')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Số điện thoại: <span style="color:red;">*</span></td>
                                    <td>
                                        <input type="text" class="form-control" placeholder="Số điện thoại" name="phone" value="<?php echo isset($user['phone']) ? $user['phone'] : ''; ?>" <?php if($this->session->userdata('sessionKhachHang')) echo 'readonly'; ?> style="width:100%;">
                                        <div class="error"><?php echo form_error('phone')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Tỉnh/Thành phố: <span style="color:red;">*</span></td>
                                    <td>
                                        <div class="select-wrapper" style="position:relative; width:100%;">
                                            <select name="city" id="province" onchange="renderDistrict()" class="form-control next-select custom-select" style="width:100%; padding:8px 35px 8px 12px; border:1px solid #ddd; border-radius:4px; background-color:#fff; font-size:14px; color:#333; appearance:none; -webkit-appearance:none; -moz-appearance:none; cursor:pointer;">
                                            <option value="">--- Chọn tỉnh thành ---</option>
                                                <?php 
                                                $provinces = $this->Mprovince->province_all();
                                                if(!empty($provinces)):
                                                    foreach($provinces as $row): ?>
                                                        <option value="<?php echo htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8'); ?></option>
                                                <?php 
                                                    endforeach;
                                                endif; ?>
                                        </select>
                                            <span class="select-arrow" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none; transition:transform 0.3s ease; color:#666;">▼</span>
                                        </div>
                                        <div class="error"><?php echo form_error('city')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:20px; vertical-align:top; color:#555;">Quận/Huyện: <span style="color:red;">*</span></td>
                                    <td>
                                        <div class="select-wrapper" style="position:relative; width:100%;">
                                            <select name="DistrictId" id="district" class="form-control next-select custom-select" style="width:100%; padding:8px 35px 8px 12px; border:1px solid #ddd; border-radius:4px; background-color:#fff; font-size:14px; color:#333; appearance:none; -webkit-appearance:none; -moz-appearance:none; cursor:pointer;">
                                            <option value="">--- Chọn quận huyện ---</option>
                                        </select>
                                            <span class="select-arrow" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none; transition:transform 0.3s ease; color:#666;">▼</span>
                                        </div>
                                        <div class="error"><?php echo form_error('DistrictId')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Hình thức thanh toán: <span style="color:red;">*</span></td>
                                    <td>
                                        <div class="select-wrapper" style="position:relative; width:100%;">
                                            <select name="payment_type" class="form-control next-select custom-select" style="width:100%; padding:8px 35px 8px 12px; border:1px solid #ddd; border-radius:4px; background-color:#fff; font-size:14px; color:#333; appearance:none; -webkit-appearance:none; -moz-appearance:none; cursor:pointer;">
                                            <option value="1">Thanh toán online</option>
                                                <option value="2">Ship COD</option>
                                        </select>
                                            <span class="select-arrow" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); pointer-events:none; transition:transform 0.3s ease; color:#666;">▼</span>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Địa chỉ giao hàng: <span style="color:red;">*</span></td>
                                    <td>
                                        <textarea name="address" placeholder="Địa chỉ giao hàng:" class="form-control" rows="4" style="width:100%; height:auto; resize:vertical;"><?php echo isset($user['address']) ? $user['address'] : ''; ?></textarea>
                                        <div class="error"><?php echo form_error('address')?></div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="width30 text-right td-right-order" style="padding-right:15px; vertical-align:top; color:#555;">Mã giảm giá (nếu có):</td>
                                    <td style="position:relative;">
                                        <input id="coupon" type="text" class="form-control" placeholder="Mã giảm giá" name="coupon" style="width:100%; padding-right:90px;">
                                        <a onclick="checkCoupon()" style="position:absolute; right:5px; top:50%; transform:translateY(-50%); background:#d0021b; color:#fff; padding:8px 12px; border-radius:4px; font-size:13px; cursor:pointer;">Sử dụng</a>
                                        <div class="error" id="result_coupon" style="margin-top:5px;"></div>
                                </td>
                                </tr>

                            <tr>
                                    <td></td>
                                    <td style="padding-top:20px;">
                                        <button type="submit" name="dathang" style="width:300px; height:50px; background:#d0021b; color:#fff; border:none; border-radius:5px; font-size:18px; font-weight:bold; cursor:pointer; float:right;">Đặt hàng</button>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: 40% -->
            <div class="checkout-right" style="flex:0 0 40%; max-width:40%; padding:0 15px; box-sizing:border-box;">
                <div style="background: white; border: 1px solid #ddd; border-radius:8px; overflow:hidden;">
                    <p style="background: white; color: #333; text-align: center; padding: 15px 0; font-size: 18px; font-weight: 600; margin:0; border-bottom:1px solid #eee;">
                        Thông tin đơn hàng
                    </p>

                    <table class="table" style="color: #333; margin:25px; width:calc(100% - 50px);">
                        <tbody>
                            <tr style="border-bottom:2px solid #eee;">
                                <td style="width:150px; padding:10px 0;"><strong>Sản phẩm</strong></td>
                                <td class="text-center"><strong>Số lượng</strong></td>
                                <td class="text-center"><strong>Giá</strong></td>
                                <td class="text-center"><strong>Tổng</strong></td>
                            </tr>

                                            <?php
                            $money = 0;
                            if($this->session->userdata('cart')):
                                $data = $this->session->userdata('cart');
                                foreach ($data as $key => $value):
                                    $row = $this->Mproduct->product_detail_id($key);
                                    $price_end = ($row['price_sale'] > 0) ? $row['price_sale'] : $row['price'];
                                    $total = $price_end * $value;
                                    $money += $total;
                                            ?>
                            <tr style="border-bottom:1px dashed #ddd;">
                                <td style="padding:8px 0; font-size:14px;"><?php echo $row['name']; ?></td>
                                <td class="text-center"><?php echo $value; ?></td>
                                <td class="text-center"><?php echo number_format($price_end); ?>đ</td>
                                <td class="text-right"><?php echo number_format($total); ?>đ</td>
                                    </tr>
                            <?php endforeach; endif; ?>

                                <tr>
                                <td colspan="3" style="padding:10px 0;"><strong>Tổng cộng:</strong></td>
                                <td class="text-right"><strong><?php echo number_format($money); ?>đ</strong></td>
                                </tr>

                            <tr>
                                <td colspan="3" style="padding:5px 0; font-size:13px; color:#666;">Phí giao hàng:</td>
                                <td class="text-right"><?php echo number_format($this->Mconfig->config_price_ship()); ?>đ</td>
                         </tr>

                            <?php if($this->session->userdata('coupon_price')): 
                                $coupon_price = $this->session->userdata('coupon_price');
                            ?>
                            <tr style="color:#d0021b;">
                                <td colspan="3" style="padding:8px 0;">
                                    <strong>Voucher giảm giá:</strong> 
                                    <span onclick="removeCoupon()" style="cursor:pointer; color:red; margin-left:5px;">[Xóa]</span>
                            </td>
                                <td class="text-right">-<?php echo number_format($coupon_price); ?>đ</td>
                            </tr>
                            <?php endif; ?>

                            <tr style="background:#f4f4f4; font-size:16px;">
                                <td colspan="3" style="padding:15px 0;">
                                    <strong style="color:red; font-size:18px;">Thành tiền</strong><br>
                                    <small style="color:#666;">(Tổng số tiền thanh toán)</small>
                            </td>
                                <td class="text-right">
                                    <strong style="color:red; font-size:20px;">
                                        <?php 
                                        $final_price = $money + $this->Mconfig->config_price_ship();
                                        if($this->session->userdata('coupon_price')) {
                                            $final_price -= $this->session->userdata('coupon_price');
                                }
                                        echo number_format($final_price).'đ'; 
                                        ?>
                                    </strong>
                        </td>
                    </tr>

            </tbody>
        </table>
    </div>
</div>

</div>

</div>
</section>


<style>
/* Responsive: xếp dọc trên mobile */
@media (max-width: 991px) {
    .checkout-left, .checkout-right {
        flex: 0 0 100% !important;
        max-width: 100% !important;
        margin-bottom: 20px;
    }
    .checkout-left { order:1; }
    .checkout-right { order:2; }
}

.error { color:red; font-size:13px; margin-top:5px; }
.form-control { padding:10px; border:1px solid #ddd; border-radius:4px; }

/* Fix dropdown hiển thị đúng nội dung - CHỈ 1 MŨI TÊN */
#checkout-cart .select-wrapper {
    position: relative;
    width: 100%;
}

#checkout-cart select.form-control,
#checkout-cart select.next-select,
#checkout-cart select.custom-select {
    display: block !important;
    width: 100% !important;
    padding: 8px 35px 8px 12px !important;
    border: 1px solid #ddd !important;
    border-radius: 4px !important;
    background-color: #fff !important;
    background-image: none !important;
    font-size: 14px !important;
    color: #333 !important;
    line-height: 1.5 !important;
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    cursor: pointer !important;
}

/* Mũi tên tùy chỉnh - CHỈ 1 MŨI TÊN */
#checkout-cart .select-arrow {
    position: absolute !important;
    right: 12px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    pointer-events: none !important;
    transition: transform 0.3s ease !important;
    color: #666 !important;
    font-size: 12px !important;
    line-height: 1 !important;
    z-index: 1 !important;
}

/* Hiệu ứng xoay mũi tên khi dropdown mở */
#checkout-cart select.custom-select:focus + .select-arrow,
#checkout-cart select.custom-select.active + .select-arrow {
    transform: translateY(-50%) rotate(180deg) !important;
}

#checkout-cart select.form-control option,
#checkout-cart select.next-select option,
#checkout-cart select.custom-select option {
    padding: 8px 12px !important;
    background-color: #fff !important;
    color: #333 !important;
    display: block !important;
    visibility: visible !important;
}

#checkout-cart select.form-control:focus,
#checkout-cart select.next-select:focus,
#checkout-cart select.custom-select:focus {
    border-color: #66afe9 !important;
    outline: 0 !important;
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6) !important;
}

/* Đảm bảo dropdown hiển thị đúng khi mở */
#checkout-cart select.form-control option:checked,
#checkout-cart select.next-select option:checked,
#checkout-cart select.custom-select option:checked {
    background-color: #f0f0f0 !important;
    color: #333 !important;
}
</style>

<script>
    // Xử lý hiệu ứng mũi tên khi click vào dropdown
    jQuery(document).ready(function($) {
        // Xử lý cho tất cả các select có class custom-select
        $('.custom-select').on('focus click', function() {
            $(this).addClass('active');
            $(this).siblings('.select-arrow').css('transform', 'translateY(-50%) rotate(180deg)');
        }).on('blur', function() {
            $(this).removeClass('active');
            $(this).siblings('.select-arrow').css('transform', 'translateY(-50%) rotate(0deg)');
        }).on('change', function() {
            // Giữ mũi tên quay lên khi đang chọn
            var $this = $(this);
            setTimeout(function() {
                if (!$this.is(':focus')) {
                    $this.removeClass('active');
                    $this.siblings('.select-arrow').css('transform', 'translateY(-50%) rotate(0deg)');
                }
            }, 100);
        });
    });

    function renderDistrict(){
        var provinceid = $("#province").val();
        
        // Reset district dropdown
        $('#district').html('<option value="">--- Chọn quận huyện ---</option>');
        
        if(!provinceid || provinceid === '') {
            return;
        }
        
        // Gửi AJAX request
        $.ajax({
            url: "<?php echo base_url(); ?>giohang/district",
            type: 'POST',
            data: {provinceid: provinceid},
            dataType: 'html', // Expect HTML response
            success: function(data) {
                // Parse nếu là JSON (fallback)
                try {
                    var parsed = JSON.parse(data);
                    $('#district').html(parsed);
                } catch(e) {
                    // Nếu không phải JSON, dùng trực tiếp
                $('#district').html(data);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading districts:', error);
                $('#district').html('<option value="">--- Lỗi tải danh sách quận huyện ---</option>');
            }
        });
    }
    function checkCoupon(){
        var code = $("input[name='coupon']").val();
        $.post("<?php echo base_url(); ?>giohang/coupon", {code: code}, function(data){
                $('#result_coupon').html(data);
            if(data.indexOf('thành công') > -1 || data.indexOf('áp dụng') > -1){
                setTimeout(() => location.reload(), 800);
            }
        });
    }
    function removeCoupon(){
        $.post("<?php echo base_url(); ?>giohang/removecoupon", function(){
            location.reload();
        });
    }
</script>

