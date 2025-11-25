<section id="content">
	<div class="container account">
        <aside class="col-right sidebar col-md-3 col-xs-12">
            <div class="block block-account">
                <div class="general__title" style="background: #ff0000; color: white; text-align: center; padding: 10px 0; font-size: 18px; font-weight: 500; margin-bottom: 20px;">
                    <h2 style="margin: 0;"><span>Thông tin tài khoản</span></h2>
                </div>
                <div class="block-content">
                    <?php if(!empty($info)): ?>
                    <p>Tài khoản: <strong><?php echo $info['username'] ?></strong></p>
                    <p>Họ và tên: <strong><?php echo $info['fullname'] ?></strong></p>
                    <p>Email: <strong><?php echo $info['email'] ?></strong></p>
                    <p>Số điện thoại: <strong><?php echo $info['phone'] ?></strong></p>
                    <?php else: ?>
                    <p>Không tìm thấy thông tin tài khoản.</p>
                    <?php endif; ?>
                </div>
                <button class="btn"><a href="reset_password">Đổi mật khẩu</a></button>
            </div>
        </aside>
        <div class="col-main col-md-9 col-sm-12">
            <div class="my-account">

                <?php 
                $currentCustomerId = isset($customerId) ? $customerId : (!empty($info) && isset($info['id']) ? $info['id'] : 0);
                $pendingOrders = $currentCustomerId ? $this->Minfocustomer->order_listorder_customerid_not($currentCustomerId) : [];
                if(!empty($pendingOrders))
                { ?>
                    <div class="general__title" style="background: #ff0000; color: white; text-align: center; padding: 10px 0; font-size: 18px; font-weight: 500; margin-bottom: 20px;">
                        <h2 style="margin: 0;"><span>Danh sách đơn hàng chưa duyệt</span></h2>
                    </div>
                    <table style="padding-right: 10px; width: 100%;">
                        <thead style="border: 1px solid silver;">
                            <tr>
                                <th class="text-left" style="width: 85px; padding:5px 10px">Đơn hàng</th>
                                <th style="width: 110px; padding:5px 10px">Ngày</th>
                                <th style="width: 150px;text-align: center; padding:5px 10px">
                                    Giá trị đơn hàng 
                                </th>
                                <th style="width: 150px; text-align: center;">Trạng thái đơn hàng</th>
                                <th style="text-align: center;" colspan="2">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody style="border: 1px solid silver;">
                        <?php foreach ($pendingOrders as $value):?>
                                <tr style="border-bottom: 1px solid silver;">
                                    <td style="padding:5px 10px;">#<?php echo $value['orderCode'] ?></td>
                                    <td style="padding:5px 10px;"><?php echo $value['orderdate'] ?></td>
                                    <td style="text-align: center; padding:5px 10px;"><span class="price-2"><?php echo number_format($value['money']) ?> VNĐ</span></td>
                                    <td style="padding:5px 10px; text-align: center;">
                                       <?php
                                       switch ($value['status']) {
                                        case '0':
                                        echo 'Đang đợi duyệt';
                                        break;
                                    }
                                    $id = $value['id'];
                                    ?>
                                </td>
                                <td width="70">
                                    <span> <a style="color: #ff0000;" href="account/orders/<?php echo $value['id'] ?>">Xem chi tiết</a></span>
                                </td>
                                <td width="70">
                                    <a style="color: red;" href="thongtin/update/<?php echo $value['id'];?>" onclick="return confirm('Xác nhận hủy đơn hàng này ?')">Hủy đơn hàng</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo '<p>Hiện chưa có đơn hàng chờ duyệt.</p>';
            }
            ?>
            
            <div class="general__title" style="background: #ff0000; color: white; text-align: center; padding: 10px 0; font-size: 18px; font-weight: 500; margin-bottom: 20px;">
                <h2 style="margin: 0;"><span>Danh sách đơn hàng</span></h2>
            </div>
            <div class="table-order">
                <table style="padding-right: 10px; width: 100%;">
                    <thead style="border: 1px solid silver;">
                        <tr>
                            <th class="text-left" style="width: 85px; padding:5px 10px">Đơn hàng</th>
                            <th style="width: 110px; padding:5px 10px">Ngày</th>
                            <th style="width: 150px;text-align: center; padding:5px 10px">
                                Giá trị đơn hàng 
                            </th>
                            <th style="width: 150px; text-align: center;">Trạng thái đơn hàng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody style="border: 1px solid silver;">
                        <?php $order = $currentCustomerId ? $this->Minfocustomer->order_listorder_customerid($currentCustomerId) : [];
                        if(!empty($order)):
                        foreach ($order as $value):?>
                            <tr style="border-bottom: 1px solid silver;">
                                <td style="padding:5px 10px;">#<?php echo $value['orderCode'] ?></td>
                                <td style="padding:5px 10px;"><?php echo $value['orderdate'] ?></td>
                                <td style="text-align: center; padding:5px 10px;"><span class="price-2"><?php echo number_format($value['money']) ?> VNĐ</span></td>
                                <td style="padding:5px 10px; text-align: center;">
                                   <?php
                                   switch ($value['status']) {
                                    case '0':
                                    echo 'Đang đợi duyệt';
                                    break;
                                    case '1':
                                    echo 'Đang giao hàng';
                                    break;
                                    case '2':
                                    echo 'Đã giao';
                                    break;
                                    case '3':
                                    echo 'Khách hàng đã hủy';
                                    break;
                                    case '4':
                                    echo 'Nhân viên đã hủy';
                                    break;
                                }
                                $id = $value['id'];
                                ?>
                            </td>
                            <td width="70">
                                <span> <a style="color: #ff0000;" href="account/orders/<?php echo $value['id'] ?>">Xem chi tiết</a></span>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="5" style="text-align:center; padding:15px;">Bạn chưa có đơn hàng nào.</td></tr>
                        <?php endif; ?>
                </tbody>
            </table>


        </div>
    </div>
</div>
</section>
