delete from tx_auth_assignment;
delete from tx_auth_item_child;
delete from tx_auth_item;

/*Data for the table `tx_auth_item` */
/* TYPE 1 = ROLE */
insert  into `tx_auth_item`
    (`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`)
values
    ('admin',1,'Admin',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('coordinator',1,'Coordinator',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('regular',1,'Regular',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('guest',1,'Guest',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP());


insert  into `tx_auth_assignment`
    (`item_name`,`user_id`,`created_at`)
values
    ('admin','1',UNIX_TIMESTAMP());

insert  into `tx_auth_item`(`name`,`type`,`description`,`rule_name`,`data`,`created_at`,`updated_at`)
values
    ('create-user-coordinator',2,'Create User Coordinator',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('create-user-regular',2,'Create User Regular',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP());

insert  into `tx_auth_item`
    (`name`,`type`,`description`,`rule_name`,`data`,
     `created_at`,`updated_at`)
values
    ('index-master',2,'Index Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('create-master',2,'Create Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('update-master',2,'Update Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('view-master',2,'View Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('delete-master',2,'Delete Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('report-master',2,'Report Master',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP());

insert  into `tx_auth_item`
    (`name`,`type`,`description`,`rule_name`,`data`,
     `created_at`,`updated_at`)
values
    ('index-transaction',2,'Index Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('create-transaction',2,'Create Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('update-transaction',2,'Update Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('view-transaction',2,'View Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('delete-transaction',2,'Delete Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP()),
    ('report-transaction',2,'Report Transaction',NULL,NULL,UNIX_TIMESTAMP(),UNIX_TIMESTAMP());


insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('admin','index-master'),
    ('admin','create-master'),
    ('admin','update-master'),
    ('admin','view-master'),
    ('admin','delete-master'),
    ('admin','report-master');

insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('admin','index-transaction'),
    ('admin','create-transaction'),
    ('admin','update-transaction'),
    ('admin','view-transaction'),
    ('admin','delete-transaction'),
    ('admin','report-transaction');

insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('regular','index-transaction'),
    ('regular','create-transaction'),
    ('regular','update-transaction'),
    ('regular','view-transaction'),
    ('regular','delete-transaction'),
    ('regular','report-transaction');

insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('regular','update-profile'),
    ('regular','view-profile');

insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('guest','index-asset'),
    ('guest','view-asset');

insert  into `tx_auth_item_child`(`parent`,`child`)
values
    ('guest','index-article'),
    ('guest','view-article');
