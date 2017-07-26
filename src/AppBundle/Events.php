<?php

namespace AppBundle;

/**
 * This class defines the names of all the events dispatched in
 * the application.
 */
final class Events
{
    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const USER_REGISTERED = 'user.registered';

    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const USER_RESET = 'user.reset';

    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    const CONTACT_MESSAGE = 'contact.message';
}
