# LogglyBundle

[Loggly](http://loggly.com/) handler for [Monolog](https://github.com/Seldaek/monolog) as a Symfony bundle.

The bundle is inspired from [Monologgly](https://github.com/pradador/Monologgly)

## Installation

Deps

    [WhitewashingLogglyBundle]
        git=https://github.com/beberlei/WhitewashingLogglyBundle.git
        target=/bundles/Whitewashing/LogglyBundle

Kernel

    $bundles = array(
        //..
        new Whitewashing\LogglyBundle\WhitewashingLogglyBundle(),
    );

Autoload:

    $loader->registerNamespaces(array(
        //..
        'Whitewashing\LogglyBundle' => __DIR__.'/../vendor/bundles',
    ));

## Configuration

Configure Monolog

    monolog:
        handlers:
            main:
                type:         fingers_crossed
                action_level: error
                handler:      loggly
            loggly:
                type: service
                id: whitewashing_loggly.monolog_handler

Configure Loggly:

    whitewashing_loggly:
        # Loggly input key
        key: abcdefg

        # Loggly API host
        host: logs.loggly.com

        # Loggly API port (443 for HTTPS, 80 for HTTP)
        port: 443

        # Level to be logged (defaults to DEBUG)
        level: DEBUG

        bubble: true
