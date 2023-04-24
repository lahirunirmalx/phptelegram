
# PHP Telegram Client

This is a PHP Telegram Client that allows you to easily send and receive messages from Telegram using PHP. It is designed to be simple and easy to use, even for developers who are new to working with the Telegram API.

## Getting Started

To get started using the PHP Telegram Client, you will need to have a Telegram account and a bot token. You can create a bot token by following the instructions in the [Telegram documentation](https://core.telegram.org/bots#creating-a-new-bot).

Once you have your bot token, you can install the PHP Telegram Client using [Composer](https://getcomposer.org/). To install, simply run the following command:

```
composer require php-telegram-client/php-telegram-client
```

## Sending a Message

To send a message using the PHP Telegram Client, you will first need to create an instance of the `TelegramClient` class:

```php
use TelegramClient\TelegramClient;

$telegramClient = new TelegramClient('your_bot_token_here');
```

Once you have created the client, you can use the `sendMessage` method to send a message:

```php
$telegramClient->sendMessage('chat_id_here', 'Hello, world!');
```

## Receiving Messages

To receive messages using the PHP Telegram Client, you will need to set up a webhook. You can set up a webhook by following the instructions in the [Telegram documentation](https://core.telegram.org/bots/api#getting-updates).

Once you have set up a webhook, you can use the `getUpdates` method to retrieve new messages:

```php
$updates = $telegramClient->getUpdates();

foreach ($updates as $update) {
    $message = $update->getMessage();
    $chatId = $message->getChat()->getId();
    $text = $message->getText();

    $telegramClient->sendMessage($chatId, "You said: $text");
}
```

## Contributing

If you would like to contribute to the PHP Telegram Client, please feel free to submit a pull request. Before submitting a pull request, please make sure that your code adheres to the [PSR-2 coding style guide](https://www.php-fig.org/psr/psr-2/) and that all tests pass.

## License

The PHP Telegram Client is open source software licensed under the [MIT license](https://opensource.org/licenses/MIT).