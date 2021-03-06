# PHP Firebase Cloud Messaging
PHP API for [Firebase Cloud Messaging from Google](https://firebase.google.com/docs/).

This library supports Messages/Notifications via HTTP only.

*Requires **PHP 7.1**, if you have to rely on lower version, use [original PHP FCM library](https://github.com/sngrl/php-firebase-cloud-messaging) or [some clone of it](https://github.com/sngrl/php-firebase-cloud-messaging/network).*

## Send message to Device or Topic

```php
<?php
use granam\FirebaseCloudMessaging\FcmClient;
use granam\FirebaseCloudMessaging\FcmMessage;
use granam\FirebaseCloudMessaging\Target\FcmTopicTarget;
use granam\FirebaseCloudMessaging\Target\FcmDeviceTarget;
use granam\FirebaseCloudMessaging\AndroidFcmNotification;

$client = new FcmClient(new \GuzzleHttp\Client(), '_YOUR_SERVER_KEY_');
$message = new FcmMessage(new FcmDeviceTarget('_YOUR_DEVICE_TOKEN_'));
$message->setPriority('high')
    ->setNotification(new AndroidFcmNotification('A message from Foo!', 'Hi Bar, how are you?'))
    ->setData(['photo' => 'https://example.com/photos/Foo.png']);
// you can NOT combine multiple recipient types as topic and device in a single message
$messageForTopic = new FcmMessage(new FcmTopicTarget('_YOUR_TOPIC_'));
$messageForTopic
    ->setNotification(new AndroidFcmNotification('Foo is looking for you!', 'Here you are!'))
    ->setData(['map' => 'https://example.com/maps/foo']);

$response = $client->send($message);
var_dump($response);
$responseFromTopic = $client->send($messageForTopic);
var_dump($responseFromTopic);
```

## Send silent push notification to a device

*App will have to process the notification by its own as system will not show it.*

```php
<?php
use granam\FirebaseCloudMessaging\FcmClient;
use granam\FirebaseCloudMessaging\FcmMessage;
use granam\FirebaseCloudMessaging\Target\FcmDeviceTarget;

$client = new FcmClient(new \GuzzleHttp\Client(), '_YOUR_SERVER_KEY_');
$message = new FcmMessage(new FcmDeviceTarget('_YOUR_DEVICE_TOKEN_'));
$message->setData([
        'title' => 'A message from Foo!',
        'message' => 'Hi Bar, how are you?',
        'photo' => 'https://example.com/photos/Foo.png'
    ]);
$response = $client->send($message);
var_dump($response);
```

## Send message to multiple Devices

```php
<?php
use granam\FirebaseCloudMessaging\FcmMessage;
use granam\FirebaseCloudMessaging\Target\FcmDeviceTarget;
use granam\FirebaseCloudMessaging\IosFcmNotification;

$message = new FcmMessage(new FcmDeviceTarget('_YOUR_DEVICE_TOKEN_'));
$message->addTarget(new FcmDeviceTarget('_YOUR_DEVICE_TOKEN_2_'))
    ->addTarget(new FcmDeviceTarget('_YOUR_DEVICE_TOKEN_3_'))
    ->setPriority('high')
    ->setNotification(new IosFcmNotification('You are wanted', 'We got some issue here, where are you? We need you.'))
    ->setData(['attachment' => 'data:image/gif;base64,FooBar==']);
// ...
```

## Send message to multiple Topics

See Firebase documentation for sending to [combinations of multiple topics](https://firebase.google.com/docs/cloud-messaging/topic-messaging#sending_topic_messages_from_the_server).

```php
<?php
use granam\FirebaseCloudMessaging\FcmMessage;
use granam\FirebaseCloudMessaging\JsFcmNotification;
use granam\FirebaseCloudMessaging\Target\FcmTopicTarget;

$message = new FcmMessage(new FcmTopicTarget('_YOUR_TOPIC_'));
$message->addTarget(new FcmTopicTarget('_YOUR_TOPIC_2_'))
    ->addTarget(new FcmTopicTarget('_YOUR_TOPIC_3_'))
    ->setPriority('high')
    ->setNotification(new JsFcmNotification('some title', 'some body'))
    ->setData(['key' => 'value'])
    // will send to devices subscribed to topic 1 AND topic 2 or 3
    ->setCondition('%s && (%s || %s)');
```

## Subscribe user to the topic
```php
<?php
use granam\FirebaseCloudMessaging\FcmClient;

$client = new FcmClient(new \GuzzleHttp\Client(), '_YOUR_SERVER_KEY_');
$response = $client->subscribeToTopic('_SOME_TOPIC_', ['_FIRST_DEVICE_TOKEN_', '_SECOND_DEVICE_TOKEN_']);
var_dump($response->getStatusCode());
var_dump($response->getBody()->getContents());
```

## Remove user subscription from the topic
```php
<?php
use granam\FirebaseCloudMessaging\FcmClient;

$client = new FcmClient(new \GuzzleHttp\Client(), '_YOUR_SERVER_KEY_');

$response = $client->unsubscribeFromTopic('_SOME_TOPIC_', ['_FIRST_DEVICE_TOKEN_', '_SECOND_DEVICE_TOKEN_']);
var_dump($response->getStatusCode());
var_dump($response->getBody()->getContents());
```

## Install
```
composer require granam/php-firebase-cloud-messaging
```
