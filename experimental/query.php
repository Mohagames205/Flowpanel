<?php

$querydb = "create table audit_log
(
  changer            varchar(225) not null,
  change_type        varchar(225) not null,
  change_slachtoffer varchar(225) not null,
  old_rank_id        int          not null,
  new_rank_id        int          not null,
  reason             varchar(225) not null,
  audit_id           int(10) auto_increment,
  change_date        varchar(100) not null,
  constraint audit_id
    unique (audit_id)
)
  charset = utf8;

create table gebruikers
(
  id      int(3) auto_increment
    primary key,
  name    varchar(225) not null,
  balance int(225)     not null
);

create table ranks
(
  rank_id   int(10)      not null,
  rank_name varchar(225) not null,
  perm_id   int          not null,
  constraint rank_id
    unique (rank_id)
);

create table reports
(
  report_id int not null
);

create table user_ranks
(
  username varchar(225) charset utf8 not null
    primary key,
  rank_id  int                       not null,
  node     varchar(2)                not null
);

create table users
(
  user_id  int(202) auto_increment,
  username varchar(15)      not null,
  password varchar(225)     not null,
  perm_id  int(5) default 1 not null,
  constraint user_id
    unique (user_id)
);

create table warns
(
  warn_id       int auto_increment
    primary key,
  waarschuwer   varchar(225) not null,
  gewaarschuwde varchar(225) not null,
  reden         varchar(225) not null,
  warn_type     varchar(225) not null
);

";

?>