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
    `reserve_time`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `reserve_date`      DATE                NULL     DEFAULT NULL,
    `reserve_from`      VARCHAR(8)          NOT NULL DEFAULT '00:00',
    `reserve_to`        VARCHAR(8)          NOT NULL DEFAULT '00:00',
    `amount`            DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
    `currency`          VARCHAR(8)          NOT NULL DEFAULT NULL,
    `payment_status`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `reserve_status`    TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
    `order_id`          INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{history}`
(
    `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `provider_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `service_id`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `schedule_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `create_by`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `update_by`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    `description` MEDIUMTEXT,
    `image`       VARCHAR(255)        NOT NULL DEFAULT '',
    PRIMARY KEY (`id`)
);

CREATE TABLE `{provider}`
(
    `id`      INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `user_id` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
    `title`   VARCHAR(255)        NOT NULL DEFAULT '',
    `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `user_id` (`user_id`),
    KEY `status` (`status`)
);

CREATE TABLE `{service}`
(
    `id`       INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `title`    VARCHAR(255)        NOT NULL DEFAULT '',
    `amount`   DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
    `currency` VARCHAR(8)          NOT NULL DEFAULT 'USD',
    `status`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `status` (`status`)
);

CREATE TABLE `{holiday}`
(
    `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `provider_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `date`        DATE             NULL     DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `date_provider` (`date`, `provider_id`),
    KEY `date` (`date`),
    KEY `provider_id` (`provider_id`)
);

CREATE TABLE `{time}`
(
    `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
    `provider_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
    `date`        DATE             NULL     DEFAULT NULL,
    `start`       VARCHAR(8)       NOT NULL DEFAULT '00:00',
    `end`         VARCHAR(8)       NOT NULL DEFAULT '00:00',
    PRIMARY KEY (`id`),
    UNIQUE KEY `date_provider` (`date`, `provider_id`),
    KEY `date` (`date`),
    KEY `provider_id` (`provider_id`)
);

CREATE TABLE `{status}`
(
    `id`    INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
    `type`  VARCHAR(32)         NOT NULL DEFAULT '',
    `title` VARCHAR(32)         NOT NULL DEFAULT '',
    `value` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `type` (`type`)
);