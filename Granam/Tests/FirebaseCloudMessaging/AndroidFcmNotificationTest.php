<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace Granam\Tests\FirebaseCloudMessaging;

use Granam\FirebaseCloudMessaging\AndroidFcmNotification;

class AndroidFcmNotificationTest extends DeviceFcmNotificationTest
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_set_android_channel_id(): void
    {
        $this->I_can_set_parameter('androidChannelId', '123');
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_set_icon(): void
    {
        $this->I_can_set_parameter('icon', 'foo');
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_set_tag(): void
    {
        $this->I_can_set_parameter('tag', 'foo');
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function I_can_set_color(): void
    {
        $this->I_can_set_parameter('color', '#906090');
    }

    /**
     * @test
     * @expectedException \Granam\FirebaseCloudMessaging\Exceptions\InvalidRgbFormatOfAndroidColor
     * @expectedExceptionMessageRegExp ~gfffff~
     */
    public function I_can_not_set_invalid_color(): void
    {
        (new AndroidFcmNotification())->setColor('#gfffff');
    }

    /**
     * @test
     */
    public function I_can_ask_it_if_is_silent(): void
    {
        $androidFcmNotification = new AndroidFcmNotification();
        self::assertFalse($androidFcmNotification->isSilent(), 'Android push notification can not be silent');
    }

    /**
     * @test
     */
    public function I_can_ask_it_if_can_be_silenced(): void
    {
        $androidFcmNotification = new AndroidFcmNotification();
        self::assertFalse($androidFcmNotification->canBeSilenced(), 'Android push notification can not be silent');
    }

    /**
     * @test
     * @expectedException \Granam\FirebaseCloudMessaging\Exceptions\AndroidFcmNotificationCanNotBeSilenced
     */
    public function I_can_set_it_silent(): void
    {
        $androidFcmNotification = new AndroidFcmNotification();
        $androidFcmNotification->setSilent();
    }
}