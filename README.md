# LogglyBundle

[Loggly](http://loggly.com/) handler for [Monolog](https://github.com/Seldaek/monolog) as a Symfony bundle.

The bundle is inspired from [Monologgly](https://github.com/pradador/Monologgly)

## Installation

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
        key: abcdefg
        host: logs.loggly.com
        post: 443

