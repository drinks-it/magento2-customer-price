<?php

namespace EPuzzle\CustomerPrice\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '0.0.1', '<')) {
            $setup->getConnection()->query("create table if not exists epuzzle_customer_price
                (   item_id     smallint auto_increment comment 'Item ID'   primary key,
                    product_id  int unsigned                                not null comment 'Product ID',
                    customer_id int unsigned                                not null comment 'Customer ID',
                    price       decimal(12, 4)                              not null comment 'Product Price',
                    qty         decimal(12, 4)                              null comment 'Quantity',
                    website_id  smallint unsigned default '0'               not null comment 'Website ID',
                    created_at  timestamp         default CURRENT_TIMESTAMP not null comment 'Creation Time',
                    updated_at  timestamp         default CURRENT_TIMESTAMP not null on update CURRENT_TIMESTAMP comment 'Modification Time',
                    constraint EPUZZLE_CSTR_PRICE_PRD_ID_CAT_PRD_ENTT_ENTT_ID
                        foreign key (product_id) references catalog_product_entity (entity_id)
                            on delete cascade,
                    constraint EPUZZLE_CUSTOMER_PRICE_CUSTOMER_ID_CUSTOMER_ENTITY_ENTITY_ID
                        foreign key (customer_id) references customer_entity (entity_id)
                            on delete cascade,
                    constraint EPUZZLE_CUSTOMER_PRICE_WEBSITE_ID_STORE_WEBSITE_WEBSITE_ID
                        foreign key (website_id) references store_website (website_id)
                            on delete cascade
                )
                comment 'Customer Price Table' charset = utf8;"
            );
        }
    }
}
