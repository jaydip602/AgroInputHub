-- Agro Input Hub Database Backup
-- Generated: 2026-08-25 07:50:15

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `action_performed` text NOT NULL,
  `table_affected` varchar(50) NOT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`log_id`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `audit_logs` VALUES('1','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-24 19:05:53');
INSERT INTO `audit_logs` VALUES('2','admin_main','Soil analyzed for crop: કપાસ (Cotton)','crop_recommendations','2026-06-24 19:28:02');
INSERT INTO `audit_logs` VALUES('3','admin_main','ઓર્ડર #ORD-02 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-06-24 19:29:46');
INSERT INTO `audit_logs` VALUES('4','admin_main','ઓર્ડર #ORD-03 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-06-24 19:30:04');
INSERT INTO `audit_logs` VALUES('5','admin_main','ખેડૂત ID #F-01 ના ખાતામાં ₹ 2000 રોકડા જમા કર્યા.','ledger','2026-06-24 19:54:05');
INSERT INTO `audit_logs` VALUES('6','admin_main','ખેડૂત ID #F-02 ના ખાતામાં ₹ 121 રોકડા જમા કર્યા.','ledger','2026-06-24 20:00:15');
INSERT INTO `audit_logs` VALUES('7','admin_main','બિલ બન્યું #BILL-02 | રકમ: ₹2250 (UPI)','bills','2026-06-24 20:02:05');
INSERT INTO `audit_logs` VALUES('8','admin_main','નવી પ્રોડક્ટ ફોટા સાથે ઉમેરી: a_one','products','2026-06-24 20:27:50');
INSERT INTO `audit_logs` VALUES('9','admin_main','ઓર્ડર #ORD-04 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-06-24 20:31:30');
INSERT INTO `audit_logs` VALUES('10','admin_main','નવી પ્રોડક્ટ ફોટા સાથે ઉમેરી: a_one','products','2026-06-24 20:33:45');
INSERT INTO `audit_logs` VALUES('11','admin_main','નવી પ્રોડક્ટ ફોટા સાથે ઉમેરી: a_one','products','2026-06-24 20:33:55');
INSERT INTO `audit_logs` VALUES('12','admin_main','નવી પ્રોડક્ટ ઉમેરી: a_one','products','2026-06-24 20:41:18');
INSERT INTO `audit_logs` VALUES('13','admin_main','પ્રોડક્ટ ગોડાઉનમાંથી ડીલીટ કરી: a_one','products','2026-06-24 20:47:38');
INSERT INTO `audit_logs` VALUES('14','admin_main','પ્રોડક્ટ ગોડાઉનમાંથી ડીલીટ કરી: a_one','products','2026-06-24 20:47:43');
INSERT INTO `audit_logs` VALUES('15','admin_main','પ્રોડક્ટ ગોડાઉનમાંથી ડીલીટ કરી: a_one','products','2026-06-24 20:47:46');
INSERT INTO `audit_logs` VALUES('16','admin_main','બિલ બન્યું #BILL-03 | રકમ: ₹5120 (Cash)','bills','2026-06-24 20:54:15');
INSERT INTO `audit_logs` VALUES('17','admin_main','બિલ બન્યું #BILL-04 | રકમ: ₹5120 (Cash)','bills','2026-06-24 20:58:20');
INSERT INTO `audit_logs` VALUES('18','admin_main','Navi Product Umeri: a_one (Pesticides)','products','2026-06-24 21:14:29');
INSERT INTO `audit_logs` VALUES('19','admin_main','નવી આઇટમ ઉમેરી: a_one (IPL)','products','2026-06-24 21:47:34');
INSERT INTO `audit_logs` VALUES('20','admin_main','નવી આઇટમ ઉમેરી: npk (IPL)','products','2026-06-24 21:50:31');
INSERT INTO `audit_logs` VALUES('21','admin_main','પ્રોડક્ટ ગોડાઉનમાંથી ડીલીટ કરી: npk','products','2026-06-24 21:51:12');
INSERT INTO `audit_logs` VALUES('22','admin_main','પ્રોડક્ટ ગોડાઉનમાંથી ડીલીટ કરી: a_one','products','2026-06-24 21:51:15');
INSERT INTO `audit_logs` VALUES('23','admin_main','ઓર્ડર #ORD-07 નું સ્ટેટસ \'Cancelled\' સેટ કર્યું. (જૂનું: Pending)','orders','2026-06-24 21:52:39');
INSERT INTO `audit_logs` VALUES('24','admin_main','ઓર્ડર #ORD-06 નું સ્ટેટસ \'Delivered\' સેટ કર્યું. (જૂનું: Pending)','orders','2026-06-24 21:52:44');
INSERT INTO `audit_logs` VALUES('25','admin_main','ઓર્ડર #ORD-05 નું સ્ટેટસ \'Delivered\' સેટ કર્યું. (જૂનું: Pending)','orders','2026-06-24 21:52:50');
INSERT INTO `audit_logs` VALUES('26','admin_main','બિલ બન્યું #BILL-06 | રકમ: ₹1379 (Credit)','bills','2026-06-24 21:58:00');
INSERT INTO `audit_logs` VALUES('27','admin_main','બિલ બન્યું #BILL-07 | રકમ: ₹1379 (Credit)','bills','2026-06-24 22:12:02');
INSERT INTO `audit_logs` VALUES('28','admin_main','બિલ બન્યું #BILL-08 | રકમ: ₹1379 (Cash)','bills','2026-06-24 22:15:45');
INSERT INTO `audit_logs` VALUES('29','admin_main','બિલ બન્યું #BILL-09 | રકમ: ₹1379 (Cash)','bills','2026-06-24 22:16:40');
INSERT INTO `audit_logs` VALUES('30','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-24 22:30:13');
INSERT INTO `audit_logs` VALUES('31','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-24 22:33:32');
INSERT INTO `audit_logs` VALUES('32','admin_main','ઓર્ડર #ORD-08 નું સ્ટેટસ \'Approved\' કર્યું. (સ્ટોક રોલબેક ઓટો-સિંક)','orders','2026-06-24 22:55:20');
INSERT INTO `audit_logs` VALUES('33','admin_main','નવી આઇટમ ઉમેરી: npk (IPL)','products','2026-06-24 22:56:31');
INSERT INTO `audit_logs` VALUES('34','admin_main','ઓર્ડર #ORD-010 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક રોલબેક ઓટો-સિંક)','orders','2026-06-24 22:57:01');
INSERT INTO `audit_logs` VALUES('35','admin_main','બિલ બન્યું #BILL-010 | રકમ: ₹94000 (Cash)','bills','2026-06-24 22:58:32');
INSERT INTO `audit_logs` VALUES('36','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-26 16:07:23');
INSERT INTO `audit_logs` VALUES('37','admin_main','બિલ બન્યું #BILL-011 | રકમ: ₹1200 (Credit)','bills','2026-06-26 16:15:00');
INSERT INTO `audit_logs` VALUES('38','admin_main','ઓર્ડર #ORD-012 નું સ્ટેટસ \'Approved\' કર્યું. (સ્ટોક રોલબેક ઓટો-સિંક)','orders','2026-06-26 16:16:52');
INSERT INTO `audit_logs` VALUES('39','admin_main','ઓર્ડર #ORD-012 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:23:04');
INSERT INTO `audit_logs` VALUES('40','admin_main','ઓર્ડર #ORD-012 નું સ્ટેટસ \'Approved\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:23:10');
INSERT INTO `audit_logs` VALUES('41','admin_main','ઓર્ડર #ORD-013 નું સ્ટેટસ \'Approved\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:23:59');
INSERT INTO `audit_logs` VALUES('42','admin_main','ઓર્ડર #ORD-013 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:24:09');
INSERT INTO `audit_logs` VALUES('43','admin_main','ઓર્ડર #ORD-011 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:24:46');
INSERT INTO `audit_logs` VALUES('44','admin_main','ઓર્ડર #ORD-09 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:24:52');
INSERT INTO `audit_logs` VALUES('45','admin_main','ઓર્ડર #ORD-01 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 16:24:59');
INSERT INTO `audit_logs` VALUES('46','admin_main','Soil analyzed for crop: કપાસ (Cotton)','crop_recommendations','2026-06-26 16:26:18');
INSERT INTO `audit_logs` VALUES('47','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 16:26:37');
INSERT INTO `audit_logs` VALUES('48','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 16:27:34');
INSERT INTO `audit_logs` VALUES('49','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 16:27:38');
INSERT INTO `audit_logs` VALUES('50','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 19:03:34');
INSERT INTO `audit_logs` VALUES('51','admin_main','Soil analyzed for crop: કપાસ (Cotton)','crop_recommendations','2026-06-26 19:04:08');
INSERT INTO `audit_logs` VALUES('52','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 19:04:36');
INSERT INTO `audit_logs` VALUES('53','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 19:04:51');
INSERT INTO `audit_logs` VALUES('54','admin_main','Soil analyzed for crop: ચણા (Chickpeas)','crop_recommendations','2026-06-26 19:05:07');
INSERT INTO `audit_logs` VALUES('55','admin_main','ઓર્ડર #ORD-014 નું સ્ટેટસ \'Delivered\' કર્યું. (સ્ટોક ઓટો-સિંક ફિક્સ)','orders','2026-06-26 19:55:40');
INSERT INTO `audit_logs` VALUES('56','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-27 08:49:05');
INSERT INTO `audit_logs` VALUES('57','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-27 11:29:10');
INSERT INTO `audit_logs` VALUES('58','jaydip','Farmer logged in successfully','farmers','2026-06-28 08:27:51');
INSERT INTO `audit_logs` VALUES('59','jaydip','Farmer logged in successfully','farmers','2026-06-28 08:30:27');
INSERT INTO `audit_logs` VALUES('60','jaydip','Farmer logged in successfully','farmers','2026-06-28 08:30:50');
INSERT INTO `audit_logs` VALUES('61','jaydip','Farmer logged in successfully','farmers','2026-06-28 08:33:44');
INSERT INTO `audit_logs` VALUES('62','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-28 09:01:38');
INSERT INTO `audit_logs` VALUES('63','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-28 09:03:35');
INSERT INTO `audit_logs` VALUES('64','jaydip','Farmer logged in successfully','farmers','2026-06-28 09:04:09');
INSERT INTO `audit_logs` VALUES('65','admin_main','ખેડૂત ID #F-03 ના ખાતામાં ₹ 12,000.00 રોકડા જમા કર્યા.','ledger','2026-06-28 11:33:26');
INSERT INTO `audit_logs` VALUES('66','admin_main','ખેડૂત ID #F-03 ના ખાતામાં ₹ 12,000.00 રોકડા જમા કર્યા.','ledger','2026-06-28 11:37:58');
INSERT INTO `audit_logs` VALUES('67','admin_main','ખેડૂત ID #F-03 ના ખાતામાં ₹ 12,000.00 રોકડા જમા કર્યા.','ledger','2026-06-28 11:38:29');
INSERT INTO `audit_logs` VALUES('68','admin_main','ખેડૂત ID #F-03 ના ખાતામાં ₹ 100.00 રોકડા જમા કર્યા.','ledger','2026-06-28 11:41:38');
INSERT INTO `audit_logs` VALUES('69','રમેશભાઈ પટેલ','Farmer logged in successfully','farmers','2026-06-28 17:07:30');
INSERT INTO `audit_logs` VALUES('70','admin_main','બિલ બન્યું #BILL-012 | રકમ: ₹1379 (Cash)','bills','2026-07-05 19:11:44');
INSERT INTO `audit_logs` VALUES('71','admin_main','બિલ બન્યું #BILL-013 | રકમ: ₹1258 (UPI)','bills','2026-07-07 11:00:17');
INSERT INTO `audit_logs` VALUES('72','admin_main','બિલ બન્યું #BILL-014 | રકમ: ₹51578 (Cash)','bills','2026-07-07 11:00:49');
INSERT INTO `audit_logs` VALUES('73','admin_main','બિલ બન્યું #BILL-015 | રકમ: ₹3900 (Cash)','bills','2026-07-07 11:02:43');
INSERT INTO `audit_logs` VALUES('74','admin_main','બિલ બન્યું #BILL-016 | રકમ: ₹15096 (Cash)','bills','2026-07-07 11:14:00');
INSERT INTO `audit_logs` VALUES('75','admin_main','બિલ બન્યું #BILL-022 | રકમ: ₹6000 (Credit)','bills','2026-07-13 19:59:41');
INSERT INTO `audit_logs` VALUES('76','admin_main','બિલ બન્યું #BILL-023 | રકમ: ₹6000 (Credit)','bills','2026-07-13 20:06:44');
INSERT INTO `audit_logs` VALUES('77','admin','ઓર્ડર #ORD-34 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-13 20:09:12');
INSERT INTO `audit_logs` VALUES('78','admin','ઓર્ડર #ORD-34 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-13 20:09:53');
INSERT INTO `audit_logs` VALUES('79','admin','ઓર્ડર #ORD-35 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-13 20:09:58');
INSERT INTO `audit_logs` VALUES('80','admin','ઓર્ડર #ORD-35 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-13 20:11:25');
INSERT INTO `audit_logs` VALUES('81','admin','ઓર્ડર #ORD-36 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-13 20:19:13');
INSERT INTO `audit_logs` VALUES('82','admin','ઓર્ડર #ORD-37 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-13 20:37:57');
INSERT INTO `audit_logs` VALUES('83','admin','ઓર્ડર #ORD-38 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-13 20:39:17');
INSERT INTO `audit_logs` VALUES('84','admin_main','ખેડૂત ID #F-04 ના ખાતામાં ₹ 1,000.00 રોકડા જમા કર્યા.','ledger','2026-07-13 20:42:12');
INSERT INTO `audit_logs` VALUES('85','admin','ઓર્ડર #ORD-39 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-14 10:48:36');
INSERT INTO `audit_logs` VALUES('86','admin','ઓર્ડર #ORD-39 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-14 10:49:09');
INSERT INTO `audit_logs` VALUES('87','admin','ઓર્ડર #ORD-40 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-15 08:11:15');
INSERT INTO `audit_logs` VALUES('88','admin','ઓર્ડર #ORD-40 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-15 08:11:34');
INSERT INTO `audit_logs` VALUES('89','admin','ઓર્ડર #ORD-41 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-15 08:38:24');
INSERT INTO `audit_logs` VALUES('90','admin_main','ઓનલાઈન ઓર્ડર #ORD-43 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-035 . મોડ: UPI','bills','2026-07-15 09:20:26');
INSERT INTO `audit_logs` VALUES('91','admin_main','ઓનલાઈન ઓર્ડર #ORD-44 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-037 . મોડ: Cash','bills','2026-07-15 09:24:16');
INSERT INTO `audit_logs` VALUES('92','admin_main','ઓર્ડર #ORD-42 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-15 09:26:58');
INSERT INTO `audit_logs` VALUES('93','admin_main','ઓર્ડર #ORD-45 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-15 09:27:53');
INSERT INTO `audit_logs` VALUES('94','admin_main','ઓનલાઈન ઓર્ડર #ORD-45 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-039 . મોડ: Cash','bills','2026-07-15 09:27:54');
INSERT INTO `audit_logs` VALUES('95','admin_main','ઓર્ડર #ORD-46 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-16 09:22:14');
INSERT INTO `audit_logs` VALUES('96','admin_main','ઓર્ડર #ORD-47 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-16 09:22:21');
INSERT INTO `audit_logs` VALUES('97','admin_main','ઓનલાઈન ઓર્ડર #ORD-47 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-042 . મોડ: Cash','bills','2026-07-16 09:22:23');
INSERT INTO `audit_logs` VALUES('98','admin_main','ઓનલાઈન ઓર્ડર #ORD-46 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-043 . મોડ: Cash','bills','2026-07-16 09:22:48');
INSERT INTO `audit_logs` VALUES('99','admin_main','ઓર્ડર #ORD-48 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-18 19:18:37');
INSERT INTO `audit_logs` VALUES('100','admin_main','ઓનલાઈન ઓર્ડર #ORD-48 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-045 . મોડ: Credit','bills','2026-07-18 19:18:50');
INSERT INTO `audit_logs` VALUES('101','admin_main','ઓનલાઈન ઓર્ડર #ORD-49 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-047 . મોડ: UPI','bills','2026-07-18 19:19:52');
INSERT INTO `audit_logs` VALUES('102','admin_main','ઓનલાઈન ઓર્ડર #ORD-49 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-049 . મોડ: UPI','bills','2026-07-18 19:30:01');
INSERT INTO `audit_logs` VALUES('103','admin_main','ઓર્ડર #ORD-51 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-18 20:37:42');
INSERT INTO `audit_logs` VALUES('104','admin_main','ઓર્ડર #ORD-52 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-18 21:25:31');
INSERT INTO `audit_logs` VALUES('105','admin_main','ઓર્ડર #ORD-52 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-07-18 21:29:53');
INSERT INTO `audit_logs` VALUES('106','admin_main','ઓર્ડર #ORD-53 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-18 21:30:04');
INSERT INTO `audit_logs` VALUES('107','admin_main','ઓનલાઈન ઓર્ડર #ORD-53 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-053 . મોડ: Credit','bills','2026-07-18 21:30:06');
INSERT INTO `audit_logs` VALUES('108','admin_main','ઓર્ડર #ORD-50 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-07-18 21:30:22');
INSERT INTO `audit_logs` VALUES('109','admin_main','Processed cash credit for Farmer ID #F-03 valued at ₹ 10,000.00','ledger','2026-07-19 08:15:39');
INSERT INTO `audit_logs` VALUES('110','admin_main','ઓર્ડર #ORD-54 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-07-24 10:50:24');
INSERT INTO `audit_logs` VALUES('111','admin_main','ઓર્ડર #ORD-56 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-07-24 10:50:25');
INSERT INTO `audit_logs` VALUES('112','admin_main','ઓર્ડર #ORD-57 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-07-24 10:50:27');
INSERT INTO `audit_logs` VALUES('113','admin_main','ઓર્ડર #ORD-58 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-07-24 10:50:29');
INSERT INTO `audit_logs` VALUES('114','admin_main','ઓર્ડર #ORD-58 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-07-24 10:50:33');
INSERT INTO `audit_logs` VALUES('115','admin_main','ઓર્ડર #ORD-57 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-07-24 10:50:37');
INSERT INTO `audit_logs` VALUES('116','admin_main','ઓર્ડર #ORD-56 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-07-24 10:50:49');
INSERT INTO `audit_logs` VALUES('117','admin_main','ઓર્ડર #ORD-55 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-07-24 10:50:54');
INSERT INTO `audit_logs` VALUES('118','admin_main','ઓર્ડર #ORD-59 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-04 21:59:13');
INSERT INTO `audit_logs` VALUES('119','admin_main','ઓનલાઈન ઓર્ડર #ORD-59 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-062 . મોડ: Cash','bills','2026-08-04 22:02:38');
INSERT INTO `audit_logs` VALUES('120','admin_main','ઓર્ડર #ORD-59 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-08-04 22:02:53');
INSERT INTO `audit_logs` VALUES('121','admin_main','ઓર્ડર #ORD-59 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-04 22:05:01');
INSERT INTO `audit_logs` VALUES('122','jaydip','ઓર્ડર #ORD-59 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-08-04 22:07:13');
INSERT INTO `audit_logs` VALUES('123','admin_main','ઓર્ડર #ORD-59 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-04 22:07:53');
INSERT INTO `audit_logs` VALUES('124','admin_main','ઓનલાઈન ઓર્ડર #ORD-60 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-064 . મોડ: UPI','bills','2026-08-04 22:12:26');
INSERT INTO `audit_logs` VALUES('125','admin_main','ઓનલાઈન ઓર્ડર #ORD-61 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-066 . મોડ: Cash','bills','2026-08-04 22:14:57');
INSERT INTO `audit_logs` VALUES('126','admin_main','ઓનલાઈન ઓર્ડર #ORD-62 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-068 . મોડ: Cash','bills','2026-08-04 22:17:54');
INSERT INTO `audit_logs` VALUES('127','admin_main','ઓર્ડર #ORD-62 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-04 22:19:21');
INSERT INTO `audit_logs` VALUES('128','admin_main','ઓનલાઈન ઓર્ડર #ORD-62 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-069 . મોડ: Cash','bills','2026-08-04 22:19:23');
INSERT INTO `audit_logs` VALUES('129','admin_main','ઓનલાઈન ઓર્ડર #ORD-63 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-071 . મોડ: Cash','bills','2026-08-04 22:19:58');
INSERT INTO `audit_logs` VALUES('130','admin_main','ઓર્ડર #ORD-63 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-04 22:20:38');
INSERT INTO `audit_logs` VALUES('131','admin_main','ઓનલાઈન ઓર્ડર #ORD-64 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-073 . મોડ: Credit','bills','2026-08-04 22:21:13');
INSERT INTO `audit_logs` VALUES('132','admin_main','ઓનલાઈન ઓર્ડર #ORD-65 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-075 . મોડ: UPI','bills','2026-08-04 22:28:57');
INSERT INTO `audit_logs` VALUES('133','admin_main','ઓનલાઈન ઓર્ડર #ORD-63 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-076 . મોડ: Cash','bills','2026-08-11 17:37:40');
INSERT INTO `audit_logs` VALUES('134','admin_main','ઓર્ડર #ORD-66 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-11 17:48:13');
INSERT INTO `audit_logs` VALUES('135','admin_main','ઓનલાઈન ઓર્ડર #ORD-66 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-078 . મોડ: Cash','bills','2026-08-11 17:48:17');
INSERT INTO `audit_logs` VALUES('136','admin_main','ઓર્ડર #ORD-66 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-11 17:53:05');
INSERT INTO `audit_logs` VALUES('137','admin_main','ઓર્ડર #ORD-65 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-11 17:53:11');
INSERT INTO `audit_logs` VALUES('138','admin_main','ઓનલાઈન ઓર્ડર #ORD-67 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-080 . મોડ: UPI','bills','2026-08-11 17:54:07');
INSERT INTO `audit_logs` VALUES('139','admin_main','ઓર્ડર #ORD-67 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-11 17:56:27');
INSERT INTO `audit_logs` VALUES('140','admin_main','ઓર્ડર #ORD-67 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-11 17:57:03');
INSERT INTO `audit_logs` VALUES('141','admin_main','ઓનલાઈન ઓર્ડર #ORD-68 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-082 . મોડ: Cash','bills','2026-08-11 17:57:06');
INSERT INTO `audit_logs` VALUES('142','admin_main','ઓનલાઈન ઓર્ડર #ORD-69 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-084 . મોડ: Cash','bills','2026-08-12 15:24:54');
INSERT INTO `audit_logs` VALUES('143','admin_main','ઓર્ડર #ORD-67 નું સ્ટેટસ બદલીને \'Pending\' કર્યું.','orders','2026-08-12 18:20:39');
INSERT INTO `audit_logs` VALUES('144','admin_main','ઓર્ડર #ORD-67 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-12 18:20:44');
INSERT INTO `audit_logs` VALUES('145','admin_main','ઓર્ડર #ORD-67 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-12 18:21:07');
INSERT INTO `audit_logs` VALUES('146','admin_main','ઓનલાઈન ઓર્ડર #ORD-70 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-086 . મોડ: Cash','bills','2026-08-12 18:21:09');
INSERT INTO `audit_logs` VALUES('147','admin_main','ઓનલાઈન ઓર્ડર #ORD-71 માંથી ઓટો-બિલ અને આઇટમ સિંક પૂર્ણ કર્યું #BILL-088 . મોડ: Credit','bills','2026-08-12 18:28:59');
INSERT INTO `audit_logs` VALUES('148','admin_main','ઓર્ડર #ORD-77 નું સ્ટેટસ બદલીને \'Approved\' કર્યું.','orders','2026-08-20 16:40:18');
INSERT INTO `audit_logs` VALUES('149','admin_main','ઓર્ડર #ORD-77 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-08-20 16:42:57');
INSERT INTO `audit_logs` VALUES('150','admin_main','ઓર્ડર #ORD-83 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-20 17:39:22');
INSERT INTO `audit_logs` VALUES('151','admin_main','ઓર્ડર #ORD-82 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-20 17:39:26');
INSERT INTO `audit_logs` VALUES('152','admin_main','ઓર્ડર #ORD-81 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-20 17:39:30');
INSERT INTO `audit_logs` VALUES('153','admin_main','ઓર્ડર #ORD-80 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-20 17:39:34');
INSERT INTO `audit_logs` VALUES('154','admin_main','ઓર્ડર #ORD-79 નું સ્ટેટસ બદલીને \'Cancelled\' કર્યું.','orders','2026-08-20 17:39:40');
INSERT INTO `audit_logs` VALUES('155','admin_main','ઓર્ડર #ORD-78 નું સ્ટેટસ બદલીને \'Delivered\' કર્યું.','orders','2026-08-20 17:39:49');
INSERT INTO `audit_logs` VALUES('156','admin_main','Deleted Kisan account: mayur (ID: 6)','farmers','2026-08-20 19:12:32');
INSERT INTO `audit_logs` VALUES('157','admin_main','Deleted Kisan account: Unknown Farmer (ID: 6)','farmers','2026-08-20 19:31:47');


DROP TABLE IF EXISTS `bill_items`;
CREATE TABLE `bill_items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `bill_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `bill_id` (`bill_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `bill_items_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`bill_id`) ON DELETE CASCADE,
  CONSTRAINT `bill_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bill_items` VALUES('1','2','4','5','450.00');
INSERT INTO `bill_items` VALUES('2','3','5','4','1280.00');
INSERT INTO `bill_items` VALUES('3','4','5','4','1280.00');
INSERT INTO `bill_items` VALUES('5','6','9','1','1379.00');
INSERT INTO `bill_items` VALUES('6','7','9','1','1379.00');
INSERT INTO `bill_items` VALUES('7','8','9','1','1379.00');
INSERT INTO `bill_items` VALUES('8','9','9','1','1379.00');
INSERT INTO `bill_items` VALUES('9','10','12','50','1880.00');
INSERT INTO `bill_items` VALUES('10','11','1','1','1200.00');
INSERT INTO `bill_items` VALUES('12','13','13','1','1258.00');
INSERT INTO `bill_items` VALUES('13','14','13','41','1258.00');
INSERT INTO `bill_items` VALUES('14','15','2','13','300.00');
INSERT INTO `bill_items` VALUES('15','16','14','12','1258.00');
INSERT INTO `bill_items` VALUES('17','17','16','10','1250.00');
INSERT INTO `bill_items` VALUES('18','18','17','5','970.00');
INSERT INTO `bill_items` VALUES('19','19','9','1','1379.00');
INSERT INTO `bill_items` VALUES('20','20','2','60','291.00');
INSERT INTO `bill_items` VALUES('21','21','2','50','300.00');
INSERT INTO `bill_items` VALUES('22','22','1','5','1200.00');
INSERT INTO `bill_items` VALUES('23','23','1','5','1200.00');
INSERT INTO `bill_items` VALUES('24','24','1','5','1200.00');
INSERT INTO `bill_items` VALUES('25','25','17','1','970.00');
INSERT INTO `bill_items` VALUES('26','26','2','1','300.00');
INSERT INTO `bill_items` VALUES('27','27','2','1','300.00');
INSERT INTO `bill_items` VALUES('28','28','17','5','970.00');
INSERT INTO `bill_items` VALUES('29','29','2','1','291.00');
INSERT INTO `bill_items` VALUES('30','30','4','40','450.00');
INSERT INTO `bill_items` VALUES('31','31','16','15','1250.00');
INSERT INTO `bill_items` VALUES('32','32','2','23','291.00');
INSERT INTO `bill_items` VALUES('33','33','17','15','970.00');
INSERT INTO `bill_items` VALUES('34','34','14','38','1258.00');
INSERT INTO `bill_items` VALUES('35','35','5','1','47804.00');
INSERT INTO `bill_items` VALUES('36','36','9','4','1379.00');
INSERT INTO `bill_items` VALUES('37','37','5','1','5516.00');
INSERT INTO `bill_items` VALUES('38','38','3','19','1350.00');
INSERT INTO `bill_items` VALUES('39','39','3','1','25650.00');
INSERT INTO `bill_items` VALUES('40','40','3','19','1350.00');
INSERT INTO `bill_items` VALUES('41','41','18','5','1300.00');
INSERT INTO `bill_items` VALUES('42','42','18','1','6500.00');
INSERT INTO `bill_items` VALUES('43','43','3','1','25650.00');
INSERT INTO `bill_items` VALUES('44','44','18','5','1300.00');
INSERT INTO `bill_items` VALUES('45','45','18','1','6500.00');
INSERT INTO `bill_items` VALUES('46','46','18','1','1300.00');
INSERT INTO `bill_items` VALUES('47','47','18','1','1300.00');
INSERT INTO `bill_items` VALUES('48','48','18','1','1300.00');
INSERT INTO `bill_items` VALUES('49','49','18','1','1300.00');
INSERT INTO `bill_items` VALUES('50','50','18','1','1300.00');
INSERT INTO `bill_items` VALUES('51','51','18','5','1300.00');
INSERT INTO `bill_items` VALUES('52','52','18','1','1300.00');
INSERT INTO `bill_items` VALUES('53','53','18','1','1300.00');
INSERT INTO `bill_items` VALUES('54','54','18','10','1300.00');
INSERT INTO `bill_items` VALUES('55','55','18','10','1300.00');
INSERT INTO `bill_items` VALUES('56','56','18','5','1300.00');
INSERT INTO `bill_items` VALUES('57','57','18','1','1300.00');
INSERT INTO `bill_items` VALUES('58','58','18','1','1300.00');
INSERT INTO `bill_items` VALUES('59','59','18','1','1300.00');
INSERT INTO `bill_items` VALUES('60','60','18','1','1300.00');
INSERT INTO `bill_items` VALUES('61','61','21','1','1200.00');
INSERT INTO `bill_items` VALUES('62','62','21','1','1200.00');
INSERT INTO `bill_items` VALUES('63','63','22','5','1200.00');
INSERT INTO `bill_items` VALUES('64','64','21','1','6000.00');
INSERT INTO `bill_items` VALUES('65','65','22','5','1200.00');
INSERT INTO `bill_items` VALUES('66','66','21','1','6000.00');
INSERT INTO `bill_items` VALUES('67','67','23','5','1850.00');
INSERT INTO `bill_items` VALUES('68','68','23','1','9250.00');
INSERT INTO `bill_items` VALUES('69','69','23','1','9250.00');
INSERT INTO `bill_items` VALUES('70','70','20','5','750.00');
INSERT INTO `bill_items` VALUES('71','71','20','1','3750.00');
INSERT INTO `bill_items` VALUES('72','72','19','1','1800.00');
INSERT INTO `bill_items` VALUES('73','73','19','1','1800.00');
INSERT INTO `bill_items` VALUES('74','74','23','1','1850.00');
INSERT INTO `bill_items` VALUES('75','74','22','1','1200.00');
INSERT INTO `bill_items` VALUES('76','74','20','1','750.00');
INSERT INTO `bill_items` VALUES('77','75','20','1','3800.00');
INSERT INTO `bill_items` VALUES('78','76','20','1','3750.00');
INSERT INTO `bill_items` VALUES('79','77','24','1','1280.00');
INSERT INTO `bill_items` VALUES('80','78','20','1','1280.00');
INSERT INTO `bill_items` VALUES('81','79','23','5','1850.00');
INSERT INTO `bill_items` VALUES('82','80','23','1','9250.00');
INSERT INTO `bill_items` VALUES('83','81','21','5','1200.00');
INSERT INTO `bill_items` VALUES('84','82','21','1','6000.00');
INSERT INTO `bill_items` VALUES('85','83','23','1','1850.00');
INSERT INTO `bill_items` VALUES('86','84','23','1','1850.00');
INSERT INTO `bill_items` VALUES('87','85','24','1','1280.00');
INSERT INTO `bill_items` VALUES('88','86','20','1','1280.00');
INSERT INTO `bill_items` VALUES('89','87','21','10','1200.00');
INSERT INTO `bill_items` VALUES('90','88','21','1','12000.00');
INSERT INTO `bill_items` VALUES('91','89','19','10','1800.00');
INSERT INTO `bill_items` VALUES('92','90','19','1','1800.00');
INSERT INTO `bill_items` VALUES('93','91','19','5','1728.00');
INSERT INTO `bill_items` VALUES('94','92','19','1','1800.00');
INSERT INTO `bill_items` VALUES('95','93','19','1','1728.00');
INSERT INTO `bill_items` VALUES('96','94','19','1','1800.00');
INSERT INTO `bill_items` VALUES('97','95','19','5','1728.00');
INSERT INTO `bill_items` VALUES('98','96','19','1','1800.00');
INSERT INTO `bill_items` VALUES('101','99','24','5','1280.00');
INSERT INTO `bill_items` VALUES('102','100','23','1','1850.00');
INSERT INTO `bill_items` VALUES('103','101','21','1','1200.00');
INSERT INTO `bill_items` VALUES('104','102','21','1','1200.00');
INSERT INTO `bill_items` VALUES('105','103','21','1','1200.00');
INSERT INTO `bill_items` VALUES('106','104','19','1','1728.00');
INSERT INTO `bill_items` VALUES('107','105','23','1','1850.00');
INSERT INTO `bill_items` VALUES('108','106','21','81','1200.00');
INSERT INTO `bill_items` VALUES('109','107','21','81','1200.00');
INSERT INTO `bill_items` VALUES('110','108','19','5','1728.00');
INSERT INTO `bill_items` VALUES('111','108','24','5','1280.00');
INSERT INTO `bill_items` VALUES('112','108','23','5','1850.00');
INSERT INTO `bill_items` VALUES('113','108','18','15','1300.00');
INSERT INTO `bill_items` VALUES('114','109','18','5','1300.00');
INSERT INTO `bill_items` VALUES('115','110','19','5','1728.00');
INSERT INTO `bill_items` VALUES('116','110','24','5','1280.00');
INSERT INTO `bill_items` VALUES('117','111','19','5','1800.00');
INSERT INTO `bill_items` VALUES('118','111','20','5','750.00');
INSERT INTO `bill_items` VALUES('119','112','24','5','1280.00');
INSERT INTO `bill_items` VALUES('120','113','20','5','750.00');
INSERT INTO `bill_items` VALUES('121','114','19','1','1728.00');


DROP TABLE IF EXISTS `bills`;
CREATE TABLE `bills` (
  `bill_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_id` int(11) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `payment_mode` enum('Cash','UPI','Credit') NOT NULL,
  `bill_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`bill_id`),
  UNIQUE KEY `unique_order_id` (`order_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bills` VALUES('1','1','2700.00','0.00','Cash','2026-06-24 19:26:38',NULL);
INSERT INTO `bills` VALUES('2','1','2250.00','0.00','UPI','2026-06-24 20:02:05',NULL);
INSERT INTO `bills` VALUES('3','2','5120.00','0.00','Cash','2026-06-24 20:54:15',NULL);
INSERT INTO `bills` VALUES('4','2','5120.00','0.00','Cash','2026-06-24 20:58:20',NULL);
INSERT INTO `bills` VALUES('6','2','1379.00','0.00','Credit','2026-06-24 21:58:00',NULL);
INSERT INTO `bills` VALUES('7','2','1379.00','0.00','Credit','2026-06-24 22:12:02',NULL);
INSERT INTO `bills` VALUES('8','2','1379.00','0.00','Cash','2026-06-24 22:15:45',NULL);
INSERT INTO `bills` VALUES('9','2','1379.00','0.00','Cash','2026-06-24 22:16:40',NULL);
INSERT INTO `bills` VALUES('10','2','94000.00','0.00','Cash','2026-06-24 22:58:32',NULL);
INSERT INTO `bills` VALUES('11','1','1200.00','0.00','Credit','2026-06-26 16:15:00',NULL);
INSERT INTO `bills` VALUES('13','3','1258.00','0.00','UPI','2026-07-07 11:00:17',NULL);
INSERT INTO `bills` VALUES('14','3','51578.00','0.00','Cash','2026-07-07 11:00:49',NULL);
INSERT INTO `bills` VALUES('15','3','3900.00','0.00','Cash','2026-07-07 11:02:43',NULL);
INSERT INTO `bills` VALUES('16','3','15096.00','0.00','Cash','2026-07-07 11:14:00',NULL);
INSERT INTO `bills` VALUES('17','3','12500.00','0.00','','2026-07-08 19:37:56',NULL);
INSERT INTO `bills` VALUES('18','3','4850.00','0.00','','2026-07-08 19:39:00',NULL);
INSERT INTO `bills` VALUES('19','3','1379.00','0.00','','2026-07-08 19:42:20',NULL);
INSERT INTO `bills` VALUES('20','3','17460.00','0.00','','2026-07-09 10:51:15',NULL);
INSERT INTO `bills` VALUES('21','3','15000.00','0.00','','2026-07-09 10:52:34',NULL);
INSERT INTO `bills` VALUES('22','4','6000.00','0.00','Credit','2026-07-13 19:59:41',NULL);
INSERT INTO `bills` VALUES('23','4','6000.00','0.00','Credit','2026-07-13 20:06:44',NULL);
INSERT INTO `bills` VALUES('24','4','6000.00','0.00','','2026-07-13 20:09:49',NULL);
INSERT INTO `bills` VALUES('25','4','970.00','0.00','','2026-07-13 20:19:02',NULL);
INSERT INTO `bills` VALUES('26','3','300.00','0.00','Cash','2026-07-13 20:31:00',NULL);
INSERT INTO `bills` VALUES('27','3','300.00','0.00','Cash','2026-07-13 20:35:07',NULL);
INSERT INTO `bills` VALUES('28','4','4850.00','0.00','','2026-07-13 20:37:32',NULL);
INSERT INTO `bills` VALUES('29','4','291.00','0.00','','2026-07-13 20:38:49',NULL);
INSERT INTO `bills` VALUES('30','3','18000.00','0.00','','2026-07-14 10:48:19',NULL);
INSERT INTO `bills` VALUES('31','3','18750.00','0.00','','2026-07-15 08:10:59',NULL);
INSERT INTO `bills` VALUES('32','3','6693.00','0.00','','2026-07-15 08:38:01',NULL);
INSERT INTO `bills` VALUES('33','3','14550.00','0.00','','2026-07-15 09:17:47',NULL);
INSERT INTO `bills` VALUES('34','3','47804.00','0.00','UPI','2026-07-15 09:20:19',NULL);
INSERT INTO `bills` VALUES('35','3','47804.00','0.00','UPI','2026-07-15 09:20:26',NULL);
INSERT INTO `bills` VALUES('36','3','5516.00','0.00','Cash','2026-07-15 09:24:07',NULL);
INSERT INTO `bills` VALUES('37','3','5516.00','0.00','Cash','2026-07-15 09:24:16',NULL);
INSERT INTO `bills` VALUES('38','3','25650.00','0.00','Cash','2026-07-15 09:27:34',NULL);
INSERT INTO `bills` VALUES('39','3','25650.00','0.00','Cash','2026-07-15 09:27:54',NULL);
INSERT INTO `bills` VALUES('40','3','25650.00','0.00','Cash','2026-07-16 09:03:02',NULL);
INSERT INTO `bills` VALUES('41','3','6500.00','0.00','Cash','2026-07-16 09:22:01',NULL);
INSERT INTO `bills` VALUES('42','3','6500.00','0.00','Cash','2026-07-16 09:22:23',NULL);
INSERT INTO `bills` VALUES('43','3','25650.00','0.00','Cash','2026-07-16 09:22:48',NULL);
INSERT INTO `bills` VALUES('44','3','6500.00','0.00','Credit','2026-07-18 19:17:49',NULL);
INSERT INTO `bills` VALUES('45','3','6500.00','0.00','Credit','2026-07-18 19:18:50',NULL);
INSERT INTO `bills` VALUES('46','3','1300.00','0.00','UPI','2026-07-18 19:19:38',NULL);
INSERT INTO `bills` VALUES('47','3','1300.00','0.00','UPI','2026-07-18 19:19:52',NULL);
INSERT INTO `bills` VALUES('48','3','1300.00','0.00','UPI','2026-07-18 19:29:57',NULL);
INSERT INTO `bills` VALUES('49','3','1300.00','0.00','UPI','2026-07-18 19:30:01',NULL);
INSERT INTO `bills` VALUES('50','3','1300.00','0.00','Cash','2026-07-18 19:33:49',NULL);
INSERT INTO `bills` VALUES('51','3','6500.00','0.00','Credit','2026-07-18 21:25:22',NULL);
INSERT INTO `bills` VALUES('52','3','1300.00','0.00','Credit','2026-07-18 21:29:43',NULL);
INSERT INTO `bills` VALUES('53','3','1300.00','0.00','Credit','2026-07-18 21:30:06',NULL);
INSERT INTO `bills` VALUES('54','4','13000.00','0.00','Credit','2026-07-18 23:03:09',NULL);
INSERT INTO `bills` VALUES('55','4','13000.00','0.00','Credit','2026-07-19 16:39:52',NULL);
INSERT INTO `bills` VALUES('56','3','6500.00','0.00','UPI','2026-07-21 10:58:24',NULL);
INSERT INTO `bills` VALUES('57','3','1300.00','0.00','UPI','2026-07-24 10:25:39',NULL);
INSERT INTO `bills` VALUES('58','3','1300.00','0.00','UPI','2026-07-24 10:46:08',NULL);
INSERT INTO `bills` VALUES('59','3','1300.00','0.00','UPI','2026-07-24 10:47:20',NULL);
INSERT INTO `bills` VALUES('60','3','1300.00','0.00','UPI','2026-07-24 10:49:24',NULL);
INSERT INTO `bills` VALUES('61','3','1200.00','0.00','Cash','2026-08-04 10:39:16',NULL);
INSERT INTO `bills` VALUES('62','3','1200.00','0.00','Cash','2026-08-04 22:02:38',NULL);
INSERT INTO `bills` VALUES('63','3','6000.00','0.00','UPI','2026-08-04 22:11:59',NULL);
INSERT INTO `bills` VALUES('64','3','6000.00','0.00','UPI','2026-08-04 22:12:26',NULL);
INSERT INTO `bills` VALUES('65','3','6000.00','0.00','Cash','2026-08-04 22:14:46',NULL);
INSERT INTO `bills` VALUES('66','3','6000.00','0.00','Cash','2026-08-04 22:14:57',NULL);
INSERT INTO `bills` VALUES('67','3','9250.00','0.00','Cash','2026-08-04 22:17:44',NULL);
INSERT INTO `bills` VALUES('68','3','9250.00','0.00','Cash','2026-08-04 22:17:54',NULL);
INSERT INTO `bills` VALUES('69','3','9250.00','0.00','Cash','2026-08-04 22:19:23',NULL);
INSERT INTO `bills` VALUES('70','3','3750.00','0.00','Cash','2026-08-04 22:19:42',NULL);
INSERT INTO `bills` VALUES('71','3','3750.00','0.00','Cash','2026-08-04 22:19:58',NULL);
INSERT INTO `bills` VALUES('72','3','1800.00','0.00','Credit','2026-08-04 22:21:04',NULL);
INSERT INTO `bills` VALUES('73','3','1800.00','0.00','Credit','2026-08-04 22:21:13',NULL);
INSERT INTO `bills` VALUES('74','3','3800.00','0.00','UPI','2026-08-04 22:28:49',NULL);
INSERT INTO `bills` VALUES('75','3','3800.00','0.00','UPI','2026-08-04 22:28:57','65');
INSERT INTO `bills` VALUES('76','3','3750.00','0.00','Cash','2026-08-11 17:37:40','63');
INSERT INTO `bills` VALUES('77','3','1280.00','0.00','Cash','2026-08-11 17:38:44',NULL);
INSERT INTO `bills` VALUES('78','3','1280.00','0.00','Cash','2026-08-11 17:48:17','66');
INSERT INTO `bills` VALUES('79','3','9250.00','0.00','UPI','2026-08-11 17:53:58',NULL);
INSERT INTO `bills` VALUES('80','3','9250.00','0.00','UPI','2026-08-11 17:54:07','67');
INSERT INTO `bills` VALUES('81','3','6000.00','0.00','Cash','2026-08-11 17:56:56',NULL);
INSERT INTO `bills` VALUES('82','3','6000.00','0.00','Cash','2026-08-11 17:57:06','68');
INSERT INTO `bills` VALUES('83','3','1850.00','0.00','Cash','2026-08-12 15:24:46',NULL);
INSERT INTO `bills` VALUES('84','3','1850.00','0.00','Cash','2026-08-12 15:24:54','69');
INSERT INTO `bills` VALUES('85','3','1280.00','0.00','Cash','2026-08-12 18:21:02',NULL);
INSERT INTO `bills` VALUES('86','3','1280.00','0.00','Cash','2026-08-12 18:21:09','70');
INSERT INTO `bills` VALUES('87','3','12000.00','0.00','Credit','2026-08-12 18:28:48',NULL);
INSERT INTO `bills` VALUES('88','3','12000.00','0.00','Credit','2026-08-12 18:28:59','71');
INSERT INTO `bills` VALUES('89','3','18000.00','0.00','Credit','2026-08-12 18:33:08',NULL);
INSERT INTO `bills` VALUES('90','3','18000.00','0.00','Credit','2026-08-12 18:33:21','72');
INSERT INTO `bills` VALUES('91','3','8640.00','0.00','UPI','2026-08-12 18:37:29',NULL);
INSERT INTO `bills` VALUES('92','3','8640.00','0.00','UPI','2026-08-12 18:37:41','73');
INSERT INTO `bills` VALUES('93','3','1728.00','0.00','Cash','2026-08-12 18:42:28',NULL);
INSERT INTO `bills` VALUES('94','3','1728.00','0.00','Cash','2026-08-12 18:42:38','74');
INSERT INTO `bills` VALUES('95','3','8640.00','0.00','UPI','2026-08-12 21:02:30',NULL);
INSERT INTO `bills` VALUES('96','3','8640.00','0.00','UPI','2026-08-12 21:02:42','75');
INSERT INTO `bills` VALUES('99','3','6400.00','0.00','UPI','2026-08-20 16:40:07',NULL);
INSERT INTO `bills` VALUES('100','3','1850.00','0.00','UPI','2026-08-20 17:09:14',NULL);
INSERT INTO `bills` VALUES('101','3','1200.00','0.00','UPI','2026-08-20 17:32:42',NULL);
INSERT INTO `bills` VALUES('102','3','1200.00','0.00','UPI','2026-08-20 17:33:02',NULL);
INSERT INTO `bills` VALUES('103','3','1200.00','0.00','Cash','2026-08-20 17:35:42',NULL);
INSERT INTO `bills` VALUES('104','3','1728.00','0.00','UPI','2026-08-20 17:36:07',NULL);
INSERT INTO `bills` VALUES('105','3','1850.00','0.00','Cash','2026-08-20 17:39:09',NULL);
INSERT INTO `bills` VALUES('106','3','97200.00','0.00','Cash','2026-08-20 18:18:01',NULL);
INSERT INTO `bills` VALUES('107','3','97200.00','0.00','UPI','2026-08-20 18:18:25','84');
INSERT INTO `bills` VALUES('108','3','43790.00','0.00','Cash','2026-08-20 18:59:45',NULL);
INSERT INTO `bills` VALUES('109','3','43790.00','0.00','Cash','2026-08-20 18:59:55','85');
INSERT INTO `bills` VALUES('110','3','15040.00','0.00','Cash','2026-08-20 19:05:26',NULL);
INSERT INTO `bills` VALUES('111','3','15040.00','0.00','Cash','2026-08-20 19:05:35','86');
INSERT INTO `bills` VALUES('112','3','6400.00','0.00','Cash','2026-08-21 10:12:44',NULL);
INSERT INTO `bills` VALUES('113','3','6400.00','0.00','UPI','2026-08-21 10:13:17','87');
INSERT INTO `bills` VALUES('114','3','1728.00','0.00','Cash','2026-08-22 10:44:49',NULL);


DROP TABLE IF EXISTS `crop_recommendations`;
CREATE TABLE `crop_recommendations` (
  `rec_id` int(11) NOT NULL AUTO_INCREMENT,
  `crop_name` varchar(50) NOT NULL,
  `min_ph` decimal(4,2) DEFAULT NULL,
  `max_ph` decimal(4,2) DEFAULT NULL,
  `min_nitrogen` int(11) DEFAULT NULL,
  `recommended_fertilizer` varchar(255) NOT NULL,
  `dosage_info` text NOT NULL,
  PRIMARY KEY (`rec_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `crop_recommendations` VALUES('1','ચણા (Chickpeas)','6.00','7.50','250','IFFCO DAP ખાતર','એકર દીઠ ૫૦ કિલો વાવણી સમયે આપવું.');
INSERT INTO `crop_recommendations` VALUES('2','કપાસ (Cotton)','6.50','8.00','280','NPK (12:32:16) + Urea','વાવણી બાદ ૨૫ દિવસે યુરિયાનો પ્રથમ ડોઝ આપવો.');


DROP TABLE IF EXISTS `farmers`;
CREATE TABLE `farmers` (
  `farmer_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_name` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `village` varchar(50) NOT NULL,
  `land_vigha` decimal(5,2) NOT NULL,
  `total_baki` decimal(10,2) NOT NULL DEFAULT 0.00,
  `upi_id` varchar(100) DEFAULT NULL,
  `bank_account` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `house_no` varchar(255) DEFAULT NULL,
  `road_name` varchar(255) DEFAULT NULL,
  `address_type` varchar(20) DEFAULT 'Home',
  `email` varchar(100) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`farmer_id`),
  UNIQUE KEY `phone_number` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `farmers` VALUES('1','રમેશભાઈ પટેલ','9876543210','123456','ગોંડલ','5.50','0.00',NULL,NULL,NULL,NULL,NULL,NULL,'Home',NULL,NULL);
INSERT INTO `farmers` VALUES('2','મનસુખભાઈ વાઘાણી','9998887770','654321','જેતપુર','12.00','0.00',NULL,NULL,NULL,NULL,NULL,NULL,'Home',NULL,NULL);
INSERT INTO `farmers` VALUES('3','jaydip','7862914314','Parmar@123','kondh','120.00','53574.00','','','Gujarat','halvad','p-49','sara rode','Home','jaydipparmar995@gmail.com','farmer_3_1787290004.jpg');
INSERT INTO `farmers` VALUES('4','jadeja purthviraj','6353886138','Purthviraj@123','dhanala','25.00','37000.00',NULL,NULL,NULL,NULL,NULL,NULL,'Home',NULL,NULL);
INSERT INTO `farmers` VALUES('5','Jaydip Parmar','8780563616','$2y$10$5qT4lGQpVJ9zWCBMTeJjGekud0C2tgy.5lb/8JXjfq6/XFoD36vZa','kondh','13.00','0.00',NULL,NULL,NULL,NULL,NULL,NULL,'Home',NULL,NULL);
INSERT INTO `farmers` VALUES('7','KAILA AMIT CHANDULAL','9624262834','Kaila@123','vejalpar','100.00','0.00',NULL,NULL,'Gujarat','halvad','vishvas society halvad','sara road','Home','kailaamit1990@gmail.com',NULL);


DROP TABLE IF EXISTS `ledger`;
CREATE TABLE `ledger` (
  `ledger_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `due_amount` decimal(10,2) DEFAULT 0.00,
  `paid_amount` decimal(10,2) DEFAULT 0.00,
  `entry_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ledger_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `ledger_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `ledger` VALUES('1','1','0','5000.00','0.00','2026-06-24 19:05:44');
INSERT INTO `ledger` VALUES('2','1','0','0.00','500.00','2026-06-24 19:05:44');
INSERT INTO `ledger` VALUES('3','2','0','12500.00','0.00','2026-06-24 19:05:44');
INSERT INTO `ledger` VALUES('4','1','0','0.00','2000.00','2026-06-24 19:54:05');
INSERT INTO `ledger` VALUES('5','2','0','0.00','121.00','2026-06-24 20:00:15');
INSERT INTO `ledger` VALUES('6','2','6','1379.00','0.00','2026-06-24 21:58:00');
INSERT INTO `ledger` VALUES('7','2','7','1379.00','0.00','2026-06-24 22:12:02');
INSERT INTO `ledger` VALUES('8','1','11','1200.00','0.00','2026-06-26 16:15:00');
INSERT INTO `ledger` VALUES('14','4','22','6000.00','0.00','2026-07-13 19:59:41');
INSERT INTO `ledger` VALUES('15','4','23','6000.00','0.00','2026-07-13 20:06:44');
INSERT INTO `ledger` VALUES('16','4','0','0.00','1000.00','2026-07-13 20:42:12');
INSERT INTO `ledger` VALUES('19','4','54','13000.00','0.00','2026-07-18 23:03:09');
INSERT INTO `ledger` VALUES('20','3','0','0.00','10000.00','2026-07-19 04:45:39');
INSERT INTO `ledger` VALUES('21','4','55','13000.00','0.00','2026-07-19 16:39:52');
INSERT INTO `ledger` VALUES('22','3','0','1800.00','0.00','2026-08-04 22:21:04');
INSERT INTO `ledger` VALUES('23','3','73','1800.00','0.00','2026-08-04 22:21:13');
INSERT INTO `ledger` VALUES('24','3','0','0.00','10000.00','2026-08-12 14:56:24');
INSERT INTO `ledger` VALUES('25','3','0','12000.00','0.00','2026-08-12 18:28:48');
INSERT INTO `ledger` VALUES('26','3','88','12000.00','0.00','2026-08-12 18:28:59');
INSERT INTO `ledger` VALUES('27','3','0','18000.00','0.00','2026-08-12 18:33:08');
INSERT INTO `ledger` VALUES('28','3','90','18000.00','0.00','2026-08-12 18:33:21');


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `order_status` enum('Pending','Approved','Delivered','Cancelled') DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_mode` varchar(50) NOT NULL DEFAULT 'COD',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`order_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=89 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` VALUES('1','1','IFFCO યુરિયા ખાતર (૨ બેગ)','Delivered','2026-06-24 19:05:44','COD','0.00');
INSERT INTO `orders` VALUES('2','2','ચણા બિયારણ ગૂજરાત-૫ (૫૦ કિલો)','Delivered','2026-06-24 19:05:44','COD','0.00');
INSERT INTO `orders` VALUES('3','1','ચણા બિયારણ (ગૂજરાત-૫) (જથ્થો: 1 Bag/Pkg)','Approved','2026-06-24 19:16:41','COD','0.00');
INSERT INTO `orders` VALUES('4','1','a_one (જથ્થો: 1 Bag)','Delivered','2026-06-24 20:31:11','COD','0.00');
INSERT INTO `orders` VALUES('5','1','a_one (જથ્થો: 1 Bag)','Delivered','2026-06-24 20:45:38','COD','0.00');
INSERT INTO `orders` VALUES('6','1','a_one (જથ્થો: 1 Bag)','Delivered','2026-06-24 21:14:46','COD','0.00');
INSERT INTO `orders` VALUES('7','1','a_one (જથ્થો: 1 Bag)','Cancelled','2026-06-24 21:16:27','COD','0.00');
INSERT INTO `orders` VALUES('8','1','ચણા બિયારણ (ગૂજરાત-૫) (Local) [જથ્થો: 20 Bag/Pcs]','Approved','2026-06-24 22:54:17','COD','0.00');
INSERT INTO `orders` VALUES('9','1','ચણા બિયારણ (ગૂજરાત-૫) (Local) [જથ્થો: 20 Bag/Pcs]','Delivered','2026-06-24 22:56:39','COD','0.00');
INSERT INTO `orders` VALUES('10','1','npk (IPL) [જથ્થો: 50 Bag/Pcs]','Delivered','2026-06-24 22:56:46','COD','0.00');
INSERT INTO `orders` VALUES('11','1','DAP ખાતર (IFFCO) (Local) [જથ્થો: 1 Bag/Pcs]','Delivered','2026-06-26 16:16:08','COD','0.00');
INSERT INTO `orders` VALUES('12','1','DAP ખાતર (IFFCO) (Local) [જથ્થો: 100 Bag/Pcs]','Approved','2026-06-26 16:16:24','COD','0.00');
INSERT INTO `orders` VALUES('13','1','IFFCO યુરિયા ખાતર (Local) [જથ્થો: 50 Bag/Pcs]','Delivered','2026-06-26 16:23:50','COD','0.00');
INSERT INTO `orders` VALUES('14','1','a_one (Local) [જથ્થો: 5 Bag/Pcs], મોનોક્રોટોફોસ જંતુનાશક દવા (Local) [જથ્થો: 5 Bag/Pcs], IFFCO યુરિયા ખાતર (Local) [જથ્થો: 5 Bag/Pcs]','Delivered','2026-06-26 19:55:28','COD','0.00');
INSERT INTO `orders` VALUES('15','3','a_one [જથ્થો: 1]','Delivered','2026-06-28 10:16:11','Baki (Credit)','1258.00');
INSERT INTO `orders` VALUES('16','3','મોનોક્રોટોફોસ જંતુનાશક દવા (Local) [જથ્થો: 1 Bag/Pcs]','Delivered','2026-06-28 10:16:51','COD','0.00');
INSERT INTO `orders` VALUES('17','3','a_one () [જથ્થો: 1 Bag/Pcs], a_one (IPL) [જથ્થો: 1 Bag/Pcs]','Delivered','2026-06-28 10:22:39','COD','0.00');
INSERT INTO `orders` VALUES('18','3','મોનોક્રોટોફોસ જંતુનાશક દવા () [જથ્થો: 1 Bag/Pcs]','Delivered','2026-06-28 10:25:24','COD','0.00');
INSERT INTO `orders` VALUES('19','3','a_one [જથ્થો: 1]','Delivered','2026-06-28 10:27:28','COD','1258.00');
INSERT INTO `orders` VALUES('20','3','a_one [જથ્થો: 1]','Delivered','2026-06-28 10:44:56','Baki (Credit)','1258.00');
INSERT INTO `orders` VALUES('21','3','ચણા બિયારણ (ગૂજરાત-૫) [જથ્થો: 10]','Delivered','2026-06-28 11:08:29','Baki (Credit)','12000.00');
INSERT INTO `orders` VALUES('22','3','chana [જથ્થો: 1]','Delivered','2026-07-05 19:01:11','Baki (Credit)','1250.00');
INSERT INTO `orders` VALUES('23','3','chana [જથ્થો: 9]','Delivered','2026-07-05 19:20:26','Baki (Credit)','11250.00');
INSERT INTO `orders` VALUES('26','3','16 (1 બોરી), 17 (1 બોરી)','Delivered','2026-07-05 17:26:51','COD','0.00');
INSERT INTO `orders` VALUES('27','3','13 (1 બોરી)','Delivered','2026-07-05 17:28:59','Cash on Delivery (COD)','1107.04');
INSERT INTO `orders` VALUES('28','3','13 (1 બોરી), 17 (1 બોરી), 2 (1 બોરી)','Delivered','2026-07-05 17:29:45','Online Payment (UPI)','2368.04');
INSERT INTO `orders` VALUES('29','3','2 (1 બોરી), 13 (14 બોરી)','Delivered','2026-07-05 17:31:39','Cash on Delivery (COD)','15789.56');
INSERT INTO `orders` VALUES('32','3','','Delivered','2026-07-08 19:37:56','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('33','3','','Delivered','2026-07-08 19:39:00','UPI Online','0.00');
INSERT INTO `orders` VALUES('34','3','','Delivered','2026-07-09 10:51:15','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('35','4','','Approved','2026-07-13 20:09:49','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('36','4','','Approved','2026-07-13 20:19:02','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('37','4','','Approved','2026-07-13 20:37:32','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('38','4','','Approved','2026-07-13 20:38:49','COD','0.00');
INSERT INTO `orders` VALUES('39','3','','Delivered','2026-07-14 10:48:19','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('40','3','','Delivered','2026-07-15 08:10:59','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('41','3','','Delivered','2026-07-15 08:38:01','Baki (Credit)','0.00');
INSERT INTO `orders` VALUES('42','3','','Approved','2026-07-15 09:17:47','UPI Online','0.00');
INSERT INTO `orders` VALUES('43','3','a_one (38 Pcs)','Delivered','2026-07-15 09:20:19','UPI Online','47804.00');
INSERT INTO `orders` VALUES('44','3','a_one (4 Pcs)','Delivered','2026-07-15 09:24:07','COD','5516.00');
INSERT INTO `orders` VALUES('45','3','DAP ખાતર (IFFCO) (19 Pcs)','Delivered','2026-07-15 09:27:34','COD','25650.00');
INSERT INTO `orders` VALUES('46','3','DAP ખાતર (IFFCO) (19 Pcs)','Delivered','2026-07-16 09:03:02','COD','25650.00');
INSERT INTO `orders` VALUES('47','3','ACTION-500 (5 Pcs)','Delivered','2026-07-16 09:22:01','COD','6500.00');
INSERT INTO `orders` VALUES('48','3','ACTION-500 (5 Pcs)','Delivered','2026-07-18 19:17:49','Baki (Credit)','6500.00');
INSERT INTO `orders` VALUES('49','3','ACTION-500 (1 Pcs)','Delivered','2026-07-18 19:19:38','UPI Online','1300.00');
INSERT INTO `orders` VALUES('50','3','ACTION-500 (1 Pcs)','Approved','2026-07-18 19:29:57','UPI Online','1300.00');
INSERT INTO `orders` VALUES('51','3','ACTION-500 (1 Pcs)','Approved','2026-07-18 19:33:49','COD','1300.00');
INSERT INTO `orders` VALUES('52','3','ACTION-500 (5 Pcs)','Delivered','2026-07-18 21:25:22','Baki (Credit)','6500.00');
INSERT INTO `orders` VALUES('53','3','ACTION-500 (1 Pcs)','Delivered','2026-07-18 21:29:43','Baki (Credit)','1300.00');
INSERT INTO `orders` VALUES('54','3','ACTION-500 (5 Pcs)','Cancelled','2026-07-21 10:58:24','UPI Online','6500.00');
INSERT INTO `orders` VALUES('55','3','ACTION-500 (1 Pcs)','Cancelled','2026-07-24 10:25:39','UPI Online','1300.00');
INSERT INTO `orders` VALUES('56','3','ACTION-500 (1 Pcs)','Cancelled','2026-07-24 10:46:08','UPI Online','1300.00');
INSERT INTO `orders` VALUES('57','3','ACTION-500 (1 Pcs)','Cancelled','2026-07-24 10:47:20','UPI Online','1300.00');
INSERT INTO `orders` VALUES('58','3','ACTION-500 (1 Pcs)','Cancelled','2026-07-24 10:49:24','UPI Online','1300.00');
INSERT INTO `orders` VALUES('59','3','ULALA (1 Pcs)','Cancelled','2026-08-04 10:39:16','Cash','1200.00');
INSERT INTO `orders` VALUES('60','3','ULALA (5 Pcs)','Delivered','2026-08-04 22:11:59','UPI Online','6000.00');
INSERT INTO `orders` VALUES('61','3','ULALA (5 Pcs)','Delivered','2026-08-04 22:14:46','Cash','6000.00');
INSERT INTO `orders` VALUES('62','3','Chlirax-20 (5 Pcs)','Delivered','2026-08-04 22:17:44','Cash','9250.00');
INSERT INTO `orders` VALUES('63','3','NPK (5 Pcs)','Delivered','2026-08-04 22:19:42','Cash','3750.00');
INSERT INTO `orders` VALUES('64','3','DAP (1 Pcs)','Delivered','2026-08-04 22:21:04','Baki (Credit)','1800.00');
INSERT INTO `orders` VALUES('65','3','Chlirax-20 (1 Pcs), ULALA (1 Pcs), NPK (1 Pcs)','Pending','2026-08-04 22:28:49','UPI Online','3800.00');
INSERT INTO `orders` VALUES('66','3','NPK (1 Pcs)','Pending','2026-08-11 17:38:44','Cash','1280.00');
INSERT INTO `orders` VALUES('67','3','Chlirax-20 (5 Pcs)','Approved','2026-08-11 17:53:58','UPI Online','9250.00');
INSERT INTO `orders` VALUES('68','3','ULALA (5 Pcs)','Delivered','2026-08-11 17:56:56','Cash','6000.00');
INSERT INTO `orders` VALUES('69','3','Chlirax-20 (1 Pcs)','Delivered','2026-08-12 15:24:46','Cash','1850.00');
INSERT INTO `orders` VALUES('70','3','NPK (1 Pcs)','Delivered','2026-08-12 18:21:02','Cash','1280.00');
INSERT INTO `orders` VALUES('71','3','ULALA (10 Pcs)','Delivered','2026-08-12 18:28:48','Baki (Credit)','12000.00');
INSERT INTO `orders` VALUES('72','3','DAP (10 Pcs)','Delivered','2026-08-12 18:33:08','Baki (Credit)','18000.00');
INSERT INTO `orders` VALUES('73','3','DAP (5 Pcs)','Delivered','2026-08-12 18:37:29','UPI Online','8640.00');
INSERT INTO `orders` VALUES('74','3','DAP (1 Pcs)','Delivered','2026-08-12 18:42:28','Cash','1728.00');
INSERT INTO `orders` VALUES('75','3','DAP (5 Pcs)','Delivered','2026-08-12 21:02:30','UPI Online','8640.00');
INSERT INTO `orders` VALUES('77','3','NPK (5 Pcs)','Delivered','2026-08-20 16:40:07','UPI Online','6400.00');
INSERT INTO `orders` VALUES('78','3','Chlirax-20 (1 Pcs)','Delivered','2026-08-20 17:09:14','Card Payment','1850.00');
INSERT INTO `orders` VALUES('79','3','ULALA (1 Pcs)','Cancelled','2026-08-20 17:32:42','UPI Online','1200.00');
INSERT INTO `orders` VALUES('80','3','ULALA (1 Pcs)','Cancelled','2026-08-20 17:33:02','UPI Online','1200.00');
INSERT INTO `orders` VALUES('81','3','ULALA (1 Pcs)','Cancelled','2026-08-20 17:35:42','Cash','1200.00');
INSERT INTO `orders` VALUES('82','3','DAP (1 Pcs)','Cancelled','2026-08-20 17:36:07','UPI Online','1728.00');
INSERT INTO `orders` VALUES('83','3','Chlirax-20 (1 Pcs)','Cancelled','2026-08-20 17:39:09','UPI Online','1850.00');
INSERT INTO `orders` VALUES('84','3','ULALA (81 Pcs)','Delivered','2026-08-20 18:18:01','UPI Online','97200.00');
INSERT INTO `orders` VALUES('85','3','DAP (5 Pcs), NPK (5 Pcs), Chlirax-20 (5 Pcs), ACTION-500 (15 Pcs)','Delivered','2026-08-20 18:59:45','Cash','43790.00');
INSERT INTO `orders` VALUES('86','3','DAP (5 Pcs), NPK (5 Pcs)','Delivered','2026-08-20 19:05:26','Cash','15040.00');
INSERT INTO `orders` VALUES('87','3','NPK (5 Pcs)','Delivered','2026-08-21 10:12:44','UPI Online','6400.00');
INSERT INTO `orders` VALUES('88','3','DAP (1 Pcs)','Pending','2026-08-22 10:44:49','Cash','1728.00');


DROP TABLE IF EXISTS `product_returns`;
CREATE TABLE `product_returns` (
  `return_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `reason` text NOT NULL,
  `return_status` varchar(50) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`return_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product_returns` VALUES('1','65','3','demeg','Rejected','2026-08-11 17:43:09');
INSERT INTO `product_returns` VALUES('2','78','3','expriy date for this product','Approved','2026-08-20 17:41:48');


DROP TABLE IF EXISTS `product_reviews`;
CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `farmer_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`review_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `product_reviews` VALUES('1','23','3','4','good','2026-08-08 11:11:15');


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT 'Local',
  `technical_name` varchar(150) DEFAULT NULL,
  `category` enum('Seeds','Fertilizers','Pesticides','Tools') NOT NULL,
  `sub_category` varchar(100) DEFAULT NULL,
  `stock_qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount_pct` int(11) NOT NULL DEFAULT 0,
  `product_image` varchar(255) DEFAULT 'default.png',
  `target_crop` varchar(100) DEFAULT NULL,
  `target_pest` varchar(150) DEFAULT NULL,
  `dosage_per_ha` varchar(50) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Active',
  `package_size` varchar(100) DEFAULT 'Standard Pack',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `products` VALUES('1','ચણા બિયારણ (ગૂજરાત-૫)','Local',NULL,'Seeds',NULL,'4','1200.00','0','chana_seeds.png',NULL,NULL,NULL,'2027-05-01','Deleted','Standard Pack');
INSERT INTO `products` VALUES('2','IFFCO યુરિયા ખાતર','Local',NULL,'Fertilizers',NULL,'0','300.00','3','urea.png',NULL,NULL,NULL,'2028-12-01','Deleted','Standard Pack');
INSERT INTO `products` VALUES('3','DAP ખાતર (IFFCO)','Local',NULL,'Fertilizers',NULL,'-19','1350.00','0','dap.png',NULL,NULL,NULL,'2028-10-01','Deleted','Standard Pack');
INSERT INTO `products` VALUES('4','મોનોક્રોટોફોસ જંતુનાશક દવા','Local',NULL,'Pesticides',NULL,'0','450.00','0','default.png',NULL,NULL,NULL,'2027-09-15','Deleted','Standard Pack');
INSERT INTO `products` VALUES('5','a_one','Local',NULL,'Pesticides',NULL,'0','1280.00','0','1782313070_aone.jpg',NULL,NULL,NULL,'2029-06-12','Deleted','Standard Pack');
INSERT INTO `products` VALUES('9','a_one','Local','Alphamethrin 10% E.C.','Pesticides',NULL,'0','1379.00','0','default.png','Cotton','boll worms','15-25','2029-06-12','Deleted','Standard Pack');
INSERT INTO `products` VALUES('12','npk','IPL','npk :20:20:3','Fertilizers',NULL,'0','1880.00','0','default.png','Cotton',NULL,NULL,'2027-04-12','Deleted','Standard Pack');
INSERT INTO `products` VALUES('13','a_one','IPL','Alphamethrin 10% E.C.','Pesticides','','0','1258.00','12','default.png','Cotton','boll worms','15-25',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('14','a_one','IPL','Alphamethrin 10% E.C.','Pesticides','','0','1258.00','0','default.png','Cotton','boll worms','15-25',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('15','chana','IPL','','Seeds','','0','1250.00','0','1783092359_chana_seeds.png','','','',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('16','chana','IPL','','Seeds','','0','1250.00','0','default.png','','','',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('17','a_one','IPL','','Seeds','','0','1000.00','3','default.png','','','',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('18','ACTION-500','IPL','Chlorpyrifos 50 %EC','Pesticides','Liquid','25','1300.00','0','1784173581_OIP.jpeg','ALL CORP','boll worms','15-25',NULL,'Active','Standard Pack');
INSERT INTO `products` VALUES('19','DAP','IFFCO','','Fertilizers','','81','1800.00','4','1785734573_DAP.png','ALL CORP','','',NULL,'Active','Standard Pack');
INSERT INTO `products` VALUES('20','NPK','IFFCO','','Fertilizers','','44','750.00','0','1785734736_NPK.png','ALL CORP','','',NULL,'Active','Standard Pack');
INSERT INTO `products` VALUES('21','ULALA','UPL','Flonicamid 50% WG','Pesticides','Liquid','0','1200.00','0','1785735181_ULALA.png','Cotton','Thrips','4.5',NULL,'Active','Standard Pack');
INSERT INTO `products` VALUES('22','ULALA','UPL','Flonicamid 50% WG','Pesticides','Liquid','89','1200.00','0','1785820892_ULALA.png','Cotton','Thrips','4.5',NULL,'Deleted','Standard Pack');
INSERT INTO `products` VALUES('23','Chlirax-20','IPL','Chlorpyriphos 20% E.C','Pesticides','Insecticide (Liquid - Contact)','31','1850.00','0','1785860380_Chlorax-20.jpg','Cotton','White Fly , Aphid ,Boll Worm','15-25',NULL,'Active','Standard Pack');
INSERT INTO `products` VALUES('24','NPK','IFFCO','','Fertilizers','Granular','0','1280.00','0','1786449459_1785734736_NPK.png','ALL CORP','','',NULL,'Active','50KG');


DROP TABLE IF EXISTS `shop_users`;
CREATE TABLE `shop_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `shop_users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `shop_users` VALUES('1','admin_main','Admin@123','1');
INSERT INTO `shop_users` VALUES('2','staff_rajesh','staff123','2');
INSERT INTO `shop_users` VALUES('3','jaydip','Jaydip@123','2');


DROP TABLE IF EXISTS `soil_reports`;
CREATE TABLE `soil_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `farmer_id` int(11) NOT NULL,
  `ph_level` decimal(4,2) NOT NULL,
  `nitrogen_kg_ha` decimal(6,2) NOT NULL,
  `phosphorus_kg_ha` decimal(6,2) NOT NULL,
  `potassium_kg_ha` decimal(6,2) NOT NULL,
  `report_date` date NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `farmer_id` (`farmer_id`),
  CONSTRAINT `soil_reports_ibfk_1` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `soil_reports` VALUES('1','1','7.20','240.50','14.20','115.00','2026-05-15');
INSERT INTO `soil_reports` VALUES('2','2','5.80','190.00','8.50','95.00','2026-06-01');
INSERT INTO `soil_reports` VALUES('3','3','12.00','213.00','23.00','234.00','2026-07-05');
INSERT INTO `soil_reports` VALUES('4','3','12.00','231.00','13.00','134.00','2026-07-05');
INSERT INTO `soil_reports` VALUES('5','3','3.08','231.00','34.00','321.00','2026-07-05');
INSERT INTO `soil_reports` VALUES('6','3','8.20','210.00','15.00','125.00','2026-07-19');


DROP TABLE IF EXISTS `system_users`;
CREATE TABLE `system_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'Admin',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `system_users` VALUES('1','admin','admin123','Admin');


DROP TABLE IF EXISTS `user_roles`;
CREATE TABLE `user_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(50) NOT NULL,
  `can_manage_stock` tinyint(1) DEFAULT 0,
  `can_view_reports` tinyint(1) DEFAULT 0,
  `can_delete_data` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_name` (`role_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `user_roles` VALUES('1','Super Admin','1','1','1');
INSERT INTO `user_roles` VALUES('2','Shop Staff','1','0','0');


SET FOREIGN_KEY_CHECKS=1;
