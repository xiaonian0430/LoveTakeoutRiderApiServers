
-- 站点 --
CREATE TABLE `delivery_site` (
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '区域id',
  `p_site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '上级区域id',
  `site_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '站点类型：1区域，2站点',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '站点名称',
  `school_title` varchar(50) NOT NULL DEFAULT '' COMMENT '学校名称',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `latitude` FLOAT(10,6) NOT NULL DEFAULT '0' COMMENT '纬度',
  `longitude` FLOAT(10,6) NOT NULL DEFAULT '0' COMMENT '经度',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0：关闭，1：开启',
  `sort` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`site_id`) USING BTREE,
  KEY `p_site_id` (`p_site_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点表';

CREATE TABLE `delivery_rider` (
  `rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '所属站点id',
  `account` varchar(20) NOT NULL DEFAULT '' COMMENT '账号',
  `mobile` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手联系电话',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '骑手真实姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别，0：未知，1:男，2：女',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `id_card_positive` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证正面',
  `id_card_back` varchar(255) NOT NULL DEFAULT '' COMMENT '身份证反面',
  `id_card_validity` varchar(150) NOT NULL DEFAULT '' COMMENT '身份证有效期',
  `id_card_issue` varchar(150) NOT NULL DEFAULT '' COMMENT '身份证签发机关',
  `id_card` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手身份证',
  `nation` varchar(50) NOT NULL DEFAULT '' COMMENT '民族',
  `birth` int(11) NOT NULL DEFAULT '0' COMMENT '出生日期',
  `address` varchar(150) NOT NULL DEFAULT '' COMMENT '住址',
  `health_card` varchar(150) NOT NULL DEFAULT '' COMMENT '健康证',
  `student_card` varchar(150) NOT NULL DEFAULT '' COMMENT '学生证',
  `intended_site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '意向站点ID',
  `intended_role_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '意向角色类型，0：无，1：站长，2收餐员，3骑手,4上寝',
  `formerly_delivery` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '曾是配送人员，1是，0否',
  `experience_electric` int(11) NOT NULL DEFAULT '0' COMMENT '电瓶车经验',
  `blood_type` varchar(10) NOT NULL DEFAULT '' COMMENT '血型，AB,O,A,B',
  `medical_history` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有过病史，1是，0否',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额，精确到分',
  `over_state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态:0=待审核；1=审核通过；2=已驳回',
  `over_id` int(11) NOT NULL DEFAULT '0' COMMENT '审核人id',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态:0=禁用；1=启用',
  `is_online` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '骑手状态:1=在线，0=离线',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除:1=删除；0=正常',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`rider_id`) USING BTREE,
  KEY `site_id` (`site_id`) USING BTREE,
  KEY `mobile` (`mobile`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-骑手信息表';


-- 骑手角色 --
CREATE TABLE `delivery_rider_role` (
`role_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '骑手角色ID',
`rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
`role_type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色，0：无，1：站长，2收餐员，3骑手，4上寝',
`create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
`update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
PRIMARY KEY (`role_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-骑手角色';

-- 短信日志 --
CREATE TABLE `delivery_sms_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `mobile` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '手机号',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '操作类型，0：未知，1：发送登录验证码rider_login_code',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发送状态，0：待发送，1：成功，2：失败',
  `sms_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '短信唯一标识',
  `msg` varchar(500) NOT NULL DEFAULT '' COMMENT '信息',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-短信日志';

-- 骑手银行 --
CREATE TABLE `delivery_bank` (
  `bank_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手银行ID',
  `rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '银行持卡人',
  `bank_title` varchar(25) NOT NULL DEFAULT '' COMMENT '银行名称',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `sub_branch` varchar(30) NOT NULL DEFAULT '' COMMENT '开户行',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `lt_bank_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '银行编号Id',
  PRIMARY KEY (`bank_id`) USING BTREE,
  KEY `rider_id` (`rider_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='骑手银行卡配置';

-- 上线，下线记录表 --
CREATE TABLE `delivery_rider_timeline` (
  `timeline_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '时间线ID',
  `rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点id',
  `line_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '日期（天）',
  `online_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上线时间',
  `offline_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下线时间',
  PRIMARY KEY (`timeline_id`) USING BTREE,
  KEY `rider_id` (`rider_id`,`line_day`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-骑手上线-下线记录表';

CREATE TABLE `delivery_site_store` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点id',
  `store_id` varchar(12) NOT NULL DEFAULT '' COMMENT '门店ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点门店表';

-- 配送订单 --
CREATE TABLE `delivery_order` (
  `d_order_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送订单ID',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `delivery_fee` int(11) NOT NULL DEFAULT '0' COMMENT '配送费，单位分',
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '站点id',
  `order_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '下单时间，暂定按支付时间为下单时间',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `print_number` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '打印编号',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0未知',
  `store_mobile` varchar(12) NOT NULL DEFAULT '' COMMENT '联系电话',
  `store_title` varchar(50) NOT NULL DEFAULT '' COMMENT '门店名称',
  `store_province` varchar(15) NOT NULL DEFAULT '' COMMENT '省',
  `store_city` varchar(15) NOT NULL DEFAULT '' COMMENT '市',
  `store_district` varchar(20) NOT NULL DEFAULT '' COMMENT '区',
  `store_address` varchar(150) NOT NULL DEFAULT '' COMMENT '详细地址',
  `store_longitude` float(10,6) NOT NULL DEFAULT '0' COMMENT '经度',
  `store_latitude` float(10,6) NOT NULL DEFAULT '0' COMMENT '纬度',
  `user_contact` varchar(150) NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `user_address` varchar(150) NOT NULL DEFAULT '' COMMENT '详细地址',
  `user_address_details` varchar(150) NOT NULL DEFAULT '' COMMENT '详细地址',
  `user_longitude` float(10,6) NOT NULL DEFAULT '0' COMMENT '经度',
  `user_latitude` float(10,6) NOT NULL DEFAULT '0' COMMENT '纬度',
  `user_sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别，0：未知，1:男，2：女',
  `user_mobile` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户手机号',
  `user_mobile_last` smallint(4) unsigned NOT NULL DEFAULT '0' COMMENT '用户手机号',
  `order_status` tinyint(5) unsigned NOT NULL DEFAULT '0' COMMENT '配送实时状态，0：未知，1：待收餐，2：配送中（收餐完成自动扭转到配送中），3：配送完成，4：已取消',
  `receive_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '收餐时间',
  `finish_time` int(10)  unsigned NOT NULL DEFAULT '0' COMMENT '完成时间，送达时间',
  `cancel_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '取消时间',
  `delivery_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户期望送达时间',
  PRIMARY KEY (`d_order_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-订单池';





-- 盒子 --
CREATE TABLE `delivery_box` (
  `box_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '盒子名称',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  `is_delete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除，0：否，1：是',
  `is_default` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否默认，0:否，1:是',
  PRIMARY KEY `box_id` (`box_id`) USING BTREE,
  KEY `rider_id` (`rider_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='骑手-送餐盒';

-- 盒子里面的订单 --
CREATE TABLE `delivery_box_order` (
  `box_order_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '盒子订单ID',
  `box_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '盒子编号',
  `d_order_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送订单ID',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0:未知，1：已入盒，2：已转单，3：已完成,4：重回订单池',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`box_order_id`) USING BTREE,
  KEY `box_id` (`box_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-盒子里面的订单';

-- 配送订单操作记录 --
CREATE TABLE `delivery_order_operation_log` (
  `log_id` tinyint(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作记录ID',
  `type` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作类型,1：收餐，2：转单，3：完成订单,4：重回订单池',
  `from_box_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单来源-盒子ID',
  `from_rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单来源-骑手编号',
  `to_box_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '订单去向-盒子ID',
  `to_rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单去向-骑手编号',
  `operation_id` bigint(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作人ID',
  `d_order_id` bigint(11) unsigned NOT NULL DEFAULT '0'  COMMENT '配送订单ID',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`log_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送订单操作记录';


-- 余额明细记录 --
CREATE TABLE `delivery_balance_log` (
  `balance_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '结算ID',
  `rider_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `balance_day` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结算日期',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额,单位分，有可能是负数',
  `amount_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '费用类型，0：未知，1：收餐，2：配送，3：提现',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '费用,单位分，有可能是负数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态，0：未知，1：已成功，2：已失败，3：处理中',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '银行持卡人',
  `bank_title` varchar(25) NOT NULL DEFAULT '' COMMENT '银行名称',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `sub_branch` varchar(50) NOT NULL DEFAULT '' COMMENT '开户行',
  `confirm_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '审核状态:0=未知，1=审核通过，2：拒接审核，3待审核',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `confirm_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '审核人编号。0：系统自动执行',
  `site_id` bigint(11) unsigned NOT NULL DEFAULT '0'  COMMENT '站点ID',
  `d_order_id` bigint(11) unsigned NOT NULL DEFAULT '0'  COMMENT '配送订单ID',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`balance_id`) USING BTREE,
  KEY `balance_day_rider` (`balance_day`,`rider_id`) USING BTREE,
  KEY `balance_day_site` (`balance_day`,`site_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-余额明细记录';






