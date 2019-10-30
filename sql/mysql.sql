CREATE TABLE `{schedule}`
(
    `id`                INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id`           INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `provider_id`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `service_id`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `schedule_previous` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `schedule_next`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `create_by`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `update_by`         INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_create`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_update`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',

    'request_time'      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    'request_from'      DATETIME            NULL,
    'request_to'        DATETIME            NULL,

    `amount`            DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
    `payment_status`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `reserve_status`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{history}`
(
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `provider_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `schedule_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `create_by`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `update_by`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `description` MEDIUMTEXT,
    PRIMARY KEY (`id`)
);

CREATE TABLE `{provider}`
(
    `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `title`   VARCHAR(255)        NOT NULL DEFAULT '',
    `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{service}`
(
    `id`     INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`  VARCHAR(255)        NOT NULL DEFAULT '',
    `amount` DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
    `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
);