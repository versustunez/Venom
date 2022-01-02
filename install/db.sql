create table if not exists seoData
(
    id       int(255) auto_increment not null unique primary key,
    seo      varchar(255)            not null,
    raw      varchar(255)            not null,
    isActive tinyint(1) default 1    null
)
    comment 'seo url mapping';

create table if not exists metaTagData
(
    id       int(255) auto_increment not null unique primary key,
    content  JSON                    not null,
    isActive tinyint(1) default 1    null
)
    comment 'Meta Tag File';

create table if not exists language
(
    id        int(255) auto_increment not null unique primary key,
    language  varchar(255)            not null,
    shortTag  varchar(255)            not null,
    isActive  tinyint(1) default 1    null,
    isDefault tinyint(1) default 0    null
)
    comment 'Language File';

create table if not exists data
(
    id        int(255) auto_increment not null unique primary key,
    identity  varchar(255)            not null unique,
    isActive  tinyint(1) default 1    null,
    generated longtext                not null,
    raw       longtext                not null,
    datatype  enum ('content', 'form')
)
    comment 'DataLoader File';

create table if not exists users
(
    id        int(255) auto_increment not null unique primary key,
    username  varchar(255)            not null unique,
    firstname varchar(255)            not null,
    lastname  varchar(255)            not null,
    email     varchar(255)            not null,
    password  varchar(255)            not null,
    token     varchar(255)            not null,
    salt      varchar(255)            not null,
    roleId    text       default '0'  not null,
    isActive  tinyint(1) default 1    null
)
    comment 'User File';

create table if not exists roles
(
    id       int(255) auto_increment not null unique primary key,
    name     varchar(255)            not null unique,
    content  JSON                    not null,
    isActive tinyint(1) default 1    null
)