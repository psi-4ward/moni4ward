-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

CREATE TABLE `tl_moni4ward_server` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `alias` varbinary(128) NOT NULL default '',
  `ip` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `tl_moni4ward_service` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',  
  `lastCheck` int(10) unsigned NOT NULL default '0',
  `exclusive` char(1) NOT NULL default '',
  `failCount` int(10) unsigned NOT NULL default '0',
  `status` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `type` varchar(15) NOT NULL default '',
  `alerts` blob NULL,
  `http_url` varchar(255) NOT NULL default '',
  `http_port` varchar(6) NOT NULL default '',
  `http_response` text NULL,
  `port_port` varchar(6) NOT NULL default '',  
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

CREATE TABLE `tl_moni4ward_service_log` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `status` char(1) NOT NULL default '',
  `value` text NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

CREATE TABLE `tl_moni4ward_alert` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `type` varchar(15) NOT NULL default '',
  `email_subject` varchar(255) NOT NULL default '',
  `email_msg` text NULL,
  `email_sendto` varchar(255) NOT NULL default '',
  `email_from` varchar(255) NOT NULL default '',
  `email_fromName` varchar(255) NOT NULL default '',  
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
