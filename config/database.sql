-- **********************************************************
-- *                                                        *
-- * IMPORTANT NOTE                                         *
-- *                                                        *
-- * Do not import this file manually but use the Contao    *
-- * install tool to create and maintain database tables!   *
-- *                                                        *
-- **********************************************************

--
-- Table `tl_easysale_product`
--

CREATE TABLE `tl_easysale_product` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `product_name` varchar(255) NOT NULL default '',
  `product_number` varchar(255) NOT NULL default '',
  `product_category` varchar(255) NOT NULL default '',
  `product_note` text NOT NULL,
  `product_price` varchar(255) NOT NULL default '0,00',
  `product_tax` decimal(20,0) NOT NULL default '0',
  `product_path` varchar(255) NOT NULL default '',
  `addImage` char(1) NOT NULL default '',
  `product_picture` varchar(255) NOT NULL default '',
  `alt` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `size` varchar(64) NOT NULL default '',
  `imagemargin` varchar(128) NOT NULL default '',
  `imageUrl` varchar(255) NOT NULL default '',
  `fullsize` char(1) NOT NULL default '',
  `caption` varchar(255) NOT NULL default '',
  `floating` varchar(32) NOT NULL default '',
  `seller_mail` varchar(255) NOT NULL default '',
  `product_active` char(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table `tl_easysale_transactions`
--

CREATE TABLE `tl_easysale_transactions` (
-- Contao
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
-- easysale
  `ipn` varchar(255) NOT NULL default '',
  `return` varchar(255) NOT NULL default '',
  `custom` varchar(255) NOT NULL default '',
  `cancel_return` varchar(255) NOT NULL default '',
  `quantity` int(10) unsigned NOT NULL default '0',
  `business` varchar(255) NOT NULL default '',
  `item_number` varchar(255) NOT NULL default '',
  `item_name` varchar(255) NOT NULL default '',
  `amount` varchar(255) NOT NULL default '',
  `invoice` varchar(255) NOT NULL default '',
  `transaction_ip` varchar(255) NOT NULL default '',
-- paypal
  `mc_gross` varchar(255) NOT NULL default '',
  `protection_eligibility` varchar(255) NOT NULL default '',
  `payer_id` varchar(255) NOT NULL default '',
  `tax` varchar(255) NOT NULL default '',
  `payment_date` varchar(255) NOT NULL default '',
  `payment_status` varchar(255) NOT NULL default '',
  `charset` varchar(255) NOT NULL default '',
  `first_name` varchar(255) NOT NULL default '',
  `mc_fee` varchar(255) NOT NULL default '',
  `notify_version` varchar(255) NOT NULL default '',
  `payer_status` varchar(255) NOT NULL default '',
  `payer_email` varchar(255) NOT NULL default '',
  `verify_sign` varchar(255) NOT NULL default '',
  `txn_id` varchar(255) NOT NULL default '',
  `payment_type` varchar(255) NOT NULL default '',
  `last_name` varchar(255) NOT NULL default '',
  `receiver_email` varchar(255) NOT NULL default '',
  `payment_fee` varchar(255) NOT NULL default '',
  `receiver_id` varchar(255) NOT NULL default '',
  `txn_type` varchar(255) NOT NULL default '',
  `mc_currency` varchar(255) NOT NULL default '',
  `residence_country` varchar(255) NOT NULL default '',
  `test_ipn` varchar(255) NOT NULL default '',
  `handling_amount` varchar(255) NOT NULL default '',
  `transaction_subject` varchar(255) NOT NULL default '',
  `payment_gross` varchar(255) NOT NULL default '',
  `shipping` varchar(255) NOT NULL default '',
  `auth` varchar(255) NOT NULL default '',
  `error` varchar(255) NOT NULL default '',
  `address_status` varchar(255) NOT NULL default '',
  `ipn_track_id` varchar(255) NOT NULL default '',
-- download
  `transaction_download01` varchar(255) NOT NULL default '',
  `transaction_download02` varchar(255) NOT NULL default '',
  `transaction_download03` varchar(255) NOT NULL default '',
-- sql
  PRIMARY KEY  (`id`)
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `productmulti` text NOT NULL,
  `productfields` text NOT NULL,
  `showheadline` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table `tl_content`
--

CREATE TABLE `tl_content` (
  `productmulti` text NOT NULL,
  `jumpTo` int(10) unsigned NOT NULL default '0',
  `productfields` text NOT NULL,
  `showheadline` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;