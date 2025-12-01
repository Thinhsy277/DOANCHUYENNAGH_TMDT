<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-comments"></i> Lịch sử tương tác chatbot</h1>
		<div class="breadcrumb">
			<a class="btn btn-default btn-sm" href="admin/chatbox">
				<span class="glyphicon glyphicon-refresh"></span> Tải lại
			</a>
		</div>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box" id="view">
					<div class="box-header with-border">
						<h3 class="box-title">Bộ lọc</h3>
					</div>
					<div class="box-body">
						<form method="get" action="admin/chatbox">
							<div class="row">
								<div class="col-md-4">
									<label>Khách hàng</label>
									<select class="form-control" name="user_id">
										<option value="">-- Tất cả --</option>
										<option value="0" <?php echo (isset($filters['user_id']) && $filters['user_id'] === '0') ? 'selected' : ''; ?>>Khách chưa đăng nhập</option>
										<?php if(!empty($conversationUsers)): ?>
											<?php foreach($conversationUsers as $userRow): ?>
												<?php if($userRow['user_id'] == 0 || empty($userRow['fullname'])) continue; ?>
												<option value="<?php echo $userRow['user_id']; ?>" <?php echo ($filters['user_id'] == $userRow['user_id']) ? 'selected' : ''; ?>>
													<?php echo $userRow['fullname']; ?> (<?php echo $userRow['total_messages']; ?> tin)
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>
								<div class="col-md-3">
									<label>Người gửi</label>
									<select name="sender" class="form-control">
										<option value="">-- Tất cả --</option>
										<option value="user" <?php echo ($filters['sender'] == 'user') ? 'selected' : ''; ?>>Khách hàng</option>
										<option value="bot" <?php echo ($filters['sender'] == 'bot') ? 'selected' : ''; ?>>Chatbot</option>
									</select>
								</div>
								<div class="col-md-3">
									<label>Từ khóa nội dung</label>
									<input type="text" name="keyword" class="form-control" placeholder="Ví dụ: iPhone, sale..." value="<?php echo isset($filters['keyword']) ? htmlspecialchars($filters['keyword']) : ''; ?>">
								</div>
								<div class="col-md-2">
									<label>&nbsp;</label>
									<div>
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Lọc</button>
										<a href="admin/chatbox" class="btn btn-default">Xóa</a>
									</div>
								</div>
							</div>
						</form>
						<hr>
						<h3 class="box-title">Tổng quan người dùng tương tác</h3>
						<div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
									<tr>
										<th>Khách hàng</th>
										<th class="text-center">Tổng tin nhắn</th>
										<th class="text-center">Tin khách</th>
										<th class="text-center">Tin bot</th>
										<th>Lần cuối</th>
										<th>Nội dung cuối</th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($conversationUsers)): ?>
										<?php foreach($conversationUsers as $row): ?>
											<tr <?php echo ($filters['user_id'] !== null && $filters['user_id'] !== '' && $filters['user_id'] == $row['user_id']) ? 'class="info"' : ''; ?>>
												<td>
													<strong><?php echo $row['fullname'] ? $row['fullname'] : 'Khách chưa đăng nhập'; ?></strong><br>
													<small><?php echo $row['email'] ? $row['email'] : 'Không có email'; ?></small><br>
													<small><?php echo $row['phone'] ? $row['phone'] : 'Không có SĐT'; ?></small>
												</td>
												<td class="text-center"><span class="badge bg-blue"><?php echo $row['total_messages']; ?></span></td>
												<td class="text-center"><?php echo $row['user_messages']; ?></td>
												<td class="text-center"><?php echo $row['bot_messages']; ?></td>
												<td><?php echo $row['last_message_at'] ? date('d/m/Y H:i', strtotime($row['last_message_at'])) : '-'; ?></td>
												<td><?php echo $row['last_user_message'] ? htmlspecialchars($row['last_user_message']) : '-'; ?></td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="6" class="text-center text-muted">Chưa có dữ liệu nào.</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
						<hr>
						<h3 class="box-title">Chi tiết cuộc hội thoại gần nhất (tối đa <?php echo count($history); ?> tin)</h3>
						<div class="table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th style="width: 180px;">Thời gian</th>
										<th>Người gửi</th>
										<th>Nội dung</th>
										<th>Intent</th>
									</tr>
								</thead>
								<tbody>
									<?php if(!empty($history)): ?>
										<?php foreach($history as $message): ?>
											<tr class="<?php echo $message['sender'] == 'user' ? 'success' : ($message['sender'] == 'bot' ? 'warning' : ''); ?>">
												<td><?php echo date('d/m/Y H:i', strtotime($message['created_at'])); ?></td>
												<td>
													<?php echo $message['sender'] == 'user' ? 'Khách hàng' : ucfirst($message['sender']); ?><br>
													<small>
														<?php 
															$label = $message['fullname'] ? $message['fullname'] : 'Khách chưa đăng nhập';
															echo htmlspecialchars($label);
														?>
													</small>
												</td>
												<td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
												<td>
													<?php if(!empty($message['intent'])): ?>
														<span class="label label-info"><?php echo $message['intent']; ?></span>
													<?php else: ?>
														-
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else: ?>
										<tr>
											<td colspan="4" class="text-center text-muted">Không tìm thấy lịch sử nào phù hợp với bộ lọc.</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

