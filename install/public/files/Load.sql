-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 20, 2019 at 08:46 AM
-- Server version: 5.7.24
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `load`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` int(5) NOT NULL,
  `bankname` varchar(100) DEFAULT NULL,
  `branchbank` varchar(100) DEFAULT NULL,
  `account` varchar(5) DEFAULT NULL,
  `iban` varchar(100) DEFAULT NULL,
  `status_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `type`, `bankname`, `branchbank`, `account`, `iban`, `status_id`) VALUES
(1, 'Cash Account', 0, '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `appconfig`
--

CREATE TABLE `appconfig` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `appconfig`
--

INSERT INTO `appconfig` (`id`, `name`, `value`) VALUES
(1, 'inv_prefix', 'INV-'),
(2, 'inv_suffix', ''),
(3, 'project_prefix', 'PROJ-'),
(4, 'project_suffix', ''),
(5, 'expense_prefix', 'EXP-'),
(6, 'expense_suffix', ''),
(7, 'proposal_prefix', 'PRO-'),
(8, 'proposal_suffix', ''),
(9, 'order_prefix', 'ODR-'),
(10, 'order_suffix', ''),
(11, 'tax_label', 'VAT'),
(12, 'product_prefix', 'PRD-'),
(13, 'vendor_prefix', 'VEN-'),
(14, 'customer_prefix', 'CUST-'),
(15, 'lead_prefix', 'LEAD-'),
(16, 'ticket_prefix', 'TKT-'),
(17, 'staff_prefix', 'STAFF-'),
(18, 'purchase_prefix', 'PO-'),
(19, 'task_prefix', 'TASK-'),
(20, 'invoice_series', '1'),
(21, 'project_series', '1'),
(22, 'product_series', '1'),
(23, 'order_series', '1'),
(24, 'proposal_series', '1'),
(25, 'vendor_series', '1'),
(26, 'customer_series', '1'),
(27, 'expense_series', '1'),
(28, 'lead_series', '1'),
(29, 'ticket_series', '1'),
(30, 'staff_series', '1'),
(31, 'purchase_series', '1'),
(32, 'task_series', '1'),
(33, 'deposit_prefix', 'DEP-'),
(34, 'deposit_series', '1'),
(35, 'decimal_separator', '.'),
(36, 'thousand_separator', ','),
(37, 'appointment_color', '#23ce54'),
(38, 'project_color', '#81c5f4'),
(39, 'task_color', '#f4de81');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(5) NOT NULL,
  `contact_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `branding`
--

CREATE TABLE `branding` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `branding`
--

INSERT INTO `branding` (`id`, `name`, `value`) VALUES
(1, 'meta_keywords', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.'),
(2, 'meta_description', 'Ciuis, CRM, Project Management tool, client management, crm, customer, expenses, invoice system, invoices, lead, project management, recurring invoices, sales, self hosted, support tickets, task manager, ticket system'),
(3, 'admin_login_image', 'login.jpg'),
(4, 'client_login_image', 'login.jpg'),
(7, 'admin_login_text', 'Welcome! With Ciuis CRM you can easily manage your customer relationships and save time on your business.'),
(8, 'client_login_text', 'Ciuis™ CRM software for customer relationship management is available for sale, so you can get more information to take advantage of your exclusive concierge.'),
(9, 'enable_support_button_on_client', '1'),
(10, 'favicon_icon', 'logo-fav.png'),
(11, 'support_button_title', 'Ciuis Support'),
(12, 'support_button_link', 'https://support.suisesoft.tech/'),
(13, 'title', 'Ciuis CRM'),
(14, 'nav_logo', ''),
(15, 'title', 'Ciuis CRM'),
(16, 'app_logo', ''),
(17, 'preloader', 'preloader.gif'),
(18, 'disable_preloader', '0');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` mediumtext,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `surname` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `extension` varchar(100) DEFAULT NULL,
  `mobile` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `language` varchar(100) NOT NULL,
  `address` text,
  `skype` varchar(100) DEFAULT NULL,
  `linkedin` varchar(100) DEFAULT NULL,
  `customer_id` int(5) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `primary` int(5) DEFAULT '0',
  `admin` int(5) DEFAULT '0',
  `inactive` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customergroups`
--

CREATE TABLE `customergroups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_number` varchar(100) DEFAULT NULL,
  `type` int(5) DEFAULT NULL,
  `created` date NOT NULL,
  `staff_id` int(5) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `namesurname` varchar(200) DEFAULT NULL,
  `groupid` int(5) NOT NULL,
  `taxoffice` varchar(100) DEFAULT NULL,
  `taxnumber` varchar(50) DEFAULT NULL,
  `ssn` varchar(100) DEFAULT NULL,
  `executive` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `city` varchar(200) DEFAULT NULL,
  `town` varchar(200) DEFAULT NULL,
  `billing_street` varchar(200) NOT NULL,
  `billing_city` varchar(100) NOT NULL,
  `billing_state` varchar(200) NOT NULL,
  `billing_state_id` int(5) DEFAULT NULL,
  `billing_zip` varchar(50) NOT NULL,
  `billing_country` int(5) NOT NULL,
  `shipping_street` varchar(200) NOT NULL,
  `shipping_city` varchar(200) NOT NULL,
  `shipping_state` varchar(255) NOT NULL,
  `shipping_state_id` int(5) DEFAULT NULL,
  `shipping_zip` varchar(255) NOT NULL,
  `shipping_country` int(11) NOT NULL,
  `latitude` varchar(100) NOT NULL,
  `longitude` varchar(100) NOT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `fax` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(100) DEFAULT NULL,
  `risk` int(5) DEFAULT '0',
  `customer_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active',
  `subsidiary_parent_id` int(5) NOT NULL,
  `default_payment_method` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields`
--

CREATE TABLE `custom_fields` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `order` int(11) NOT NULL,
  `data` longtext NOT NULL,
  `relation` varchar(100) NOT NULL,
  `icon` varchar(100) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `active` varchar(100) NOT NULL DEFAULT 'true',
  `updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `custom_fields_data`
--

CREATE TABLE `custom_fields_data` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `db_backup`
--

CREATE TABLE `db_backup` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `version` varchar(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'Administrator'),
(2, 'Sales Agent'),
(3, 'IT Services'),
(4, 'Tax Dept');

-- --------------------------------------------------------

--
-- Table structure for table `depositcat`
--

CREATE TABLE `depositcat` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `deposit_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `category_id` int(5) NOT NULL,
  `account_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `status` int(5) DEFAULT NULL COMMENT 'Unpaid-0, Paid-1, Internal-2',
  `recurring` int(5) DEFAULT NULL,
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) NOT NULL DEFAULT '0',
  `deposits_created_by` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `show_to_customer` tinyint(1) NOT NULL DEFAULT '0',
  `datecreated` datetime NOT NULL,
  `staff_id` int(5) NOT NULL DEFAULT '0',
  `contact_id` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `discussion_comments`
--

CREATE TABLE `discussion_comments` (
  `id` int(11) NOT NULL,
  `discussion_id` int(5) NOT NULL,
  `created` datetime NOT NULL,
  `content` text NOT NULL,
  `staff_id` int(5) NOT NULL,
  `contact_id` int(5) DEFAULT '0',
  `full_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

CREATE TABLE `email_queue` (
  `id` int(11) NOT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `subject` mediumtext,
  `message` mediumtext,
  `attachments` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `created` datetime DEFAULT NULL,
  `send_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `email_templates`
--

CREATE TABLE `email_templates` (
  `id` int(11) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  `from_name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  `display` tinyint(1) DEFAULT '1',
  `attachment` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_templates`
--

INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES
(1, 'invoice', 'invoice_message', 'Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <strong>{invoice_number}</strong></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(2, 'invoice', 'invoice_reminder', 'Send Invoice', '<h1><span xss=removed>DOCTYPE</span></h1>\n<p xss=removed>DOCTYPE</p>', 'Ciuis CRM', 1, 0, 0),
(3, 'invoice', 'invoice_payment', 'Invoice Payment Recorded', '<p><span>Hello {customer}<br><br></span>Thank you for the payment. Find the payment details below:<br><br>-------------------------------------------------<br><br>Amount: <strong>{payment_total}<br></strong>Date: <strong>{payment_date}</strong><br>Invoice number: <span><strong># {invoice_number}<br><br></strong></span>-------------------------------------------------<br><br>You can always view the invoice for this payment at the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></p><p><a>View Invoice</a><br><br>We are looking forward working with you.<br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(4, 'invoice', 'invoice_overdue', 'Invoice Overdue Notice - {invoice_number}', '<p><span>Hi {customer},</span><br><br><span>This is an overdue notice for invoice <strong># {invoice_number}</strong></span><br><br><span>This invoice was due: {invoice_duedate}</span><br><br><span>You can view the invoice on the following link: <a>{invoice_number}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong><br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 0, 0),
(6, 'customer', 'new_contact_added', 'Welcome aboard', '<p>Dear {customer},<br><br>Thank you for registering.<br><br>We just wanted to say welcome.<br><br>Please contact us if you need any help.<br><br>Click here to view your profile: <a href=\"{app_url}\" data-mce-href=\"{app_url}\" style=\"\">{app_url}</a></p><p>Your login details:</p><p>Email: <span><strong>{login_email}</strong></span></p><p>Password: <span><strong>{login_password}</strong></span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br><br>(This is an automated email, so please don\'t reply to this email address)</p>', 'Ciuis CRM', 1, 1, 0),
(7, 'customer', 'new_customer', 'New Customer Registration', '<p>Hello Admin.<br><br>New customer registration on your customer portal:<br></p><p><strong>Type</strong>: {customer_type}<br><strong>Name: </strong>{name}<br><strong>Email:</strong> {customer_email}<br><br>Best Regards<br></p>', 'Ciuis CRM', 1, 1, 0),
(8, 'staff', 'new_staff', 'New Staff Added (Welcome Email)', '<p>Hi {staff},<br><br>You are added as member on our CRM.<br><br>Please use the following logic credentials:<br><br><strong>Email:</strong> {staff_email}<br><strong>Password:</strong> {password}<br><br>Click <span><span><a href=\"{login_url}\" data-mce-href=\"{login_url}\">here </a> </span></span>to login in the dashboard.<br><br>Best Regards,<br><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(9, 'staff', 'forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {staffname},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(10, 'staff', 'password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{staff_email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(11, 'staff', 'reminder_email', 'Send Invoice', 'Im sending Invoice', 'Ciuis CRM', 1, 0, 0),
(12, 'task', 'new_task_assigned', 'New Task Assigned to You - {task_name}', '<p><span>Dear {staffname},</span><br><br><span>You have been assigned to a new task:</span><br><br><span><strong>Name:</strong> {task_name}</span></p><p><strong>Start Date:</strong> {task_startdate}</p><p><span><strong>Due date:</strong> {task_duedate}</span></p><p><span><strong>Priority:</strong> {task_priority}</span></p><p><strong>Status:</strong> {task_status}<span><br><br></span><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\" style=\"\">view</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(13, 'task', 'task_comments', 'New Comment on Task - {task_name}', '<p>Dear {staffname},<br><br>A comment has been made on the following task:<br><br><strong>Task:</strong> {task_name}<br><strong>Comment:</strong> {task_comment}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(14, 'task', 'task_attachment', 'New Attachment on Task - {task_name}', '<p>Hi {staffname},<br><br><strong>{logged_in_user}</strong> added an attachment on the following task:<br><br><strong>Name:</strong> {task_name}<br><br>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">view</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(15, 'task', 'task_updated', 'Task Status Changed', '<p><span>Hi {staffname},</span><br><br><span><strong>{</strong></span><span><b>logged_in_user}</b> marked task as <strong>{task_status}</strong></span><br><br><span><strong>Name:</strong> {task_name}</span><br><span><strong>Due date:</strong> {task_duedate}</span><br><br><span>You can view the task on the following link: <a href=\"{task_url}\" data-mce-href=\"{task_url}\">{task_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(19, 'ticket', 'new_ticket', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>New ticket has been opened.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department:</strong> {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><span><a><br></a>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(20, 'ticket', 'new_customer_ticket', 'New Ticket Created', '<p><span>A new ticket has been created.</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(21, 'ticket', 'ticket_assigned', 'New ticket has been assigned to you', '<p><span>Hi {assigned},</span></p><p><span>A new support ticket&ampampnbsphas been assigned to you.</span></p><p><br><strong>Subject:</strong> {ticket_subject}<br><strong>Department:</strong> {ticket_department}<br><strong>Priority:</strong> {ticket_priority}<br></p><p><strong>Customer:</strong> {customer}</p><p><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(22, 'ticket', 'ticket_reply_to_staff', 'Ticket Reply', '<p><span>A new support ticket reply from {customer}</span><br><br><span><strong>Subject</strong>: {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority</strong>: {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(23, 'ticket', 'ticket_autoresponse', 'New Ticket Opened', '<p><span>Hi {customer},</span><br><br><span>Thank you for contacting our team. A ticket has now been created for your request. </span></p><p><span>You will be notified when a response is made by email.</span><br><br><span><strong>Subject:</strong> {ticket_subject}</span><br><span><strong>Department</strong>: {ticket_department}</span><br><span><strong>Priority:</strong> {ticket_priority}</span><br><br><span><strong>Ticket message:</strong></span><br><span>{ticket_message}</span><br><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(24, 'ticket', 'ticket_reply_to_customer', 'New Ticket Reply', '<p><span>Hi {customer},</span><br><br><span>You have a new ticket reply to ticket.</span><br><br></p><p><strong>Subject: </strong>{ticket_subject}<br><strong></strong><br><strong>Ticket message:</strong><br>{ticket_message}<br><br><a><br></a>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(25, 'proposal', 'send_proposal', 'Proposal With Number {proposal_number} Created', '<p>Dear {proposal_to},<br><br>Please find our attached proposal.<br><br>This proposal is valid until: {open_till}</p><p><br><strong>Proposal Subject:</strong> {subject}</p><p><strong>Proposal Details:</strong></p><p>{details}</p><p><br>Please don\'t hesitate to comment online if you have any questions.<br><br>We look forward to your communication.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 1),
(26, 'proposal', 'thankyou_email', 'Thank for you accepting the proposal', '<p>Dear {proposal_to},<br><br>Thank for for accepting the proposal.<br><br>We look forward to doing business with you.<br><br>We will contact you as soon as possible<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(27, 'proposal', 'customer_accepted_proposal', 'Customer Accepted Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> accepted the following proposal:<br><br><strong>Number:</strong> {proposal_number}<br><strong>Subject</strong>: {subject}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div>', 'Ciuis CRM', 1, 1, 0),
(28, 'proposal', 'customer_rejected_proposal', 'Client Declined Proposal', '<div>Hi,<br><br>Client <strong>{proposal_to}</strong> declined the proposal <strong>{subject}</strong><br><br><strong>Proposal Number:</strong> {proposal_number}<br><strong>Total</strong>: {proposal_total}<br><br>Kind Regards,</div><div><strong>{name}</strong>,<br>{email_signature}</div><div> </div><div> </div><p> <br></p><div> </div>', 'Ciuis CRM', 1, 1, 0),
(29, 'lead', 'lead_assigned', 'New lead assigned to you', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(34, 'project', 'project_notification', 'New project created', '<p>Hello {customer},</p><p>New project is assigned to your company.<br><br><strong>Project Name:</strong> {project_name}<br><strong>Project Start Date:</strong> {project_start_date}</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\" style=\"\">{project_name}</a></p><p>\n\n\n</p><p>We are looking forward hearing from you.<br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(35, 'project', 'staff_added', 'New project assigned to you', '<p>Hi {staff},<br><br>New project has been assigned to you.<br><br>You can view the project on the following link <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a><br></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(36, 'project', 'new_file_uploaded_to_members', 'New Project File Uploaded - {project_name}', '<p>Hello {staff},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(37, 'project', 'new_file_uploaded_to_customer', 'New Project File Uploaded - {project_name}', '<p>Hello {customer},</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(38, 'project', 'new_note_to_members', 'New Note on Project - {project_name}', '<p>Hello {staff},<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>You can view the note on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(39, 'project', 'new_note_to_customers', 'New Note on Project - {project_name}', '<p><span>Hello {customer},</span><br><br><span>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong></span><br><br><span><strong>Note</strong>: </span></p><p><span>{note}</span><br><br><span>You can view the note on the following link: <a href=\"{project_url}\" data-mce-href=\"{project_url}\">{project_name}</a></span><br><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span></p>', 'Ciuis CRM', 1, 1, 0),
(40, 'project', 'project_status_changed', 'Project Status Changed', '<p><span>Hi {customer},</span><br><br><span><strong>{loggedin_staff}</strong> marked project as <strong>{project_status}</strong></span><br><br></p><p><strong>Project Name: </strong>{project_name}<br><strong>Project End Date:</strong> {project_end_date}</p><p>You can view the project on the following link: <a href=\"http://localhost:8080/ciuiscrm/emails/template/%7Bproject_url%7D\" data-mce-href=\"{project_url}\">{project_name}</a></p><p><br><span>Kind Regards,</span></p><p><strong>{name}</strong>,<br><span>{email_signature}</span><br></p>', 'Ciuis CRM', 1, 1, 0),
(42, 'expense', 'expense_created', 'Expense Created - {expense_number}', '<p><strong></strong>Hello {customer},<br><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p><span>{expense_title}</span></p><p><strong>Expense Category:</strong></p><p><span>{expense_category}</span></p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Description:</strong></p><p><span>{expense_description}</span></p><p><br></p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 1),
(43, 'staff', 'customer_forgot_password', 'Reset password', '<h2><span>Reset password</span></h2><p>Hi {customer},</p><p><br>Forgot your password?<br>To create a new password, just follow this link:<br><br><a href=\"{password_url}\" data-mce-href=\"{password_url}\">Reset Password</a><br><br>You received this email, because it was requested by a user. This is part of the procedure to create a new password on the system. If you DID NOT request a new password then please ignore this email and your password will remain the same. <br></p><p>Regards,</p><p>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(44, 'staff', 'customer_password_reset', 'Your password has been changed', '<p><span><strong>You have changed your password.<br></strong></span><br>Please, keep it in your records so you don\'t forget it.<br><br>Your email address for login is: <span style=\"color: rgb(0, 0, 255)\">{email}</span><br><br>If this was not you, please contact us.<br></p><p>Regards,<br>{email_signature}<br></p>', 'Ciuis CRM', 1, 1, 0),
(45, 'invoice', 'invoice_recurring', 'Recurring Invoice with number {invoice_number} created', '<p><span><strong>INVOICE {invoice_number}</strong></span><br><br></p><div>Hello {customer},</div><div><br></div><div>We have prepared the following invoice for you: # <strong>{invoice_number}</strong></div><div><br></div><div>Invoice status: <strong>{invoice_status}</strong></div><div><br></div><div>You can view the invoice on the following link: <a href=\"{invoice_link}\" data-mce-href=\"{invoice_link}\">{invoice_number}</a></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(46, 'lead', 'web_lead_created', 'New Online Web lead created', '<p>Hello {lead_assigned_staff},<br><br>New lead is assigned to you.<br><br>Lead Name: {lead_name}<br>Lead Email: {lead_email}<br><br>You can view the lead on the following link: <a href=\"{lead_url}\" data-mce-href=\"{lead_url}\">View</a><br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}<br></p>', 'Ciuis CRM', 0, 1, 0),
(47, 'lead', 'lead_submitted', 'New lead has been created for you', '<p>Hello {lead_name},<br><br>New lead has been created for you.<br><br>Lead Email: {lead_email}<br><br><br>Kind Regards,<br><strong>{email_signature}</strong><br></p>', 'Ciuis CRM', 0, 1, 0),
(48, 'expense', 'expense_recurring', 'Recurring Expense with number {expense_number} created', '<p>Hello {customer},</p><p><br>We have prepared the following expense for you: # <strong>{expense_number}</strong><br></p><p><strong>Expense Title:</strong></p><p>{expense_title}</p><p><strong>Expense Category:</strong></p><p>{expense_category}</p><p><strong>Expense Date: </strong>{expense_date}</p><p><strong>Expense Amount: </strong>{expense_amount}<br><br>Please contact us for more information.<br><br>Kind Regards,</p><p><strong>{name},</strong><br>{email_signature}</p>', 'Ciuis CRM', 0, 1, 1),
(50, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(51, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(52, 'order', 'order_message', 'Order {order_number} confirmed', '<p>Hello {customer},<br></p><div><br></div><div><br></div><div>Thank you very much for your recent order with {company_name}. Your order is currently being processed and you will receive a shipping confirmation once the order has been shipped.</div><div><br></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{name},</strong></div><div>{email_signature}</div>', 'Ciuis CRM', 1, 1, 1),
(53, 'project', 'new_note_to_members_by_customer', 'New Note on Project by customer - {project_name}', '<p>Hello,<br><br>New note has been made on project <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong><br><br><strong>Note</strong>:</p><p><span>{note}</span><br><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(54, 'project', 'new_file_uploaded_by_customer', 'New Project File Uploaded By Customer - {project_name}', '<p>Hello,</p><p>New project file is uploaded on <strong>{project_name}</strong> by <strong>{loggedin_staff}</strong>.</p><p>You can find the attachment below attached.</p><p><br>Kind Regards,</p><p><strong>{name}</strong>,<br>{email_signature}</p>', 'Ciuis CRM', 1, 1, 0),
(55, 'quote', 'request_quote', 'New Quote requested by {customer_name}', '<p>Dear {staff},<br><br>{customer_name} has requested a new quote.<br><br><br><strong>Quote Subject:</strong> {subject}</p><p><strong>Quote </strong><strong>Details:</strong></p><p>{details}</p><p><strong>Quote </strong><strong>Link: </strong><a href=\"{quote_link}\" data-mce-href=\"{quote_link}\">{quote_link}</a></p><p><br><br>Kind Regards,</p><p><strong>{company_name}</strong>,<br>{company_email}<br></p>', 'Ciuis CRM', 1, 1, 0),
(56, 'quote', 'quote_status_changed', 'Quote Status has Changed by {staff}', '<div><p>Hi {customer_name},<br><br><strong>{staff}</strong> marked quote as <strong>{quote_status}</strong><br><br></p><p><strong>Quote Subject: </strong>{subject}<br><strong>Quote Details:</strong></p><p>{details}</p><p><br>Kind Regards,</p><p><strong>{company_name}</strong>,<br>{company_email}</p></div><div><br></div>', 'Ciuis CRM', 1, 1, 0),
(57, 'events', 'event_reminder', 'Event Reminder', '<p>Hello {staff},<br></p><p>This is the {event_title} reminder.</p><p><br data-mce-bogus=\"1\"></p><p>Regards,</p><p><strong>{company_name}</strong></p><p>{company_email}</p>', 'Ciuis CRM', 1, 1, 0),
(58, 'purchase', 'purchase_message', 'purchase with number {purchase_number} created', '<p><span><strong>PURCHASE {purchase_number}</strong></span><br><br></p><div>Hello {vendor_name},</div><div><br></div><div>We have prepared the following purchase order for you: # <strong>{purchase_number}</strong></div><div><br></div><div>Purchase status: <strong>{purchase_status}</strong></div><div><br></div><div>You can view the purchase order on the following link: <a href=\"{purchase_link}\" data-mce-href=\"{purchase_link}\">{purchase_number}</a></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(59, 'purchase', 'purchase_payment', 'Purchase Payment Recorded', '<p><span>Hello {vendor_name}<br><br></span>Thank you for the payment. Find the payment details below:<br><br>-------------------------------------------------<br><br>Amount: <strong>{payment_amount}<br></strong>Date: <strong>{payment_date}</strong><br>Purchase number: <span><strong># {purchase_number}<br><br></strong></span>-------------------------------------------------<br><br>You can always view the purchase for this payment at the following link:{purchase_link}</p><p><a>View Purchase</a><br><br>We are looking forward working with you.<br><br><span>Kind Regards,</span></p><p><strong>{company_name}</strong><br><span>{company_email}</span></p>', 'Ciuis CRM', 1, 1, 0),
(60, 'purchase', 'purchase_recurring', 'Recurring Purchase with number {purchase_number} created', '<p><span><strong>PURCHASE {purchase_number}</strong></span><br><br></p><div>Hello {vendor_name},</div><div><br></div><div>We have prepared the following purchase for you: # <strong>{purchase_number}</strong></div><div><br></div><div>Purchase status: <strong>{purchase_status}</strong></div><div><br></div><div>You can view the purchase on the following link: {purchase_link}</div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(61, 'deposit', 'deposit_message', 'Deposit with number {deposit_number} created', '<p><span><strong>DEPOSIT {deposit_number}</strong></span><br><br></p><div>Hello {customer_name},</div><div><br></div><div>We have prepared the following deposit for you: # <strong>{deposit_number}</strong></div><div><br></div><div>Deposit status: <strong>{deposit_status}</strong></div><div><br></div><div>You can view the deposit on the following link: <strong>{deposit_link}</strong></div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1),
(62, 'deposit', 'recurring_deposit', 'Recurring Deposit with number {deposit_number} created', '<p><strong>DEPOSIT {deposit_number}</strong><br><br></p><div>Hello {customer_name},</div><div><br></div><div>We have prepared the following deposit for you: # <strong>{deposit_number}</strong></div><div><br></div><div>Deposit status: <strong>{deposit_status}</strong></div><div><br></div><div>You can view the deposit on the following link: {deposit_link}</div><div><br></div><div>Please contact us for more information.</div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `email_template_fields`
--

CREATE TABLE `email_template_fields` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_value` varchar(255) NOT NULL,
  `template_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `email_template_fields`
--

INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES
(1, 1, 'email_signature', 'email_signature', 'invoice_message'),
(2, 1, 'invoice_number', 'invoice_number', 'invoice_message'),
(3, 1, 'invoice_status', 'invoice_status', 'invoice_message'),
(4, 1, 'name', 'name', 'invoice_message'),
(5, 1, 'invoice_link', 'invoice_link', 'invoice_message'),
(6, 3, 'payment_total', 'payment_total', 'invoice_payment'),
(7, 3, 'payment_date', 'payment_date', 'invoice_payment'),
(8, 3, 'invoice_number', 'invoice_number', 'invoice_payment'),
(9, 3, 'email_signature', 'email_signature', 'invoice_payment'),
(10, 3, 'customer', 'customer', 'invoice_payment'),
(11, 3, 'invoice_link', 'invoice_link', 'invoice_payment'),
(12, 4, 'invoice_number', 'invoice_number', 'invoice_overdue'),
(13, 4, 'invoice_duedate', 'invoice_duedate', 'invoice_overdue'),
(14, 4, 'name', 'name', 'invoice_overdue'),
(15, 4, 'email_signature', 'email_signature', 'invoice_overdue'),
(16, 4, 'customer', 'customer', 'invoice_overdue'),
(17, 6, 'companyname', 'companyname', 'new_contact_added'),
(18, 6, 'app_url', 'app_url', 'new_contact_added'),
(19, 6, 'customer', 'customer', 'new_contact_added'),
(20, 6, 'name', 'name', 'new_contact_added'),
(21, 6, 'email_signature', 'email_signature', 'new_contact_added'),
(22, 7, 'customer_type', 'customer_type', 'new_customer'),
(23, 7, 'name', 'name', 'new_customer'),
(24, 7, 'customer_email', 'customer_email', 'new_customer'),
(25, 19, 'customer', 'customer', 'new_ticket'),
(26, 19, 'ticket_subject', 'ticket_subject', 'new_ticket'),
(27, 19, 'ticket_department', 'ticket_department', 'new_ticket'),
(28, 19, 'ticket_priority', 'ticket_priority', 'new_ticket'),
(29, 19, 'ticket_message', 'ticket_message', 'new_ticket'),
(30, 19, 'name', 'name', 'new_ticket'),
(31, 19, 'email_signature', 'email_signature', 'new_ticket'),
(32, 21, 'assigned', 'assigned', ''),
(33, 21, 'ticket_subject', 'ticket_subject', 'ticket_assigned'),
(34, 21, 'ticket_department', 'ticket_department', 'ticket_assigned'),
(35, 21, 'ticket_priority', 'ticket_priority', 'ticket_assigned'),
(36, 21, 'ticket_message', 'ticket_message', 'ticket_assigned'),
(37, 21, 'name', 'name', 'ticket_assigned'),
(38, 21, 'email_signature', 'email_signature', 'ticket_assigned'),
(39, 24, 'customer', 'customer', 'ticket_reply_to_customer'),
(40, 24, 'ticket_subject', 'ticket_subject', 'ticket_reply_to_customer'),
(41, 24, 'ticket_message', 'ticket_message', 'ticket_reply_to_customer'),
(42, 24, 'name', 'name', 'ticket_reply_to_customer'),
(43, 24, 'email_signature', 'email_signature', 'ticket_reply_to_customer'),
(44, 25, 'proposal_to', 'proposal_to', 'send_proposal'),
(45, 25, 'open_till', 'open_till', 'send_proposal'),
(46, 25, 'open_till', 'open_till', 'send_proposal'),
(47, 25, 'name', 'name', 'send_proposal'),
(48, 25, 'email_signature', 'email_signature', 'send_proposal'),
(49, 25, 'subject', 'subject', 'send_proposal'),
(50, 25, 'details', 'details', 'send_proposal'),
(51, 26, 'proposal_to', 'proposal_to', 'thankyou_email'),
(52, 26, 'name', 'name', 'thankyou_email'),
(53, 26, 'email_signature', 'email_signature', 'thankyou_email'),
(54, 26, 'subject', 'subject', 'thankyou_email'),
(55, 26, 'details', 'details', 'thankyou_email'),
(56, 26, 'proposal_number', 'proposal_number', 'thankyou_email'),
(57, 27, 'proposal_to', 'proposal_to', 'customer_accepted_proposal'),
(58, 27, 'subject', 'subject', 'customer_accepted_proposal'),
(59, 27, 'proposal_number', 'proposal_number', 'customer_accepted_proposal'),
(60, 27, 'proposal_total', 'proposal_total', 'customer_accepted_proposal'),
(61, 27, 'name', 'name', 'customer_accepted_proposal'),
(62, 27, 'email_signature', 'email_signature', 'customer_accepted_proposal'),
(63, 27, 'details', 'details', 'customer_accepted_proposal'),
(64, 28, 'proposal_to', 'proposal_to', 'customer_rejected_proposal'),
(65, 28, 'proposal_number', 'proposal_number', 'customer_rejected_proposal'),
(66, 28, 'subject', 'subject', 'customer_rejected_proposal'),
(67, 28, 'proposal_total', 'proposal_total', 'customer_rejected_proposal'),
(68, 28, 'name', 'name', 'customer_rejected_proposal'),
(69, 28, 'email_signature', 'email_signature', 'customer_rejected_proposal'),
(70, 28, 'details', 'details', 'customer_rejected_proposal'),
(71, 8, 'staff', 'staff', 'new_staff'),
(72, 8, 'staff_email', 'staff_email', 'new_staff'),
(73, 8, 'password', 'password', 'new_staff'),
(74, 8, 'name', 'name', 'new_staff'),
(75, 8, 'email_signature', 'email_signature', 'new_staff'),
(76, 8, 'login_url', 'login_url', 'new_staff'),
(77, 9, 'staffname', 'staffname', 'forgot_password'),
(78, 9, 'password_url', 'password_url', 'forgot_password'),
(80, 9, 'email_signature', 'email_signature', 'forgot_password'),
(81, 10, 'staff_email', 'staff_email', 'password_reset'),
(82, 10, 'email_signature', 'email_signature', 'password_reset'),
(83, 10, 'staffname', 'staffname', 'password_reset'),
(84, 29, 'lead_assigned_staff', 'lead_assigned_staff', 'lead_assigned'),
(85, 29, 'lead_name', 'lead_name', 'lead_assigned'),
(86, 29, 'lead_email', 'lead_email', 'lead_assigned'),
(87, 29, 'lead_url', 'lead_url', 'lead_assigned'),
(88, 29, 'name', 'name', 'lead_assigned'),
(89, 29, 'email_signature', 'email_signature', 'lead_assigned'),
(90, 12, 'staffname', 'staffname', 'new_task_assigned'),
(91, 12, 'task_name', 'task_name', 'new_task_assigned'),
(92, 12, 'task_startdate', 'task_startdate', 'new_task_assigned'),
(93, 12, 'task_duedate', 'task_duedate', 'new_task_assigned'),
(94, 12, 'task_priority', 'task_priority', 'new_task_assigned'),
(95, 12, 'task_url', 'task_url', 'new_task_assigned'),
(96, 12, 'name', 'name', 'new_task_assigned'),
(97, 12, 'email_signature', 'email_signature', 'new_task_assigned'),
(98, 13, 'staffname', 'staffname', 'task_comments'),
(99, 13, 'task_name', 'task_name', 'task_comments'),
(100, 13, 'task_startdate', 'task_startdate', 'task_comments'),
(101, 13, 'task_duedate', 'task_duedate', 'task_comments'),
(102, 13, 'task_priority', 'task_priority', 'task_comments'),
(103, 13, 'task_status', 'task_status', 'task_comments'),
(104, 13, 'task_url', 'task_url', 'task_comments'),
(105, 13, 'task_comment', 'task_comment', 'task_comments'),
(106, 13, 'name', 'name', 'task_comments'),
(107, 13, 'email_signature', 'email_signature', 'task_comments'),
(108, 14, 'staffname', 'staffname', 'task_attachment'),
(109, 14, 'task_name', 'task_name', 'task_attachment'),
(110, 14, 'task_startdate', 'task_startdate', 'task_attachment'),
(111, 14, 'task_duedate', 'task_duedate', 'task_attachment'),
(112, 14, 'task_priority', 'task_priority', 'task_attachment'),
(113, 14, 'task_url', 'task_url', 'task_attachment'),
(114, 14, 'name', 'name', 'task_attachment'),
(115, 14, 'email_signature', 'email_signature', 'task_attachment'),
(116, 14, 'task_status', 'task_status', 'task_attachment'),
(117, 13, 'task_status', 'task_status', 'task_comments'),
(118, 12, 'task_status', 'task_status', 'new_task_assigned'),
(119, 14, 'logged_in_user', 'logged_in_user', 'task_attachment'),
(120, 15, 'staffname', 'staffname', 'task_updated'),
(121, 15, 'task_name', 'task_name', 'task_updated'),
(122, 15, 'task_startdate', 'task_startdate', 'task_updated'),
(123, 15, 'task_duedate', 'task_duedate', 'task_updated'),
(124, 15, 'task_priority', 'task_priority', 'task_updated'),
(125, 15, 'task_url', 'task_url', 'task_updated'),
(126, 15, 'name', 'name', 'task_updated'),
(127, 15, 'task_status', 'task_status', 'task_updated'),
(128, 15, 'logged_in_user', 'logged_in_user', 'task_updated'),
(129, 15, 'email_signature', 'email_signature', 'task_updated'),
(130, 34, 'customer', 'customer', 'project_notification'),
(131, 34, 'project_name', 'project_name', 'project_notification'),
(132, 34, 'project_start_date', 'project_start_date', 'project_notification'),
(133, 34, 'project_value', 'project_value', 'project_notification'),
(134, 34, 'project_url', 'project_url', 'project_notification'),
(135, 34, 'name', 'name', 'project_notification'),
(136, 34, 'email_signature', 'email_signature', 'project_notification'),
(137, 34, 'project_status', 'project_status', 'project_notification'),
(138, 34, 'project_tax', 'project_tax', 'project_notification'),
(139, 34, 'project_end_date', 'project_end_date', 'project_notification'),
(140, 40, 'customer', 'customer', 'project_status_changed'),
(141, 40, 'project_name', 'project_name', 'project_status_changed'),
(142, 40, 'project_start_date', 'project_start_date', 'project_status_changed'),
(143, 40, 'project_end_date', 'project_end_date', 'project_status_changed'),
(144, 40, 'project_value', 'project_value', 'project_status_changed'),
(145, 40, 'project_tax', 'project_tax', 'project_status_changed'),
(146, 40, 'project_url', 'project_url', 'project_status_changed'),
(147, 40, 'name', 'name', 'project_status_changed'),
(148, 40, 'email_signature', 'email_signature', 'project_status_changed'),
(149, 40, 'loggedin_staff', 'loggedin_staff', 'project_status_changed'),
(150, 35, 'customer', 'customer', 'staff_added'),
(151, 35, 'staff', 'staff', 'staff_added'),
(152, 35, 'project_name', 'project_name', 'staff_added'),
(153, 35, 'project_start_date', 'project_start_date', 'staff_added'),
(154, 35, 'project_end_date', 'project_end_date', 'staff_added'),
(155, 35, 'project_value', 'project_value', 'staff_added'),
(156, 35, 'project_tax', 'project_tax', 'staff_added'),
(157, 35, 'project_url', 'project_url', 'staff_added'),
(158, 35, 'name', 'name', 'staff_added'),
(159, 35, 'email_signature', 'email_signature', 'staff_added'),
(160, 35, 'loggedin_staff', 'loggedin_staff', 'staff_added'),
(161, 36, 'customer', 'customer', 'new_file_uploaded_to_members'),
(162, 36, 'staff', 'staff', 'new_file_uploaded_to_members'),
(163, 36, 'project_name', 'project_name', 'new_file_uploaded_to_members'),
(164, 36, 'project_start_date', 'project_start_date', 'new_file_uploaded_to_members'),
(165, 36, 'project_end_date', 'project_end_date', 'new_file_uploaded_to_members'),
(166, 36, 'project_value', 'project_value', 'new_file_uploaded_to_members'),
(167, 36, 'project_tax', 'project_tax', 'new_file_uploaded_to_members'),
(168, 36, 'project_url', 'project_url', 'new_file_uploaded_to_members'),
(169, 36, 'name', 'name', 'new_file_uploaded_to_members'),
(170, 36, 'email_signature', 'email_signature', 'new_file_uploaded_to_members'),
(171, 36, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_to_members'),
(172, 37, 'customer', 'customer', 'new_file_uploaded_to_customer'),
(173, 37, 'project_name', 'project_name', 'new_file_uploaded_to_customer'),
(174, 37, 'project_start_date', 'project_start_date', 'new_file_uploaded_to_customer'),
(175, 37, 'project_end_date', 'project_end_date', 'new_file_uploaded_to_customer'),
(176, 37, 'project_value', 'project_value', 'new_file_uploaded_to_customer'),
(177, 37, 'project_tax', 'project_tax', 'new_file_uploaded_to_customer'),
(178, 37, 'project_url', 'project_url', 'new_file_uploaded_to_customer'),
(179, 37, 'name', 'name', 'new_file_uploaded_to_customer'),
(180, 37, 'email_signature', 'email_signature', 'new_file_uploaded_to_customer'),
(181, 37, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_to_customer'),
(182, 38, 'customer', 'customer', 'new_note_to_members'),
(183, 38, 'note', 'note', 'new_note_to_members'),
(184, 38, 'project_name', 'project_name', 'new_note_to_members'),
(185, 38, 'project_start_date', 'project_start_date', 'new_note_to_members'),
(186, 38, 'project_end_date', 'project_end_date', 'new_note_to_members'),
(187, 38, 'project_value', 'project_value', 'new_note_to_members'),
(188, 38, 'project_tax', 'project_tax', 'new_note_to_members'),
(189, 38, 'project_url', 'project_url', 'new_note_to_members'),
(190, 38, 'name', 'name', 'new_note_to_members'),
(191, 38, 'email_signature', 'email_signature', 'new_note_to_members'),
(192, 38, 'loggedin_staff', 'loggedin_staff', 'new_note_to_members'),
(193, 39, 'customer', 'customer', 'new_note_to_customers'),
(194, 39, 'note', 'note', 'new_note_to_customers'),
(195, 39, 'project_name', 'project_name', 'new_note_to_customers'),
(196, 39, 'project_start_date', 'project_start_date', 'new_note_to_customers'),
(197, 39, 'project_end_date', 'project_end_date', 'new_note_to_customers'),
(198, 39, 'project_value', 'project_value', 'new_note_to_customers'),
(199, 39, 'project_tax', 'project_tax', 'new_note_to_customers'),
(200, 39, 'project_url', 'project_url', 'new_note_to_customers'),
(201, 39, 'name', 'name', 'new_note_to_customers'),
(202, 39, 'email_signature', 'email_signature', 'new_note_to_customers'),
(203, 39, 'loggedin_staff', 'loggedin_staff', 'new_note_to_customers'),
(204, 42, 'customer', 'customer', 'expense_created'),
(205, 42, 'expense_number', 'expense_number', 'expense_created'),
(206, 42, 'expense_title', 'expense_title', 'expense_created'),
(207, 42, 'expense_category', 'expense_category', 'expense_created'),
(208, 42, 'expense_date', 'expense_date', 'expense_created'),
(210, 42, 'expense_amount', 'expense_amount', 'expense_created'),
(211, 42, 'name', 'name', 'expense_created'),
(212, 42, 'email_signature', 'email_signature', 'expense_created'),
(213, 20, 'ticket_subject', 'ticket_subject', 'new_customer_ticket'),
(214, 20, 'ticket_department', 'ticket_department', 'new_customer_ticket'),
(215, 20, 'ticket_priority', 'ticket_priority', 'new_customer_ticket'),
(216, 20, 'ticket_message', 'ticket_message', 'new_customer_ticket'),
(217, 20, 'name', 'name', 'new_customer_ticket'),
(218, 20, 'email_signature', 'email_signature', 'new_customer_ticket'),
(219, 22, 'ticket_subject', 'ticket_subject', 'ticket_reply_to_staff'),
(220, 22, 'ticket_department', 'ticket_department', 'ticket_reply_to_staff'),
(221, 22, 'ticket_priority', 'ticket_priority', 'ticket_reply_to_staff'),
(222, 22, 'ticket_message', 'ticket_message', 'ticket_reply_to_staff'),
(223, 22, 'name', 'name', 'ticket_reply_to_staff'),
(224, 22, 'email_signature', 'email_signature', 'ticket_reply_to_staff'),
(225, 23, 'customer', 'customer', 'ticket_autoresponse'),
(226, 23, 'ticket_subject', 'ticket_subject', 'ticket_autoresponse'),
(227, 23, 'ticket_department', 'ticket_department', 'ticket_autoresponse'),
(228, 23, 'ticket_priority', 'ticket_priority', 'ticket_autoresponse'),
(229, 23, 'ticket_message', 'ticket_message', 'ticket_autoresponse'),
(230, 23, 'name', 'name', 'ticket_autoresponse'),
(231, 23, 'email_signature', 'email_signature', 'ticket_autoresponse'),
(232, 43, 'customer', 'customer', 'customer_forgot_password'),
(234, 43, 'email_signature', 'email_signature', 'customer_forgot_password'),
(235, 44, 'email', 'email', 'customer_password_reset'),
(237, 44, 'email_signature', 'email_signature', 'customer_password_reset'),
(241, 45, 'invoice_number', 'invoice_number', 'invoice_recurring'),
(242, 45, 'name', 'name', 'invoice_recurring'),
(243, 45, 'customer', 'customer', 'invoice_recurring'),
(244, 45, 'invoice_link', 'invoice_link', 'invoice_recurring'),
(245, 45, 'invoice_status', 'invoice_status', 'invoice_recurring'),
(246, 45, 'email_signature', 'email_signature', 'invoice_recurring'),
(247, 1, 'customer', 'customer', 'invoice_message'),
(248, 46, 'lead_assigned_staff', 'lead_assigned_staff', 'web_lead_created'),
(249, 46, 'lead_name', 'lead_name', 'web_lead_created'),
(250, 46, 'lead_email', 'lead_email', 'web_lead_created'),
(251, 46, 'lead_url', 'lead_url', 'web_lead_created'),
(252, 46, 'email_signature', 'email_signature', 'web_lead_created'),
(253, 46, 'name', 'name', 'web_lead_created'),
(254, 25, 'proposal_link', 'proposal_link', 'send_proposal'),
(255, 48, 'expense_title', 'expense_title', 'expense_recurring'),
(256, 48, 'email_signature', 'email_signature', 'expense_recurring'),
(257, 48, 'expense_number', 'expense_number', 'expense_recurring'),
(258, 48, 'customer', 'customer', 'expense_recurring'),
(259, 48, 'name', 'name', 'expense_recurring'),
(260, 48, 'expense_amount', 'expense_amount', 'expense_recurring'),
(261, 48, 'expense_category', 'expense_category', 'expense_recurring'),
(262, 48, 'expense_date', 'expense_date', 'expense_recurring'),
(263, 49, 'expense_title', 'expense_title', 'expense_consultant'),
(264, 49, 'email_signature', 'email_signature', 'expense_consultant'),
(265, 49, 'expense_number', 'expense_number', 'expense_consultant'),
(266, 49, 'customer', 'customer', 'expense_consultant'),
(267, 49, 'name', 'name', 'expense_consultant'),
(268, 49, 'expense_amount', 'expense_amount', 'expense_consultant'),
(269, 49, 'expense_category', 'expense_category', 'expense_consultant'),
(270, 49, 'expense_date', 'expense_date', 'expense_consultant'),
(271, 50, 'note', 'note', 'new_note_to_members_by_customer'),
(272, 50, 'project_name', 'project_name', 'new_note_to_members_by_customer'),
(273, 50, 'project_start_date', 'project_start_date', 'new_note_to_members_by_customer'),
(274, 50, 'project_end_date', 'project_end_date', 'new_note_to_members_by_customer'),
(275, 50, 'project_value', 'project_value', 'new_note_to_members_by_customer'),
(276, 50, 'project_tax', 'project_tax', 'new_note_to_members_by_customer'),
(277, 50, 'project_url', 'project_url', 'new_note_to_members_by_customer'),
(278, 50, 'name', 'name', 'new_note_to_members_by_customer'),
(279, 50, 'email_signature', 'email_signature', 'new_note_to_members_by_customer'),
(280, 50, 'loggedin_staff', 'loggedin_staff', 'new_note_to_members_by_customer'),
(281, 51, 'project_name', 'project_name', 'new_file_uploaded_by_customer'),
(282, 51, 'project_start_date', 'project_start_date', 'new_file_uploaded_by_customer'),
(283, 51, 'project_end_date', 'project_end_date', 'new_file_uploaded_by_customer'),
(284, 51, 'project_value', 'project_value', 'new_file_uploaded_by_customer'),
(285, 51, 'project_tax', 'project_tax', 'new_file_uploaded_by_customer'),
(286, 51, 'project_url', 'project_url', 'new_file_uploaded_by_customer'),
(287, 51, 'name', 'name', 'new_file_uploaded_by_customer'),
(288, 51, 'email_signature', 'email_signature', 'new_file_uploaded_by_customer'),
(289, 51, 'loggedin_staff', 'loggedin_staff', 'new_file_uploaded_by_customer'),
(290, 34, 'project_description', 'project_description', 'project_notification'),
(291, 52, 'customer', 'customer', 'order_message'),
(292, 52, 'order_to', 'order_to', 'order_message'),
(293, 52, 'email_signature', 'email_signature', 'order_message'),
(294, 52, 'name', 'name', 'order_message'),
(295, 52, 'order_number', 'order_number', 'order_message'),
(296, 52, 'app_name', 'app_name', 'order_message'),
(297, 52, 'company_name', 'company_name', 'order_message'),
(298, 53, 'staff', 'staff', 'request_quote'),
(299, 53, 'customer_name', 'customer_name', 'request_quote'),
(300, 53, 'subject', 'subject', 'request_quote'),
(301, 53, 'details', 'details', 'request_quote'),
(302, 53, 'quote_link', 'quote_link', 'request_quote'),
(303, 53, 'company_name', 'company_name', 'request_quote'),
(304, 53, 'company_email', 'company_email', 'request_quote'),
(305, 54, 'quote_status', 'quote_status', 'quote_status_changed'),
(306, 54, 'staff', 'staff', 'quote_status_changed'),
(307, 54, 'customer_name', 'customer_name', 'quote_status_changed'),
(308, 54, 'subject', 'subject', 'quote_status_changed'),
(309, 54, 'details', 'details', 'quote_status_changed'),
(310, 54, 'quote_link', 'quote_link', 'quote_status_changed'),
(311, 54, 'company_name', 'company_name', 'quote_status_changed'),
(312, 54, 'company_email', 'company_email', 'quote_status_changed'),
(313, 12, 'company_name', 'company_name', 'new_task_assigned'),
(314, 12, 'company_email', 'company_email', 'new_task_assigned'),
(315, 12, 'task_description', 'task_description', 'new_task_assigned'),
(316, 14, 'company_name', 'company_name', 'task_attachment'),
(317, 14, 'company_email', 'company_email', 'task_attachment'),
(318, 55, 'company_name', 'company_name', 'event_reminder'),
(319, 55, 'company_email', 'company_email', 'event_reminder'),
(320, 55, 'staff', 'staff', 'event_reminder'),
(321, 55, 'event_title', 'event_title', 'event_reminder'),
(322, 55, 'event_type', 'event_type', 'event_reminder'),
(323, 55, 'event_details', 'event_details', 'event_reminder'),
(324, 55, 'staff_email', 'staff_email', 'event_reminder'),
(325, 58, 'purchase_number', 'purchase_number', 'purchase_message'),
(326, 58, 'vendor_name', 'vendor_name', 'purchase_message'),
(327, 58, 'issuance_date', 'issuance_date', 'purchase_message'),
(328, 58, 'due_date', 'due_date', 'purchase_message'),
(329, 58, 'due_note', 'due_note', 'purchase_message'),
(330, 58, 'purchase_status', 'purchase_status', 'purchase_message'),
(331, 58, 'total_amount', 'total_amount', 'purchase_message'),
(332, 58, 'company_name', 'company_name', 'purchase_message'),
(333, 58, 'company_email', 'company_email', 'purchase_message'),
(334, 58, 'purchsae_link', 'purchsae_link', 'purchase_message'),
(335, 59, 'purchase_number', 'purchase_number', 'purchase_payment'),
(336, 59, 'vendor_name', 'vendor_name', 'purchase_payment'),
(337, 59, 'issuance_date', 'issuance_date', 'purchase_payment'),
(338, 59, 'due_date', 'due_date', 'purchase_payment'),
(339, 59, 'payment_date', 'payment_date', 'purchase_payment'),
(340, 59, 'payment_account', 'payment_account', 'purchase_payment'),
(341, 59, 'payment_amount', 'payment_amount', 'purchase_payment'),
(342, 59, 'payment_description', 'payment_description', 'purchase_payment'),
(343, 59, 'payment_made_by', 'payment_made_by', 'purchase_payment'),
(344, 59, 'purchase_status', 'purchase_status', 'purchase_payment'),
(345, 59, 'purchase_link', 'purchase_link', 'purchase_payment'),
(346, 59, 'company_name', 'company_name', 'purchase_payment'),
(347, 59, 'company_email', 'company_email', 'purchase_payment'),
(348, 59, 'total_amount', 'total_amount', 'purchase_payment'),
(349, 60, 'purchase_number', 'purchase_number', 'purchase_recurring'),
(350, 60, 'vendor_name', 'vendor_name', 'purchase_recurring'),
(351, 60, 'issuance_date', 'issuance_date', 'purchase_recurring'),
(352, 60, 'due_date', 'due_date', 'purchase_recurring'),
(353, 60, 'purchase_status', 'purchase_status', 'purchase_recurring'),
(354, 60, 'purchase_link', 'purchase_link', 'purchase_recurring'),
(355, 60, 'total_amount', 'total_amount', 'purchase_recurring'),
(356, 60, 'due_note', 'due_note', 'purchase_recurring'),
(357, 60, 'company_name', 'company_name', 'purchase_recurring'),
(358, 60, 'company_email', 'company_email', 'purchase_recurring'),
(359, 61, 'deposit_number', 'deposit_number', 'deposit_message'),
(360, 61, 'customer_name', 'customer_name', 'deposit_message'),
(361, 61, 'deposit_date', 'deposit_date', 'deposit_message'),
(362, 61, 'deposit_amount', 'deposit_amount', 'deposit_message'),
(363, 61, 'deposit_status', 'deposit_status', 'deposit_message'),
(364, 61, 'deposit_link', 'deposit_link', 'deposit_message'),
(365, 61, 'company_name', 'company_name', 'deposit_message'),
(366, 61, 'company_email', 'company_email', 'deposit_message'),
(367, 62, 'deposit_number', 'deposit_number', 'recurring_deposit'),
(368, 62, 'customer_name', 'customer_name', 'recurring_deposit'),
(369, 62, 'deposit_date', 'deposit_date', 'recurring_deposit'),
(370, 62, 'deposit_amount', 'deposit_amount', 'recurring_deposit'),
(371, 62, 'deposit_status', 'deposit_status', 'recurring_deposit'),
(372, 62, 'deposit_link', 'deposit_link', 'recurring_deposit'),
(373, 62, 'company_name', 'company_name', 'recurring_deposit'),
(374, 62, 'company_email', 'company_email', 'recurring_deposit');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `added_by` int(5) DEFAULT NULL,
  `staffname` varchar(255) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `public` varchar(10) DEFAULT '0',
  `reminder` tinyint(1) DEFAULT NULL,
  `event_type` int(5) DEFAULT NULL,
  `is_all` tinyint(1) DEFAULT '0',
  `created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `detail`, `staff_id`, `added_by`, `staffname`, `start`, `end`, `color`, `public`, `reminder`, `event_type`, `is_all`, `created`) VALUES
(1, 'Holiday', 'Public Holiday on 30-06-2019', 1, 1, 'Root', '2019-06-30 16:10:00', '2019-07-06 16:10:00', 'red', '1', 0, 1, 0, '2019-06-03 07:00:52');

-- --------------------------------------------------------

--
-- Table structure for table `event_triggers`
--

CREATE TABLE `event_triggers` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(100) DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `duration_type` int(11) DEFAULT NULL COMMENT '0 = minutes | 1 = hours | 2 = days | 3 = weeks',
  `duration_period` varchar(100) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `status` tinyint(4) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `public` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `name`, `color`, `public`) VALUES
(1, 'Public Event', 'red', 1);

-- --------------------------------------------------------

--
-- Table structure for table `expensecat`
--

CREATE TABLE `expensecat` (
  `id` int(11) NOT NULL,
  `name` varchar(300) NOT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `expense_number` varchar(100) DEFAULT NULL,
  `number` varchar(100) DEFAULT NULL,
  `hash` varchar(6) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT '0',
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `category_id` int(5) DEFAULT NULL,
  `account_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `purchase_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(20,2) NOT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `internal` tinyint(1) NOT NULL DEFAULT '0',
  `recurring` int(5) DEFAULT NULL,
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0',
  `expense_created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `is_old` tinyint(1) DEFAULT '1',
  `filetype` varchar(50) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `invoiceId` varchar(10) DEFAULT NULL,
  `token` varchar(100) NOT NULL,
  `no` varchar(50) DEFAULT NULL,
  `serie` varchar(100) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `customer_id` int(5) NOT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `proposal_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `staff_id` int(5) DEFAULT NULL,
  `datesend` datetime DEFAULT NULL,
  `datepayment` date DEFAULT NULL,
  `duenote` varchar(255) DEFAULT NULL,
  `status_id` int(5) DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `amount_paying` varchar(50) DEFAULT NULL,
  `default_payment_method` varchar(50) DEFAULT NULL,
  `CustomField` varchar(255) NOT NULL,
  `billing_street` varchar(100) NOT NULL,
  `billing_city` varchar(100) NOT NULL,
  `billing_state` varchar(100) NOT NULL,
  `billing_state_id` int(5) DEFAULT NULL,
  `billing_zip` varchar(50) NOT NULL,
  `billing_country` int(5) NOT NULL,
  `shipping_street` varchar(100) NOT NULL,
  `shipping_city` varchar(100) NOT NULL,
  `shipping_state` varchar(100) NOT NULL,
  `shipping_state_id` int(5) DEFAULT NULL,
  `shipping_zip` varchar(100) NOT NULL,
  `shipping_country` int(5) NOT NULL,
  `recurring` int(2) NOT NULL DEFAULT '0',
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `invoicestatus`
--

CREATE TABLE `invoicestatus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `invoicestatus`
--

INSERT INTO `invoicestatus` (`id`, `name`, `color`) VALUES
(1, 'Draft', '#7d7d7d'),
(2, 'Paid', '#26c281'),
(3, 'Unpaid', '#ff3b30'),
(4, 'Cancelled', '#dd2c00');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `product_id` int(5) DEFAULT NULL,
  `code` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `quantity` decimal(20,2) DEFAULT NULL,
  `unit` varchar(200) DEFAULT NULL,
  `price` decimal(20,2) DEFAULT NULL,
  `tax` decimal(20,2) DEFAULT '0.00',
  `discount` decimal(20,2) DEFAULT '0.00',
  `total` decimal(20,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `langcode` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `foldername` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES
(1, 'de_De', 'german', 'german'),
(2, 'en_US', 'english', 'english'),
(3, 'es_ES', 'spanish', 'spanish'),
(4, 'fr_FR', 'french', 'french'),
(5, 'pt-pt', 'portuguese_pt', 'portuguese_pt'),
(6, 'pt-BR', 'portuguese_br', 'portuguese_br'),
(7, 'tr_TR', 'turkish', 'turkish'),
(8, 'ru_RU', 'russian', 'russian'),
(9, 'sv_SV', 'swedish', 'swedish'),
(10, 'it-ch', 'italian', 'italian');

-- --------------------------------------------------------

--
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` int(11) NOT NULL,
  `lead_number` varchar(100) DEFAULT NULL,
  `date_contacted` datetime NOT NULL,
  `type` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `description` text,
  `country_id` int(11) NOT NULL DEFAULT '0',
  `zip` varchar(15) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `assigned_id` int(5) NOT NULL,
  `created` date NOT NULL,
  `status` int(5) NOT NULL,
  `source` int(5) NOT NULL,
  `lastcontact` datetime DEFAULT NULL,
  `dateassigned` date DEFAULT NULL,
  `staff_id` int(5) NOT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `lost` tinyint(1) DEFAULT '0',
  `junk` int(5) DEFAULT '0',
  `public` tinyint(1) DEFAULT '0',
  `weblead` int(5) DEFAULT NULL,
  `lead_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `leadssources`
--

CREATE TABLE `leadssources` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadssources`
--

INSERT INTO `leadssources` (`id`, `name`) VALUES
(1, 'WEB'),
(2, 'EMAIL'),
(6, 'TELEPHONE'),
(7, 'SOCIAL MEDIA');

-- --------------------------------------------------------

--
-- Table structure for table `leadsstatus`
--

CREATE TABLE `leadsstatus` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) DEFAULT '#28B8DA'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leadsstatus`
--

INSERT INTO `leadsstatus` (`id`, `name`, `color`) VALUES
(1, 'NEW', NULL),
(2, 'CONTACTED', '#fff3d1'),
(3, 'INPROGRESS', '#ffdc77'),
(4, 'CONVERTED', '#daf196'),
(5, 'LOST', 'pink');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `detail` mediumtext NOT NULL,
  `date` datetime NOT NULL,
  `staff_id` varchar(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `project_id` int(5) NOT NULL,
  `vendor_id` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `detail`, `date`, `staff_id`, `customer_id`, `project_id`, `vendor_id`) VALUES
(1, '<a href=\"staff/staffmember/1\"> Root</a> logged in the system', '2019-04-18 03:09:32', '1', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meetings`
--

CREATE TABLE `meetings` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `customer_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `date` date NOT NULL,
  `start` time NOT NULL,
  `end` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL,
  `order_id` int(5) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `main_menu` int(5) DEFAULT '0',
  `icon` varchar(100) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL,
  `show_staff` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `order_id`, `name`, `description`, `main_menu`, `icon`, `url`, `show_staff`) VALUES
(1, 1, 'x_menu_panel', NULL, 0, NULL, 'panel', 0),
(2, 2, 'x_menu_finance', NULL, 0, NULL, NULL, 0),
(3, 3, 'x_menu_customers_and_lead', NULL, 0, NULL, NULL, 0),
(4, 4, 'x_menu_track', NULL, 0, NULL, NULL, 0),
(5, 5, 'x_menu_others', NULL, 0, NULL, NULL, 1),
(6, 6, 'x_menu_purchases', NULL, 0, NULL, NULL, 0),
(7, 7, 'x_menu_modules', NULL, 0, NULL, NULL, 0),
(8, 1, 'x_menu_invoices', 'manage_invoices', 2, 'ico-ciuis-invoices', 'invoices', 0),
(9, 2, 'x_menu_deposits', 'manage_deposits', 2, 'ion-ios-paper-outline', 'deposits', 0),
(10, 3, 'x_menu_expenses', 'manage_expenses', 2, 'ico-ciuis-expenses', 'expenses', 0),
(11, 4, 'x_menu_accounts', 'manage_accounts', 2, 'ico-ciuis-accounts', 'accounts', 0),
(12, 1, 'x_menu_orders', 'manage_orders', 3, 'ion-ios-filing-outline', 'orders', 0),
(13, 2, 'x_menu_proposals', 'manage_proposals', 3, 'ico-ciuis-proposals', 'proposals', 0),
(14, 3, 'x_menu_customers', 'manage_customers', 3, 'ico-ciuis-customers', 'customers', 0),
(15, 4, 'x_menu_leads', 'manage_leads', 3, 'ico-ciuis-leads', 'leads', 0),
(16, 5, 'x_menu_products', 'manage_products', 3, 'ico-ciuis-products', 'products', 0),
(17, 1, 'x_menu_projects', 'manage_projects', 4, 'ico-ciuis-projects', 'projects', 0),
(18, 2, 'x_menu_tasks', 'manage_tasks', 4, 'ico-ciuis-tasks', 'tasks', 0),
(19, 3, 'x_menu_timesheets', 'manage_timesheets', 4, 'ion-ios-clock-outline', 'timesheets', 0),
(20, 4, 'x_menu_tickets', 'manage_tickets', 4, 'ico-ciuis-supports', 'tickets', 0),
(21, 4, 'x_menu_reports', 'manage_reports', 5, 'ico-ciuis-reports', 'report', 0),
(22, 1, 'x_menu_staff', 'manage_staff', 5, 'ico-ciuis-staff', 'staff', 0),
(23, 2, 'x_menu_calendar', 'manage_calendar', 5, 'ion-android-calendar', 'calendar', 0),
(24, 3, 'x_menu_emails', 'manage_emails', 5, 'ion-ios-email-outline', 'emails', 0),
(25, 1, 'x_menu_vendors', 'manage_vendor', 6, 'ion-social-buffer-outline', 'vendors', 0),
(26, 2, 'x_menu_purchases', 'manage_purchases', 6, 'ion-ios-cart-outline', 'purchases', 0);

-- --------------------------------------------------------

--
-- Table structure for table `milestones`
--

CREATE TABLE `milestones` (
  `id` int(11) NOT NULL,
  `order` int(5) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL,
  `description` text,
  `duedate` date NOT NULL,
  `project_id` int(5) NOT NULL,
  `color` varchar(10) DEFAULT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `license` varchar(255) DEFAULT NULL,
  `updatedat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL,
  `description` text,
  `addedfrom` int(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `target` varchar(255) NOT NULL,
  `markread` tinyint(2) NOT NULL DEFAULT '0',
  `customerread` int(5) NOT NULL,
  `date` datetime NOT NULL,
  `detail` text NOT NULL,
  `staff_id` int(5) NOT NULL,
  `contact_id` int(5) NOT NULL,
  `customer_id` int(5) NOT NULL,
  `vendor_id` int(5) NOT NULL,
  `public` int(5) DEFAULT NULL,
  `perres` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_number` varchar(100) DEFAULT NULL,
  `token` mediumtext NOT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `content` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `addedfrom` int(5) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(5) DEFAULT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `invoice_id` int(5) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transactiontype` int(5) NOT NULL,
  `is_transfer` int(5) DEFAULT NULL,
  `deposit_id` int(5) DEFAULT NULL,
  `invoice_id` int(5) DEFAULT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `purchase_id` int(5) DEFAULT NULL,
  `customer_id` int(5) DEFAULT NULL,
  `vendor_id` int(5) DEFAULT NULL,
  `amount` decimal(11,2) NOT NULL,
  `account_id` int(5) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  `not` varchar(255) NOT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `staff_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `input_label1` varchar(255) DEFAULT NULL,
  `input_label2` varchar(255) DEFAULT NULL,
  `input_label3` varchar(255) DEFAULT NULL,
  `active` varchar(1) DEFAULT '0',
  `input_value1` varchar(255) DEFAULT NULL,
  `input_value2` varchar(255) DEFAULT NULL,
  `input_value3` varchar(255) DEFAULT NULL,
  `sandbox_account` varchar(1) DEFAULT '0',
  `payment_record_account` varchar(11) DEFAULT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `gateway_note` varchar(255) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `image`, `input_label1`, `input_label2`, `input_label3`, `active`, `input_value1`, `input_value2`, `input_value3`, `sandbox_account`, `payment_record_account`, `relation`, `gateway_note`, `updated_at`) VALUES
(1, 'PayPal', 'paypal.png', 'paypalemail', 'paypalcurrency', NULL, '0', '', '', '', '1', '', 'paypal', NULL, '2019-08-07 04:03:22'),
(2, 'CCAvenue', 'ccavenue.png', 'ccavenue_marchent_key', 'ccavenue_working_key', 'ccavenue_access_code', '0', '', '', '', '1', '', 'ccavenue', 'CCAvenue Gateway uses mcrypt encryption. \r\n Please make sure you\'re using PHP 7.0 or Older and mcrypt extension is enabled.', '2019-08-07 04:22:31'),
(3, 'PayUmoney', 'payumoney.png', 'payu_money_key', 'payu_money_salt', NULL, '0', '', '', '', '1', '', 'payumoney', NULL, '2019-08-07 06:04:23'),
(4, 'Stripe', 'stripe.png', 'stripe_api_secret_key', 'stripe_public_key', NULL, '0', '', '', '', '1', '', 'stripe', NULL, '2019-08-07 04:09:00'),
(5, 'Authorize.net AIM', 'authorize.png', 'authorize_aim_api_login_id', 'authorize_aim_api_transaction_key', NULL, '0', '', '', NULL, '1', '', 'authorize', NULL, '2019-08-08 00:00:00'),
(6, 'Razorpay', 'razorpay.png', 'key_id', 'razorpay_key_secret', NULL, '0', '', '', '', '1', '0', 'razorpay', NULL, '2019-08-07 05:35:40'),
(7, 'Bank', 'bank.png', NULL, NULL, NULL, '0', '', '', '', '0', '1', 'bank', NULL, '2019-08-07 02:15:40');

-- --------------------------------------------------------

--
-- Table structure for table `payment_modes`
--

CREATE TABLE `payment_modes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `payment_modes`
--

INSERT INTO `payment_modes` (`id`, `name`, `value`) VALUES
(1, 'authorize_aim_active', '0'),
(2, 'authorize_aim_api_login_id', ''),
(3, 'authorize_aim_api_transaction_key', ''),
(4, 'authorize_aim_payment_record_account', ''),
(5, 'paypal_active', '0'),
(6, 'paypal_username', ''),
(7, 'paypal_currency', ''),
(8, 'paypal_test_mode_enabled', '1'),
(9, 'paypal_payment_record_account', ''),
(10, 'stripe_active', '0'),
(11, 'stripe_api_secret_key', ''),
(12, 'stripe_api_publishable_key', ''),
(13, 'payu_money_active', '0'),
(14, 'payu_money_key', ''),
(15, 'payu_money_salt', ''),
(16, 'payu_money_test_mode_enabled', '1'),
(17, 'stripe_record_account', ''),
(18, 'ccavenue_record_account', ''),
(19, 'ccavenue_active', '0'),
(20, 'ccavenue_marchent_key', ''),
(21, 'ccavenue_working_key', ''),
(22, 'ccavenue_access_code', ''),
(23, 'ccavenue_test_mode', '1'),
(24, 'payu_money_record_account', ''),
(25, 'stripe_test_mode_enabled', '1'),
(26, 'razorpay_active', '0'),
(27, 'razorpay_key', ''),
(28, 'razorpay_test_mode_enabled', '1'),
(29, 'razorpay_payment_record_account', '0'),
(30, 'primary_bank_account', '1'),
(31, 'authorize_test_mode_enabled', '1'),
(32, 'razorpay_key_secret', '');

-- --------------------------------------------------------

--
-- Table structure for table `pending_process`
--

CREATE TABLE `pending_process` (
  `process_id` int(11) NOT NULL,
  `process_type` varchar(100) DEFAULT 'pdf',
  `process_relation` int(5) DEFAULT NULL,
  `process_relation_type` varchar(100) DEFAULT NULL,
  `process_template_name` varchar(200) DEFAULT NULL,
  `process_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `key` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission`, `type`, `key`) VALUES
(1, 'x_menu_invoices', 'common', 'invoices'),
(2, 'x_menu_proposals', 'common', 'proposals'),
(3, 'x_menu_expenses', 'non-common', 'expenses'),
(4, 'x_menu_accounts', 'non-common', 'accounts'),
(5, 'x_menu_customers', 'non-common', 'customers'),
(6, 'x_menu_leads', 'non-common', 'leads'),
(7, 'x_menu_projects', 'common', 'projects'),
(8, 'x_menu_tasks', 'non-common', 'tasks'),
(9, 'x_menu_tickets', 'common', 'tickets'),
(10, 'x_menu_products', 'non-common', 'products'),
(11, 'x_menu_staff', 'non-common', 'staff'),
(12, 'x_menu_reports', 'non-common', 'report'),
(13, 'x_menu_orders', 'non-common', 'orders'),
(14, 'x_menu_emails', 'non-common', 'emails'),
(15, 'x_menu_timesheets', 'non-common', 'timesheets'),
(16, 'x_menu_quotes', 'common', 'quotes'),
(17, 'x_menu_vendor', 'non-common', 'vendors'),
(18, 'x_menu_purchases', 'non-common', 'purchases'),
(19, 'x_menu_deposits', 'non-common', 'deposits'),
(20, 'x_menu_settings', 'non-common', 'settings');

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `relation` int(5) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `permission_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`relation`, `relation_type`, `permission_id`) VALUES
(1, 'staff', 1),
(1, 'staff', 3),
(1, 'staff', 2),
(1, 'staff', 4),
(1, 'staff', 5),
(1, 'staff', 6),
(1, 'staff', 7),
(1, 'staff', 8),
(1, 'staff', 9),
(1, 'staff', 10),
(1, 'staff', 11),
(1, 'staff', 12),
(1, 'staff', 13),
(1, 'staff', 14),
(1, 'staff', 15),
(1, 'staff', 17),
(1, 'staff', 18);

-- --------------------------------------------------------

--
-- Table structure for table `productcategories`
--

CREATE TABLE `productcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_number` varchar(100) DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `productname` varchar(255) DEFAULT NULL,
  `categoryid` int(11) NOT NULL,
  `description` text NOT NULL,
  `productimage` varchar(255) DEFAULT NULL,
  `purchase_price` decimal(20,2) DEFAULT NULL,
  `sale_price` decimal(20,2) DEFAULT NULL,
  `stock` bigint(20) DEFAULT NULL,
  `vat` decimal(20,2) DEFAULT NULL,
  `status_id` enum('0','1') DEFAULT NULL,
  `product_created_by` int(5) DEFAULT '0',
  `createdat` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `projectmembers`
--

CREATE TABLE `projectmembers` (
  `id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_number` varchar(100) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `projectvalue` decimal(20,2) DEFAULT '0.00',
  `tax` decimal(20,2) DEFAULT '0.00',
  `status_id` int(5) NOT NULL DEFAULT '0',
  `customer_id` int(5) NOT NULL,
  `invoice_id` int(5) NOT NULL,
  `start_date` date NOT NULL,
  `deadline` date DEFAULT NULL,
  `created` date NOT NULL,
  `finished` datetime DEFAULT NULL,
  `pinned` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `template` tinyint(1) DEFAULT '0',
  `pdf_report` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `projectservices`
--

CREATE TABLE `projectservices` (
  `id` int(11) NOT NULL,
  `projectid` int(5) DEFAULT NULL,
  `categoryid` int(5) DEFAULT NULL,
  `categoryname` varchar(255) DEFAULT NULL,
  `productid` int(5) DEFAULT NULL,
  `servicename` varchar(255) DEFAULT NULL,
  `serviceprice` decimal(20,2) DEFAULT NULL,
  `quantity` varchar(20) DEFAULT '1',
  `unit` varchar(100) DEFAULT 'Unit',
  `servicetax` decimal(20,2) DEFAULT NULL,
  `servicedescription` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `proposal_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `subject` varchar(300) DEFAULT NULL,
  `content` longtext,
  `customer_quote` longtext,
  `date` date NOT NULL,
  `created` date NOT NULL,
  `opentill` date DEFAULT NULL,
  `relation_type` varchar(100) DEFAULT NULL,
  `relation` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `addedfrom` int(5) NOT NULL,
  `datesend` datetime DEFAULT NULL,
  `comment` int(5) DEFAULT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `invoice_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `dateconverted` datetime DEFAULT NULL,
  `sub_total` decimal(20,2) DEFAULT NULL,
  `total_discount` decimal(20,2) DEFAULT NULL,
  `total_tax` decimal(20,2) DEFAULT NULL,
  `total` decimal(20,2) DEFAULT NULL,
  `is_requested` tinyint(1) DEFAULT '0',
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` int(11) NOT NULL,
  `purchase_number` varchar(100) DEFAULT NULL,
  `token` varchar(200) NOT NULL,
  `no` int(11) DEFAULT NULL,
  `serie` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL,
  `duedate` date DEFAULT NULL,
  `vendor_id` int(5) NOT NULL,
  `expense_id` int(5) DEFAULT NULL,
  `proposal_id` int(5) DEFAULT NULL,
  `project_id` int(5) DEFAULT NULL,
  `staff_id` int(5) DEFAULT NULL,
  `datesend` datetime DEFAULT NULL,
  `datepayment` date DEFAULT NULL,
  `duenote` text,
  `status_id` int(5) DEFAULT NULL,
  `sub_total` decimal(11,2) DEFAULT NULL,
  `total_discount` decimal(11,2) DEFAULT NULL,
  `total_tax` decimal(11,2) DEFAULT NULL,
  `total` decimal(11,2) DEFAULT NULL,
  `recurring` int(5) NOT NULL DEFAULT '0',
  `last_recurring` date DEFAULT NULL,
  `pdf_status` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `recurring`
--

CREATE TABLE `recurring` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(100) NOT NULL,
  `relation` int(5) NOT NULL DEFAULT '0',
  `period` int(5) NOT NULL DEFAULT '0',
  `type` int(5) NOT NULL DEFAULT '0' COMMENT '0 = day | 1 = week | 2 = month | 3 = years',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_date` varchar(100) DEFAULT 'Invalid date',
  `status` int(5) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `description` text,
  `date` datetime NOT NULL,
  `isnotified` int(11) NOT NULL DEFAULT '0',
  `addedfrom` int(11) NOT NULL,
  `public` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_type` varchar(10) NOT NULL,
  `role_createdat` datetime DEFAULT NULL,
  `role_updatedat` datetime DEFAULT NULL,
  `created_by` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_type`, `role_createdat`, `role_updatedat`, `created_by`) VALUES
(1, 'Admin Role', 'admin', '2019-08-09 08:04:18', '2019-08-16 07:13:48', 1),
(2, 'Staff Role', 'staff', '2019-08-16 09:32:37', '2019-08-16 09:32:37', 1),
(3, 'VAT Consultant', 'other', '2019-08-16 09:32:57', '2019-08-16 09:32:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_permission_id` int(5) NOT NULL,
  `permission_id` int(5) NOT NULL,
  `permission_view_own` tinyint(1) NOT NULL,
  `permission_view_all` tinyint(1) NOT NULL,
  `permission_edit` tinyint(1) NOT NULL,
  `permission_delete` tinyint(1) NOT NULL,
  `permission_create` tinyint(1) NOT NULL,
  `role_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `permission_id`, `permission_view_own`, `permission_view_all`, `permission_edit`, `permission_delete`, `permission_create`, `role_id`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 1, 1, 1, 1, 1, 1),
(3, 3, 1, 1, 1, 1, 1, 1),
(4, 4, 1, 1, 1, 1, 1, 1),
(5, 5, 1, 1, 1, 1, 1, 1),
(6, 6, 1, 1, 1, 1, 1, 1),
(7, 7, 1, 0, 1, 1, 1, 1),
(8, 8, 1, 1, 1, 1, 1, 1),
(9, 9, 1, 1, 1, 1, 1, 1),
(10, 10, 1, 1, 1, 1, 1, 1),
(11, 11, 1, 1, 1, 1, 1, 1),
(12, 12, 0, 1, 0, 0, 0, 1),
(13, 13, 1, 1, 1, 1, 1, 1),
(14, 14, 0, 1, 1, 0, 0, 1),
(15, 15, 1, 1, 1, 1, 1, 1),
(16, 16, 0, 0, 0, 0, 0, 1),
(17, 17, 1, 1, 1, 1, 1, 1),
(18, 18, 1, 1, 1, 1, 1, 1),
(19, 19, 1, 1, 1, 1, 1, 1),
(20, 20, 1, 1, 1, 1, 1, 1),
(21, 1, 1, 1, 1, 1, 1, 2),
(22, 2, 1, 1, 1, 1, 1, 2),
(23, 3, 1, 1, 1, 1, 1, 2),
(24, 4, 1, 1, 1, 1, 1, 2),
(25, 5, 1, 1, 1, 1, 1, 2),
(26, 6, 1, 1, 1, 1, 1, 2),
(27, 7, 1, 1, 1, 1, 1, 2),
(28, 8, 1, 1, 1, 1, 1, 2),
(29, 9, 1, 1, 1, 1, 1, 2),
(30, 10, 1, 1, 1, 1, 1, 2),
(31, 11, 1, 1, 1, 1, 1, 2),
(32, 12, 1, 1, 1, 1, 1, 2),
(33, 13, 1, 1, 1, 1, 1, 2),
(34, 14, 1, 1, 1, 1, 1, 2),
(35, 15, 1, 1, 1, 1, 1, 2),
(36, 16, 1, 1, 1, 1, 1, 2),
(37, 17, 1, 1, 1, 1, 1, 2),
(38, 18, 1, 1, 1, 1, 1, 2),
(39, 19, 1, 1, 1, 1, 1, 2),
(40, 1, 1, 1, 1, 1, 1, 3),
(41, 3, 1, 1, 1, 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `settingname` varchar(255) NOT NULL,
  `crm_name` varchar(255) NOT NULL DEFAULT 'CiuisCRM',
  `company` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `state_id` int(5) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) NOT NULL,
  `taxoffice` varchar(100) DEFAULT NULL,
  `vatnumber` varchar(100) DEFAULT NULL,
  `unitseparator` varchar(50) NOT NULL DEFAULT '.',
  `currencyid` int(5) NOT NULL,
  `currencyposition` varchar(50) NOT NULL,
  `termtitle` varchar(255) NOT NULL,
  `termdescription` varchar(255) NOT NULL,
  `dateformat` varchar(100) DEFAULT NULL,
  `default_timezone` varchar(100) NOT NULL,
  `languageid` varchar(100) NOT NULL,
  `smtphost` varchar(255) DEFAULT NULL,
  `smtpport` varchar(255) DEFAULT NULL,
  `emailcharset` varchar(255) DEFAULT NULL,
  `email_encryption` int(5) NOT NULL,
  `smtpusername` varchar(255) DEFAULT NULL,
  `smtppassoword` varchar(255) DEFAULT NULL,
  `sendermail` varchar(255) DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `app_logo` varchar(255) DEFAULT NULL,
  `accepted_files_formats` varchar(255) NOT NULL DEFAULT 'jpg,jpeg,doc,png,txt,docx',
  `allowed_ip_adresses` varchar(255) DEFAULT NULL,
  `pushState` int(5) NOT NULL,
  `voicenotification` int(5) NOT NULL,
  `converted_lead_status_id` int(5) DEFAULT NULL,
  `two_factor_authentication` int(5) DEFAULT NULL,
  `is_demo` tinyint(1) DEFAULT '0',
  `is_mysql` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`settingname`, `crm_name`, `company`, `email`, `address`, `country_id`, `state`, `state_id`, `city`, `town`, `zipcode`, `phone`, `fax`, `taxoffice`, `vatnumber`, `unitseparator`, `currencyid`, `currencyposition`, `termtitle`, `termdescription`, `dateformat`, `default_timezone`, `languageid`, `smtphost`, `smtpport`, `emailcharset`, `email_encryption`, `smtpusername`, `smtppassoword`, `sendermail`, `sender_name`, `logo`, `app_logo`, `accepted_files_formats`, `allowed_ip_adresses`, `pushState`, `voicenotification`, `converted_lead_status_id`, `two_factor_authentication`, `is_demo`, `is_mysql`) VALUES
('ciuis', 'CiuisCRM', 'Acme Business INC', 'info@businessaddress.com', 'P.O. Box 929 4189 Nunc RoadLebanon KY 69409', 236, 'DC', NULL, 'New York', 'New Jersey', '34400', '+1 (389) 737-2852', '+1 (389) 737-2852', 'New York Tax Office', '4530685631', '.', 159, 'before', 'Terms & Conditions', 'Maecenas facilisis ultrices purus non vehicula. Nulla dignissim enim a libero tincidunt, consequat molestie nisi mattis. Phasellus scelerisque fringilla lectus vel tempor.', 'dd.mm.yy', 'America/New_York', 'english', '', '587', 'utf-8', 0, '', '', '', '', 'ciuis-icon.png', 'ciuis-icon.png', 'zip|rar|tar|gif|jpg|png|jpeg|pdf|doc|docx|xls|xlsx|mp4|txt|csv|ppt|opt', '', 0, 1, 4, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `staff_number` varchar(100) DEFAULT NULL,
  `role_id` int(5) NOT NULL,
  `language` varchar(100) NOT NULL,
  `staffname` varchar(255) DEFAULT NULL,
  `staffavatar` varchar(300) DEFAULT 'n-img.jpg',
  `department_id` int(5) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `root` tinyint(1) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  `staffmember` tinyint(1) DEFAULT NULL,
  `other` tinyint(1) DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `timezone` varchar(200) DEFAULT NULL,
  `appointment_availability` int(5) NOT NULL,
  `inactive` tinyint(1) DEFAULT NULL,
  `google_calendar_enable` int(5) NOT NULL,
  `google_calendar_id` varchar(255) NOT NULL,
  `google_calendar_api_key` varchar(255) NOT NULL,
  `createdat` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `staff_number`, `role_id`, `language`, `staffname`, `staffavatar`, `department_id`, `phone`, `address`, `email`, `birthday`, `password`, `root`, `admin`, `staffmember`, `other`, `last_login`, `timezone`, `appointment_availability`, `inactive`, `google_calendar_enable`, `google_calendar_id`, `google_calendar_api_key`, `createdat`, `updatedAt`) VALUES
(1, NULL, 1, 'english', 'Root', 'pm.jpg', 1, '+1-202-555-0160', '71 Pilgrim Avenue Chevy Chase, MD 20815', 'root@ciuis.com', '1992-12-05', '63a9f0ea7bb98050796b649e85481845', NULL, 1, NULL, NULL, '2017-08-05 03:02:42', NULL, 1, NULL, 0, 'root@ciuis.com', 'AIzaSyA1sWdokA3dVTzk7gNN58NztVw3kbPhJX8', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_work_plan`
--

CREATE TABLE `staff_work_plan` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `work_plan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `staff_work_plan`
--

INSERT INTO `staff_work_plan` (`id`, `staff_id`, `work_plan`) VALUES
(1, 1, '[{\"day\":\"monday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:360\"},{\"day\":\"tuesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:361\"},{\"day\":\"wednesday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:362\"},{\"day\":\"thursday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:363\"},{\"day\":\"friday\",\"status\":true,\"start\":\"09:00\",\"end\":\"18:00\",\"breaks\":{\"start\":\"14:30\",\"end\":\"15:00\"},\"$$hashKey\":\"object:364\"},{\"day\":\"saturday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:365\"},{\"day\":\"sunday\",\"status\":false,\"start\":\"\",\"end\":\"\",\"breaks\":{\"start\":\"\",\"end\":\"\"},\"$$hashKey\":\"object:366\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `subtasks`
--

CREATE TABLE `subtasks` (
  `id` int(11) NOT NULL,
  `taskid` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `finished` int(11) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `staff_id` int(11) NOT NULL,
  `complete` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `table_columns`
--

CREATE TABLE `table_columns` (
  `id` int(11) NOT NULL,
  `relation` varchar(255) DEFAULT NULL,
  `table_column` varchar(255) DEFAULT NULL,
  `display` tinyint(1) DEFAULT '1',
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `table_columns`
--

INSERT INTO `table_columns` (`id`, `relation`, `table_column`, `display`, `updated_at`) VALUES
(1, 'projects', 'name', 1, '2019-07-30 08:02:20'),
(2, 'projects', 'startdate', 1, '2019-08-06 03:13:11'),
(3, 'projects', 'customer', 1, '2019-08-06 03:13:11'),
(4, 'projects', 'deadline', 1, '2019-08-06 03:13:12'),
(5, 'projects', 'value', 1, '2019-08-06 03:13:14'),
(6, 'projects', 'status', 0, '2019-08-06 03:13:29'),
(7, 'projects', 'members', 0, '2019-08-06 03:13:19'),
(8, 'projects', 'actions', 1, '2019-08-06 03:13:13'),
(9, 'projects', 'list_view', 1, '2019-08-06 03:13:06');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `relation_type` varchar(255) NOT NULL,
  `relation` int(11) NOT NULL,
  `data` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `task_number` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `priority` int(5) DEFAULT NULL,
  `assigned` int(5) DEFAULT NULL,
  `created` datetime NOT NULL,
  `startdate` date NOT NULL,
  `duedate` date DEFAULT NULL,
  `datefinished` datetime NOT NULL,
  `addedfrom` int(5) NOT NULL,
  `status_id` int(5) NOT NULL DEFAULT '0',
  `relation` int(5) DEFAULT NULL,
  `relation_type` varchar(30) DEFAULT NULL,
  `public` tinyint(1) DEFAULT '0',
  `billable` tinyint(1) DEFAULT '0',
  `billed` tinyint(1) DEFAULT '0',
  `hourly_rate` decimal(20,2) DEFAULT '0.00',
  `milestone` int(5) DEFAULT '0',
  `visible` tinyint(1) DEFAULT '0',
  `timer` int(5) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tasktimer`
--

CREATE TABLE `tasktimer` (
  `id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `task_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `start` varchar(64) DEFAULT NULL,
  `end` varchar(64) DEFAULT NULL,
  `timed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `note` text,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ticketreplies`
--

CREATE TABLE `ticketreplies` (
  `id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `contact_id` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `message` text,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `ticket_number` varchar(100) DEFAULT NULL,
  `contact_id` int(5) NOT NULL DEFAULT '0',
  `customer_id` int(5) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `department_id` int(5) NOT NULL,
  `priority` enum('1','2','3') NOT NULL,
  `status_id` enum('1','2','3','4') NOT NULL,
  `relation` varchar(100) DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `subject` varchar(300) NOT NULL,
  `message` text,
  `date` datetime NOT NULL,
  `lastreply` datetime DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `staff_id` int(5) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `todo`
--

CREATE TABLE `todo` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `staff_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `done` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `todo`
--

INSERT INTO `todo` (`id`, `description`, `staff_id`, `date`, `done`) VALUES
(6, 'Donec volutpat massa id justo lacinia, quis cursus lorem consectetur.', 2, '2017-08-25 21:19:09', 1),
(7, 'Cras felis elit, vehicula id consectetur eu, cursus vel elit.', 2, '2017-08-25 21:19:17', 1),
(8, 'Vestibulum dolor felis, porta id auctor sollicitudin', 2, '2017-08-25 21:19:27', 0),
(9, 'Maecenas vel ultrices justo, nec consequat ipsum.', 2, '2017-08-25 21:19:35', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `user_id` int(10) NOT NULL,
  `contact_id` int(10) NOT NULL,
  `created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `token`, `user_id`, `contact_id`, `created`) VALUES
(1, '46e684b53f71f476b8f152f0cf41ae', 0, 23, '2018-09-26');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_number` varchar(100) DEFAULT NULL,
  `type` int(5) DEFAULT NULL,
  `created` date NOT NULL,
  `staff_id` int(5) NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `namesurname` varchar(255) DEFAULT NULL,
  `groupid` int(5) DEFAULT NULL,
  `taxoffice` varchar(255) DEFAULT NULL,
  `taxnumber` varchar(50) DEFAULT NULL,
  `ssn` varchar(255) DEFAULT NULL,
  `executive` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `zipcode` varchar(50) DEFAULT NULL,
  `country_id` int(5) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `town` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `web` varchar(255) DEFAULT NULL,
  `risk` int(5) DEFAULT '0',
  `vendor_status_id` int(5) DEFAULT '1' COMMENT '0 = Inactive | 1 = Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vendors_groups`
--

CREATE TABLE `vendors_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_sales`
--

CREATE TABLE `vendor_sales` (
  `id` int(11) NOT NULL,
  `purchase_id` int(5) NOT NULL,
  `status_id` int(5) NOT NULL,
  `vendor_id` int(5) NOT NULL,
  `staff_id` int(5) NOT NULL,
  `total` decimal(11,2) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `id` int(11) NOT NULL,
  `versions_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_version` varchar(255) DEFAULT NULL,
  `last_updated` date DEFAULT NULL,
  `is_update_available` tinyint(1) DEFAULT '0',
  `last_checked` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`id`, `versions_name`, `created_at`, `last_version`, `last_updated`, `is_update_available`, `last_checked`) VALUES
(1, '1.94', '2019-02-26 15:09:08', '1.7', '2019-06-04', 0, '2019-04-18');

-- --------------------------------------------------------

--
-- Table structure for table `webleads`
--

CREATE TABLE `webleads` (
  `id` int(11) NOT NULL,
  `token` text NOT NULL,
  `lead_source` int(5) NOT NULL,
  `lead_status` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `submit_text` varchar(255) NOT NULL,
  `success_message` mediumtext NOT NULL,
  `notification` tinyint(1) NOT NULL DEFAULT '1',
  `duplicate` tinyint(1) NOT NULL DEFAULT '1',
  `assigned_id` int(5) NOT NULL,
  `form_data` longtext NOT NULL,
  `created` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appconfig`
--
ALTER TABLE `appconfig`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branding`
--
ALTER TABLE `branding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customergroups`
--
ALTER TABLE `customergroups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields`
--
ALTER TABLE `custom_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `db_backup`
--
ALTER TABLE `db_backup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `depositcat`
--
ALTER TABLE `depositcat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_templates`
--
ALTER TABLE `email_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_triggers`
--
ALTER TABLE `event_triggers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expensecat`
--
ALTER TABLE `expensecat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadssources`
--
ALTER TABLE `leadssources`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `milestones`
--
ALTER TABLE `milestones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_modes`
--
ALTER TABLE `payment_modes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pending_process`
--
ALTER TABLE `pending_process`
  ADD PRIMARY KEY (`process_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `productcategories`
--
ALTER TABLE `productcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectmembers`
--
ALTER TABLE `projectmembers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projectservices`
--
ALTER TABLE `projectservices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recurring`
--
ALTER TABLE `recurring`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_permission_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`settingname`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subtasks`
--
ALTER TABLE `subtasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_columns`
--
ALTER TABLE `table_columns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tasktimer`
--
ALTER TABLE `tasktimer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo`
--
ALTER TABLE `todo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors_groups`
--
ALTER TABLE `vendors_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendor_sales`
--
ALTER TABLE `vendor_sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `webleads`
--
ALTER TABLE `webleads`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appconfig`
--
ALTER TABLE `appconfig`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branding`
--
ALTER TABLE `branding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customergroups`
--
ALTER TABLE `customergroups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields`
--
ALTER TABLE `custom_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_fields_data`
--
ALTER TABLE `custom_fields_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `db_backup`
--
ALTER TABLE `db_backup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `depositcat`
--
ALTER TABLE `depositcat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discussion_comments`
--
ALTER TABLE `discussion_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_templates`
--
ALTER TABLE `email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `email_template_fields`
--
ALTER TABLE `email_template_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=375;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_triggers`
--
ALTER TABLE `event_triggers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expensecat`
--
ALTER TABLE `expensecat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoicestatus`
--
ALTER TABLE `invoicestatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leadssources`
--
ALTER TABLE `leadssources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `leadsstatus`
--
ALTER TABLE `leadsstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `meetings`
--
ALTER TABLE `meetings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `milestones`
--
ALTER TABLE `milestones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payment_modes`
--
ALTER TABLE `payment_modes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `pending_process`
--
ALTER TABLE `pending_process`
  MODIFY `process_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `productcategories`
--
ALTER TABLE `productcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projectmembers`
--
ALTER TABLE `projectmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projectservices`
--
ALTER TABLE `projectservices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recurring`
--
ALTER TABLE `recurring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `role_permission_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `staff_work_plan`
--
ALTER TABLE `staff_work_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subtasks`
--
ALTER TABLE `subtasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_columns`
--
ALTER TABLE `table_columns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasktimer`
--
ALTER TABLE `tasktimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ticketreplies`
--
ALTER TABLE `ticketreplies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `todo`
--
ALTER TABLE `todo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendors_groups`
--
ALTER TABLE `vendors_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_sales`
--
ALTER TABLE `vendor_sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `webleads`
--
ALTER TABLE `webleads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

  INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_email', 'login_email', 'new_contact_added');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '6', 'login_password', 'login_password', 'new_contact_added');
INSERT INTO `email_templates` (`id`, `relation`, `name`, `subject`, `message`, `from_name`, `status`, `display`, `attachment`) VALUES (NULL, 'appointment', 'new_appointment', 'New Appointment', '<p>Hello {staff_name},<br></p><div>{contact_name} has requested for appointment.<br></div><div><br></div><div>Appointment Date: <strong>{appointment_date}</strong><br></div><div>Appointment Time: <strong>{appointment_time}</strong><strong><br data-mce-bogus="1"></strong></div><div><strong><br data-mce-bogus="1"></strong></div><div>Please contact us for more information.<br></div><div><br></div><div>Kind Regards,</div><div><br></div><div><strong>{company_name},</strong></div><div>{company_email}</div>', 'Ciuis CRM', '1', '1', '1');
INSERT INTO `email_template_fields` (`id`, `template_id`, `field_name`, `field_value`, `template_name`) VALUES (NULL, '63', 'staff_name', 'staff_name', 'new_appointment'), (NULL, '63', 'customer_name', 'customer_name', 'new_appointment'), (NULL, '63', 'contact_name', 'contact_name', 'new_appointment'), (NULL, '63', 'appointment_date', 'appointment_date', 'new_appointment'), (NULL, '63', 'appointment_time', 'appointment_time', 'new_appointment'), (NULL, '63', 'company_name', 'company_name', 'new_appointment'), (NULL, '63', 'company_email', 'company_email', 'new_appointment');

ALTER TABLE `settings` CHANGE `unitseparator` `thousand_separator` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto', CHANGE `currencyposition` `currency_position` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'auto';
ALTER TABLE `settings` ADD `decimal_separator` VARCHAR(50) NULL DEFAULT 'auto' AFTER `currency_position`, ADD `currency_display` VARCHAR(50) NULL DEFAULT 'code' AFTER `decimal_separator`;

ALTER TABLE `settings` ADD `email_type` TINYINT(1) NULL DEFAULT '1' AFTER `languageid`;

UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'event_reminder';
UPDATE `email_templates` SET `relation` = 'staff' WHERE `email_templates`.`name` = 'new_appointment';

INSERT INTO `languages` (`id`, `langcode`, `name`, `foldername`) VALUES (NULL, 'bg_BG', 'bulgarian', 'bulgarian');