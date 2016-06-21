<?php

return ['orm.proxies_dir' => __DIR__ . '/../cache/doctrine/proxies',
    'orm.default_cache' => 'array',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'type' => 'annotation',
                'path' => __DIR__ . '/../src/Walgistics/ValueObject',
                'namespace' => 'Walgistics\\ValueObject',
            ),
        ))];