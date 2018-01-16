##项目技术
- PHP7.0~
- 框架: Laravel5.4
- 权限：laravel-permission
- 前端：Bootstrap,select2,layer,my97datepicker,jQuery
- 图表：ECharts

# 系统说明
- 因饭堂没有采用打卡结算和即时现金结算，用餐费用采用月结的方式。单位员工需要向饭堂报餐，告知饭堂早中晚餐开餐的日期，可以临时或长期开餐。当天报餐有时间限制。
- 用餐员工可查看当月或历史的用餐记录，用餐费用。
- 饭堂工作人员可以设置用餐时限，查看用餐人数，报表等。

## 首页
- 早、午、晚菜式
- 早、午、晚就餐人数
- 通知
- 系统登陆

## 用餐人员功能
- home页展示：当月早、午、晚用餐天数，餐费
- 用餐详情
- 开、停餐设置
- 个人信息设置

## 餐厅工作人员功能
- home页展示：天、月用餐情况
- 假期设置
- 早、午、晚菜式设置
- 开、停餐时限设置
- 用餐人员停开餐设置（无时限限制）
- 发布通知（餐厅通知）

## 管理员功能
- home页展示：
- 机构设置
- 人员设置
- 用餐费用设置
- 权限、角色设置

# 功能细节
- 设置登陆入口
- 机构设置
- 每个人员设置收费标准,修改标准下个月生效？记录历史收费标准？
- 角色设置：员工、餐厅工作人员、
- 费用报表，月结晚上自动生成人员月报表
- 按时间段进行开餐或停餐设置
- 用餐天数统计
 统计当月开餐时间范围内工作日天数（workdays),统计工作日假期天数（holidays）;用餐天数（eatDays）= workdays - holidays
- 用餐费用统计：
当月的个人餐费标准*用餐天数

## 数据库表结构
###用户表: users
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- username | varchar(50) NOT NULL  |uni| | 登录名
- name | varchar(100) NOT NULL  |uni| | 中文名
- nickname | varchar(100) NOT NULL  | uni| |论坛呢称
- active | int(1) NOT NULL  | | |状态
- email | varchar(255) not NULL  |uni | |
- tag | varchar(255) NULL  | | |标签
- telephone | varchar(20) NULL  | | |
- mobilephone | int(11) unsigned NULL  | | |
- ip_address| varchar(16) NULL  | | |
- password | varchar(255) not NULL  | | |
- remember_token| | | |
- last_login_at| datetime NULL  | | |最近登录时间|
- timestamps| datetime NULL| | |
- softDeletes| datetime NULL  | | |软删除

### 机构表：depts
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- pid| int(10) unsigned NULL | |depts.pid=depts.id |父ID
- name | varchar(255) NOT NULL  |uni| | 部门名称

### 用户机构关联表：userdepts
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- dept_id | int(10) unsigned NOT NULL |pri | |
- user_id | int(10) unsigned NOT NULL  |pri| |

### 权限表：permits
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- name | varchar(255) NOT NULL  |uni| | 
- permission | varchar(255) NOT NULL  |uni| | 权限

### 角色表：roles
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- name | varchar(255) NOT NULL  |uni| | 
- permits | varchar(255) NOT NULL  || | 权限

### 菜单:menus
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- name | varchar(100) NOT NULL  |uni| | 菜名
- type | int(1) unsigned NOT NULL  | | |1：早餐2：中餐3：晚餐
- active | int(1) unsigned NOT NULL  | | |状态

### 定餐时限：order_times
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- type | int(1) unsigned NOT NULL  | | |1：早餐2：中餐3：晚餐
- book_time | datetime NOT NULL  || | 定餐时限
- cancel_time | datetime NOT NULL  | | |停餐时限

### 餐费标准：prices
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- name | varchar(100) NOT NULL  |uni| | 餐费名称
- breakfast | decimal(10,2) unsigned NOT NULL | | |早餐
- lunch | decimal(10,2) unsigned NOT NULL  || |中餐
- dinner | decimal(10,2) unsigned NOT NULL  || |晚餐
- begin_date| datetime not NULL  | | |生效日期
- status| varchar(1) null | | | 状态

### 人员餐费标准设置：price_users
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- begin_date| datetime NULL  | | |开始期
- valid_date| datetime NULL  | | |有效期
- user_id | int(10) unsigned NOT NULL | |users.id=$this.id |用户
- price_id | int(10) unsigned NOT NULL | |prices.id=$this.id |餐费
- breakfast | decimal(10,2) unsigned NOT NULL | | |早餐
- lunch | decimal(10,2) unsigned NOT NULL  || |中餐
- dinner | decimal(10,2) unsigned NOT NULL  || |晚餐
- softDeletes| datetime NULL  | | |软删除


### 通知：notices
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- title | varchar(100) NOT NULL  || |
- content | text  || |
- valid_date| datetime NULL  | | |有效期
- user_id | int(10) unsigned NOT NULL | |users.id=notices.id |用户
- softDeletes| datetime NULL  | | |软删除

### 假期设置：calendars
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- name | varchar(100) NOT NULL  || |假期名称
- type | int(1) unsigned NOT NUL  || |0：假期 1：工作日
- begin_date| date NOT NULL  | | |开始日期
- end_date| date NOT NULL  | | |结束日期
- user_id | int(10) unsigned NOT NULL | |users.id=notices.id |用户
- softDeletes| datetime NULL  | | |软删除

### 开餐记录表：
### book_breakfasts
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- begin_date| datetime NULL  | | |开始期
- valid_date| datetime NULL  | | |有效期
- user_id | int(10) unsigned NOT NULL | |users.id=bookbreakfasts.id |用户

### book_lunches
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- begin_date| datetime NULL  | | |开始期
- valid_date| datetime NULL  | | |有效期
- user_id | int(10) unsigned NOT NULL | |users.id=booklunchs.id |用户

### book_dinners
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- begin_date| datetime NULL  | | |开始期
- valid_date| datetime NULL  | | |有效期
- user_id | int(10) unsigned NOT NULL | |users.id=bookdinners.id |用户

### report_data
- 字段名 | 类型 | 主键索引 | 关联 | 说明 
- id | int(10) unsigned NOT NULL AUTO_INCREMENT |pri |
- user_id | int(10) unsigned NOT NULL | |users.id=bookdinners.id |用户
- year| int(4) not NULL  | | |年
- month| int(2) not NULL  | | |月
- breakfasts | int unsigned NOT NULL | | |早餐天数
- lunches | int unsigned NOT NULL  || |中餐天数
- dinners | int unsigned NOT NULL  || |晚餐天数
- breakfast_price | decimal(10,2) unsigned NOT NULL | | |早餐价格
- lunch_price | decimal(10,2) unsigned NOT NULL  || |中餐价格
- dinner_price | decimal(10,2) unsigned NOT NULL  || |晚餐价格
- breakfast_amount | decimal(10,2) unsigned NOT NULL | | |早餐总额
- lunch_amount | decimal(10,2) unsigned NOT NULL  || |中餐总额
- dinner_amount | decimal(10,2) unsigned NOT NULL  || |晚餐总额