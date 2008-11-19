
#
# Table structure for table 'xt_monitor_hf_hash_files'
#

CREATE TABLE  `xt_monitor_hf_hash_files` (
  `hf_30_file` varchar(255) NOT NULL,
  `hf_30_hash` varchar(32) default NULL,
  PRIMARY KEY  (`hf_30_file`)
) TYPE=MYISAM;




#
# Table structure for table 'xt_monitor_md_module'
#

CREATE TABLE `xt_monitor_md_module` (
  `md_30_dirname` varchar(50) NOT NULL,
  `md_11_include` tinyint(1) unsigned default NULL,
  `md_33_tabelas` text,
  PRIMARY KEY  (`md_30_dirname`)
) TYPE=MYISAM;



#
# Table structure for table 'xt_monitor_par_param'
#

CREATE TABLE `xt_monitor_par_param` (
  `par_10_id` int(10) unsigned NOT NULL auto_increment,
  `par_30_ext` varchar(255) default NULL,
  `par_33_pastas_off` text,
  `par_30_emails` varchar(255) default NULL,
  `par_30_dirpath` varchar(255) default NULL,
  `par_11_gerarq` tinyint(1) unsigned default NULL,
  `par_30_nomearq` varchar(12) default NULL,
  PRIMARY KEY  (`par_10_id`)
)TYPE=MYISAM;



#
# Table structure for table 'xt_monitor_qry_querys'
#

CREATE TABLE  `xt_monitor_qry_querys` (
  `qry_10_id` int(10) unsigned NOT NULL auto_increment,
  `qry_10_dt` int(10) unsigned default NULL,
  `qry_14_uid` mediumint(8) unsigned default NULL,
  `qry_30_ip` varchar(20) default NULL,
  `qry_30_script` varchar(255) default NULL,
  `qry_33_sql` text,
  `qry_33_obs` text,
  `qry_14_error` mediumint(8) unsigned default NULL,
  `qry_33_menerro` text,
  `qry_30_module` varchar(50) default NULL,
  `qry_30_table` varchar(50) default NULL,
  `qry_30_referer` varchar(255) default NULL,
  `qry_30_user_agent` varchar(255) default NULL,
  PRIMARY KEY  (`qry_10_id`),
  KEY `qry_10_dt` (`qry_10_dt`),
  KEY `qry_30_module` (`qry_30_module`),
  KEY `qry_14_uid` (`qry_14_uid`),
  KEY `qry_30_table` (`qry_30_table`)
) TYPE=MYISAM;

