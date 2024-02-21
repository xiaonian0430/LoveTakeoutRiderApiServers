CREATE TABLE `delivery_site` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级区域id',
  `site_type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '站点类型：1区域，2站点',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '站点名称',
  `school_title` varchar(50) NOT NULL DEFAULT '' COMMENT '学校名称',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `latitude` varchar(50) NOT NULL DEFAULT '' COMMENT '纬度',
  `longitude` varchar(50) NOT NULL DEFAULT '' COMMENT '经度',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '地址',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：关闭，1：开启',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点表';

CREATE TABLE `delivery_rider` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rider_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '骑手编号',
  `mobile` varchar(12) NOT NULL DEFAULT '' COMMENT '骑手联系电话',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '骑手真实姓名',
  `sex` tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别：1=男，0=女',
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
  `intended_site_id` int(11) NOT NULL DEFAULT '0' COMMENT '意向站点',
  `intended_role` tinyint(4) NOT NULL DEFAULT '0' COMMENT '意向角色类型，0，位置，1站长，2骑手',
  `formerly_delivery` tinyint(4) NOT NULL DEFAULT '0' COMMENT '曾是配送人员，1是，0否',
  `experience_electric` int(11) NOT NULL DEFAULT '0' COMMENT '电瓶车经验',
  `blood_type` varchar(10) NOT NULL DEFAULT '' COMMENT '血型，AB,O,A,B',
  `medical_history` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否有过病史，1是，0否',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额，精确到分',
  `over_state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态:0=待审核；1=审核通过；2=已驳回',
  `over_id` int(11) NOT NULL DEFAULT '0' COMMENT '审核人id',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态:0=禁用；1=启用',
  `is_online` tinyint(4) NOT NULL DEFAULT '1' COMMENT '骑手状态:1=在线，0=离线',
  `is_delete` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否删除:1=删除；0=正常',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-骑手信息表';


-- 骑手银行 --
CREATE TABLE `delivery_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `bank_owner` varchar(30) NOT NULL DEFAULT '' COMMENT '银行持卡人',
  `bank_title` varchar(25) NOT NULL DEFAULT '' COMMENT '银行名称',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `sub_branch` varchar(30) NOT NULL DEFAULT '' COMMENT '开户行',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `bank_id` int(10) NOT NULL DEFAULT '0' COMMENT '银行编号Id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='骑手银行卡配置';

CREATE TABLE `delivery_rider_timeline` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `day_line` int(11) NOT NULL DEFAULT '0' COMMENT '日期（天）',
  `online_time` int(10) NOT NULL DEFAULT '0' COMMENT '上线时间',
  `offline_time` int(10) NOT NULL DEFAULT '0' COMMENT '下线时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-骑手上线-下线记录表';

CREATE TABLE `delivery_site_store` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点编号',
  `store_id` varchar(12) NOT NULL DEFAULT '' COMMENT '门店ID',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点门店表';

CREATE TABLE `delivery_site_rider` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点编号',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点骑手表';

CREATE TABLE `delivery_site_rider_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点编号',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `role_type` int(11) NOT NULL DEFAULT '0' COMMENT '角色，0：无，1：站长，2收餐员，3骑手',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-站点骑手角色表';


CREATE TABLE `delivery_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `store_id` varchar(12) NOT NULL DEFAULT '' COMMENT '门店编号',
  `delivery_fee` int(11) NOT NULL DEFAULT '0' COMMENT '配送费，单位分',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点编号',
  `order_time` int(10) NOT NULL DEFAULT '0' COMMENT '下单时间',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `print_number` varchar(15) NOT NULL DEFAULT '' COMMENT '打印编号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0未知',
  `user_address_info` json NOT NULL COMMENT '用户地址信息',
  `order_status` int(5) NOT NULL DEFAULT '0' COMMENT '配送实时状态，0：未知，1：待收餐，2：配送中（收餐完成自动扭转到配送中），3：配送完成，4：已取消',
  `receive_time` int(10) NOT NULL DEFAULT '0' COMMENT '收餐时间',
  `finish_time` int(10) NOT NULL DEFAULT '0' COMMENT '完成时间',
  `cancle_time` int(10) NOT NULL DEFAULT '0' COMMENT '取消时间',
  `delivery_time` int(10) NOT NULL DEFAULT '0' COMMENT '用户期望送达时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-订单池';


-- 骑手所属的订单 --
CREATE TABLE `delivery_rider_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点编号',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：未知，1：已收餐，2：已配送',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `obtain_fee` int(11) NOT NULL DEFAULT '0' COMMENT '结算费用，单位分',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='骑手所属的订单表';


-- 盒子 --
CREATE TABLE `delivery_box` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `rider_id` varchar(20) NOT NULL DEFAULT '' COMMENT '骑手编号',
  `title` varchar(100) NOT NULL DEFAULT '' COMMENT '盒子名称',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `is_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除，0：否，1：是',
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认，0:否，1:是',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='骑手-送餐盒';

-- 盒子里面的订单 --
CREATE TABLE `delivery_box_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `box_id` int(11) NOT NULL DEFAULT '0' COMMENT '盒子ID',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：已入盒，1：已转单，2：已完成',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-盒子里面的订单';

-- 盒子里面的订单转交记录 --
CREATE TABLE `delivery_box_trans_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `from_box_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单来源-盒子ID',
  `to_box_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单去向-盒子ID',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-盒子里面的订单转交记录';



-- 余额明细记录 --
CREATE TABLE `delivery_balance_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `balance` int(11) NOT NULL DEFAULT '0' COMMENT '余额,单位分',
  `fee` int(11) NOT NULL DEFAULT '0' COMMENT '费用,单位分',
  `fee_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '费用类型，0未知，1增加，2提现',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0：未知，1：已成功，2：已失败，3：处理中',
  `bank_owner` varchar(30) NOT NULL DEFAULT '' COMMENT '银行持卡人',
  `bank_title` varchar(25) NOT NULL DEFAULT '' COMMENT '银行名称',
  `bank_card` varchar(30) NOT NULL DEFAULT '' COMMENT '银行卡号',
  `sub_branch` varchar(50) NOT NULL DEFAULT '' COMMENT '开户行',
  `confirm_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '审核状态:0=待审核，1=审核通过，2拒接审核',
  `remarks` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `confirm_id` varchar(32) NOT NULL DEFAULT '' COMMENT '审核人编号',
  `order_no` varchar(32) NOT NULL DEFAULT '' COMMENT '订单编号',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='配送-余额明细记录';






